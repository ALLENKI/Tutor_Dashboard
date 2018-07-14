<?php

namespace Aham\Interactions;

use Carbon;
use DB;

use Aham\Models\SQL\LocationCalendar;
use Aham\Models\SQL\DayType;
use Aham\Models\SQL\ClassroomSlot;
use Aham\Models\SQL\Classroom;
use Aham\Models\SQL\Slot;
use Aham\Models\SQL\ClassTiming;
use Aham\Models\SQL\EpisodeTiming;
use Aham\Models\SQL\Unit;
use Aham\Models\SQL\ClassUnit;
use Aham\Models\SQL\AhamClass;
use Aham\Models\SQL\TeacherCertification;
use Aham\Models\SQL\GuestSeriesEpisode;

class ClassSchedule {

	public static function prepareAndGetAvailableSlots($class, $unit, $date)
	{
		$ahamClass = AhamClass::with('topic.units','location')->find($class);

		$unit = Unit::find($unit);

		$previousUnit = Unit::find($unit->previousUnit());

		$time_constraint = NULL;

        if(!is_null($previousUnit))
        {
        	$previousUnitTiming = ClassTiming::where('class_id',$ahamClass->id)
                               ->where('unit_id',$previousUnit->id)
                               ->first();

            $date = Carbon::createFromTimestamp(strtotime($date));

            if($previousUnitTiming->date->isSameDay($date))
            {
                $time_constraint = $previousUnitTiming->slot->end_time;
            }
        }

        $currentUnitTiming = ClassTiming::where('class_id',$ahamClass->id)
                               ->where('unit_id',$unit->id)
                               ->first();

        $consider_slot = [];

        if($currentUnitTiming)
        {
            $consider_slot[] = $currentUnitTiming->slot_id;
        }

        $availableSlots = static::getAvailableSlots($date,$ahamClass->location, $time_constraint, $consider_slot);

        return $availableSlots;
	}

	public static function getAvailableSlotsForDate($date,$episode)
	{
		$episode = GuestSeriesEpisode::find($episode);

		// dd($episode);

		$location = $episode->location;

		$dayType = static::getDayType($date,$location);

		$time_constraint = NULL;
		$consider_slot = [];

		if($episode->timings->count())
		{
			if(strtotime($episode->timings->first()->date) == strtotime($date))
			{
				$consider_slot = $episode->timings->pluck('slot_id')->toArray();
			}
		}

		// dd($consider_slot);

        $availableSlots = static::getAvailableSlots($date,$location, $time_constraint, $consider_slot);

        return $availableSlots;
	}


	public static function getDayType($date, $location)
	{
		$date = Carbon::createFromTimestamp(strtotime($date));

		if(!$date->isWeekend())
		{
			$dayType = LocationCalendar::with('dayType')
							->where('location_id',$location->id)
							->where('from_date','<=',$date)
							->where('to_date','>=',$date)
							->first();
			if($dayType)
			{
				$dayType = $dayType->dayType;
			}
			else
			{
				return [];
			}				
			
		}
		else
		{
			$dayType = DayType::where('slug','weekend')->first();
		}

		return $dayType;
	}

	public static function getAvailableSlots($date, $location, $time_constraint, $consider_slot)
	{

		$date = Carbon::createFromTimestamp(strtotime($date));

		if(!$date->isWeekend())
		{
			$dayType = LocationCalendar::with('dayType')
							->where('location_id',$location->id)
							->where('from_date','<=',$date)
							->where('to_date','>=',$date)
							->first();
			if($dayType)
			{
				$dayType = $dayType->dayType;
			}
			else
			{
				return [];
			}				
			
		}
		else
		{
			$dayType = DayType::where('slug','weekend')->first();
		}

		// dd($dayType);

		// Find all classrooms of this location

		$classrooms = $location->classrooms->pluck('id')->toArray();

		// find all slots
		$slots = ClassroomSlot::where('day_type_id',$dayType->id)
								->whereIn('classroom_id',$classrooms)
								->pluck('slot_id')
								->toArray();

		// find booked slots
		$booked_slots = ClassTiming::where('date',$date)
								   ->where('location_id',$location->id)
								   ->whereHas('ahamClass',function($query){
								   		$query->where('status','<>','cancelled');
								   })
								   ->pluck('slot_id')
								   ->toArray();

		$episode_slots = EpisodeTiming::where('date',$date)
								   ->where('location_id',$location->id)
								   ->whereHas('series',function($query){
								   		$query->where('status','<>','cancelled');
								   })
								   ->pluck('slot_id')
								   ->toArray();

		$booked_slots = array_merge($booked_slots,$episode_slots);

		// Get final slots

		$final_slots = array_diff_once($slots,$booked_slots);

		// To use the current slot

		// if(!is_null($consider_slot))
		// {
		// 	array_push($final_slots, $consider_slot);
		// }

		if(count($consider_slot))
		{
			$final_slots = array_merge($final_slots, $consider_slot);
		}

		// dd($final_slots);

		$final_slots = array_unique($final_slots);

		$slots = Slot::whereIn('id',$final_slots)
					->orderBy('start_time','asc');

		if(!is_null($time_constraint))
		{
			$slots->where('start_time','>', $time_constraint);
		}

		$slots = $slots->get();

		$availableSlots = [];

		foreach($slots as $slot)
		{
			$availableSlots["'".$slot->id."'"] = $slot->present()->slotStyled;
		}

		// If the previous unit is scheduled on same day, then get slots after endtime of the previousUnit

		// Slots should match with the scheduling rules we have

		return $availableSlots;
	}

	public static function getAvailableClassrooms($class, $unit)
	{
		$timing = timing($unit, $class->id);

		// Get location
		$location = $class->location;

		$availableClassrooms = [];

		// Get available classrooms in that location 
		$classrooms = $location->classrooms->pluck('id')->toArray();


		// Check which classrooms are booked on that date and slot

		foreach($classrooms as $classroom)
		{
			$busy = ClassTiming::where('date',$timing->date)
					->where('classroom_id',$classroom)
					->where(function($query) use ($timing){	
						$query->whereBetween('start_time', [$timing->start_time,$timing->end_time])
		                      ->orWhereBetween('end_time', [$timing->start_time,$timing->end_time]);
					})
					->first();

			if(is_null($busy))
			{
				$availableClassrooms[] = $classroom;
			}
		}
		
		// Send remaining classrooms

		return Classroom::whereIn('id',$availableClassrooms)->pluck('name','id');
	}
	
	public static function getDateRange($ahamClass, $unitToBeScheduled)
	{

		$division = $ahamClass->schedulingRule->division;

		$max_days = $ahamClass->maximum_days-1;

		$units = clone $ahamClass->topic->units;

		$firstUnit = $units->first();

		$unitToBeScheduled = Unit::find($unitToBeScheduled);

		if(!$previousUnit = $unitToBeScheduled->previousUnit())
		{
				return	[
					'startDate' => NULL,
					'endDate' => NULL,
				];
		}

		// Divide into holes

		$holes = explode('-',$division);

		$unit_dates = [];

		foreach($holes as $index => $size)
		{
			// $units_group = [];

			for ($i=0; $i < $size; $i++) { 
				
				$unit_group = [];

				$unit_group['group'] = $index;
				$unit_group['code'] = $units->shift()->code;

				$unit_dates[] = $unit_group;
			}

		}

		$no_of_groups = count($holes);

		// Get schedule of previous unit

		$previousUnit = Unit::find($previousUnit);

		$previousUnitTiming = ClassTiming::where('class_id',$ahamClass->id)
							   ->where('unit_id',$previousUnit->id)
							   ->first();

		$firstUnitTiming = ClassTiming::where('class_id',$ahamClass->id)
							   ->where('unit_id',$firstUnit->id)
							   ->first();

		$previousUnitKey = array_search($previousUnit->code, array_column($unit_dates, 'code'));

		$previousUnitGroup = $unit_dates[$previousUnitKey]['group'];

		$currentUnitKey = array_search($unitToBeScheduled->code, array_column($unit_dates, 'code'));

		$currentUnitGroup = $unit_dates[$currentUnitKey]['group'];

		$remainingGroups = $no_of_groups - ($currentUnitGroup + 1);

		if($previousUnitGroup == $currentUnitGroup)
		{
			$startDate = clone $previousUnitTiming->date;
			$endDate = clone $previousUnitTiming->date;
		}
		else
		{

			$startDate = clone $previousUnitTiming->date;
			$startDate = $startDate->addDays(1);

			$endDate = clone $firstUnitTiming->date;
			$endDate = $endDate->addDays($max_days-$remainingGroups);
		}

		return 	[
					'startDate' => $startDate,
					'endDate' => $endDate ,
				];

	}

	public static function eligibleToSchedule($class, $unit)
	{
		$previousUnit = $unit->previousUnit();

		if(is_null($previousUnit))
		{
			return true;
		}

		$exists = ClassTiming::where('class_id',$class->id)
							   ->where('unit_id',$previousUnit)
							   ->first();

		if($exists)
		{
			return true;
		}

		return false;

	}

	public static function calculateRemainingUnits($class, $unit)
	{
		$units = $class->topic->units->pluck('id')->toArray();

		$key = array_search($unit->id, $units);

		return count($units)-1-$key;
	}


}
