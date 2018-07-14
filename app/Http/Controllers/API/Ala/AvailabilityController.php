<?php

namespace Aham\Http\Controllers\API\Ala;

use Aham\Http\Controllers\Controller;
use Aham\Http\Requests;
use Illuminate\Http\Request;

use League\Fractal;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;
use Dingo\Api\Routing\Helpers;

use League\Fractal\Manager;
use League\Fractal\Serializer\DataArraySerializer;
use League\Fractal\Serializer\ArraySerializer;
use Aham\Transformers\AhamClassTransformer;

use League\Fractal\Pagination\IlluminatePaginatorAdapter;

use Input;
use Validator;
use Carbon;

use Aham\Models\SQL\Location;
use Aham\Models\SQL\AhamClass;
use Aham\Models\SQL\ClassTiming;

class AvailabilityController extends BaseController
{
    public function __construct()
    {
        parent::__construct();
    }

    public function get(Request $request)
    {
    	$date = Carbon::createFromTimestamp(strtotime(request()->get('date')));
    	$location = request()->get('location');
    	$classroom = request()->get('classroom');
    	$max_days = request()->get('max_days');

        $otimings = [];

    	$range = range(strtotime("00:00"),strtotime("23:45"),15*60);

        foreach($range as $index => $time)
        {
            if($time >= strtotime("05:45") && $time <= strtotime("21:00"))
            {
                $timing = [];
                $timing['id'] = $index+1;
                $timing['time'] = date("H:i",$time);
                $timing['from_time'] = date("H:i",$time);
                $timing['to_time'] = date("H:i",$time);
                $otimings[] = $timing;
            }
        }

        $availability = [];
        $timings = [];

		for ($i=0; $i < $max_days; $i++) 
		{ 
			$date = Carbon::createFromTimestamp(strtotime(request()->get('date')))->addDays($i);

	    	$dbTimings = ClassTiming::where('date',$date)
					    				->where('location_id',$location)
					    				->where('classroom_id',$classroom)
					    				->get();

			$occupiedTimings = [];

			foreach($dbTimings as $dbTiming)
			{
				$rangeTimings = range(strtotime($dbTiming->start_time),strtotime($dbTiming->end_time),15*60);
				array_pop($rangeTimings);

				$occupiedTimings = array_merge($occupiedTimings,$rangeTimings);
			}

			$range = range(strtotime("00:00"),strtotime("23:45"),15*60);

            foreach($range as $index => $time)
            {
                if($time >= strtotime("05:45") && $time <= strtotime("21:00"))
                {
                    $timing = [];
                    $timing['id'] = $index+1;
                    $timing['time'] = date("H:i",$time);
                    $timing['occupied'] = false;
                    if(in_array($time, $occupiedTimings))
                    {
                        $timing['occupied'] = true;
                    }
                    $timing['available'] = !$timing['occupied'];
                    $timing['selectable'] = false;
                    $timings[$timing['id']] = $timing;
                }
            }

            $a = [];
            $a['name'] = $date->format('d-m-Y');
            $a['id'] = $i+1;
            $a['timings'] = $timings;

            $availability[$a['id']] = $a;

        }        

        $units = [];
        $units['rule'] = '1-1-2';
        $units['max_days'] = $max_days;

        return [
                'timings' => $otimings, 
                'availability' => $availability,
                'units_meta' => $units
            ];
    }

    public function classroomAvailability($start_date,$location,$classroom,$max_days, $ahamClass = null)
    {
    	$availability = [];
        $timings = [];

        $allDates = [];

        if(!is_null($ahamClass))
        {
            foreach($ahamClass->timings as $index => $classTiming)
            {
                if(Input::get('restart') != 'restart')
                {
                    $allDates[] = $classTiming->date->format('d-m-Y');  
                } 
            }
        }

        for ($i=0; $i < $max_days; $i++) 
        { 
            $allDates[] = Carbon::createFromTimestamp(strtotime($start_date))->addDays($i)->format('d-m-Y');  
        }

        // dd($allDates);

        $allDates = array_unique($allDates);

        $allDates = array_values($allDates);

        usort($allDates, "date_sort");

        // for ($i=0; $i < $max_days; $i++) 
		foreach ($allDates as $idate => $allDate) 
		{ 
            // $date = Carbon::createFromTimestamp(strtotime($start_date))->addDays($i);

            $i = $idate;

			$date = Carbon::createFromTimestamp(strtotime($allDate));

			// var_dump($date->format('d-M-Y'));

	    	$dbTimings = ClassTiming::where('date',$date)
					    				->where('location_id',$location)
					    				->where('classroom_id',$classroom)
                                        ->whereNotIn('status',['cancelled'])
					    				->get();

            $selectedTimings = [];

            if(!is_null($ahamClass))
            {
                $ocTimings = ClassTiming::where('date',$date)
                                        ->where('location_id',$location)
                                        ->where('classroom_id',$classroom)
                                        ->where('class_id',$ahamClass->id)
                                        ->whereNotIn('status',['cancelled'])
                                        ->get();

                foreach($ocTimings as $ocTiming)
                {
                    $rangeTimings = range(strtotime($ocTiming->start_time),strtotime($ocTiming->end_time),15*60);
                    array_pop($rangeTimings);

                    $selectedTimings = array_merge($selectedTimings,$rangeTimings);
                }
            }

            // if($classroom == 5)
            // {
            //     dd($selectedTimings);
            // }
            

			$occupiedTimings = [];

			foreach($dbTimings as $dbTiming)
			{
				$rangeTimings = range(strtotime($dbTiming->start_time),strtotime($dbTiming->end_time),15*60);
				array_pop($rangeTimings);

				$occupiedTimings = array_merge($occupiedTimings,$rangeTimings);
			}

			$range = range(strtotime("00:00"),strtotime("23:45"),15*60);

	        foreach($range as $index => $time)
	        {
	            if($time >= strtotime("05:45") && $time <= strtotime("21:00"))
	            {
	                $timing = [];
	                $timing['id'] = $index+1;
	                $timing['time'] = date("H:i",$time);
	                $timing['occupied'] = false;
	                if(in_array($time, $occupiedTimings))
	                {
	                    $timing['occupied'] = true;
	                }
                    if(in_array($time, $selectedTimings))
                    {
                        $timing['occupied'] = false;
                        // $timing['selected'] = true;
                    }
	                $timing['available'] = !$timing['occupied'];
	                $timing['selectable'] = false;
	                $timings[$timing['id']] = $timing;
	            }
	        }

	        $a = [];
	        $a['name'] = $date->format('d-m-Y');
	        $a['id'] = $i+1;
	        $a['timings'] = $timings;

	        $availability[$a['id']] = $a;

		}



        return $availability;
    }

    public function getLocationAvailability()
    {
    	$date = Carbon::createFromTimestamp(strtotime(request()->get('start_date')));
    	$rule_id = request()->get('rule_id');
    	$location_id = request()->get('location_id');
    	$max_days = request()->get('max_days');

    	$location = Location::find($location_id);

        $otimings = [];

    	$range = range(strtotime("00:00"),strtotime("23:45"),15*60);

        foreach($range as $index => $time)
        {
            if($time >= strtotime("05:45") && $time <= strtotime("21:00"))
            {
                $timing = [];
                $timing['id'] = $index+1;
                $timing['time'] = date("H:i",$time);
                $timing['from_time'] = date("H:i",$time);
                $timing['to_time'] = date("H:i",$time+15*60);
                $otimings[$timing['id']] = $timing;
            }
        }

    	$classrooms = $location->classrooms()->get();

    	$schedule_data = [];

    	foreach($classrooms as $classroom)
    	{
    		$schedule = [];
    		$schedule['availability'] = 
    		$this->classroomAvailability(request()->get('start_date'),$location_id,$classroom->id,$max_days);
    		$schedule['classroom'] = $classroom;
    		$schedule_data[$classroom->id] = $schedule;
    	}

        $units = [];
        $units['rule'] = $rule_id;
        $units['max_days'] = $max_days;

    	return [
    		'timings' => $otimings,
    		'schedule_data' => $schedule_data,
    		'units_meta' => $units,
            'classrooms' => $classrooms->pluck('name','id')->toArray()
    	];
    }

    public function getClassTimings($id)
    {
        $ahamClass = AhamClass::find($id);

        $rule_id = $ahamClass->schedulingRule->division;
        $location_id = $ahamClass->location_id;

        if(Input::has('start_date'))
        {
            $date = Carbon::createFromTimestamp(strtotime(Input::get('start_date')))->format('d-m-Y');
        }
        else
        {
            $date = $ahamClass->start_date->format('d-m-Y');
        }
        
        $max_days = $ahamClass->maximum_days;

        if(request()->has('max_days'))
        {
            $max_days = request()->get('max_days');
        }

        $location = Location::find($location_id);

        $otimings = [];

        $range = range(strtotime("00:00"),strtotime("23:45"),15*60);

        foreach($range as $index => $time)
        {
            if($time >= strtotime("05:45") && $time <= strtotime("21:00"))
            {
                $timing = [];
                $timing['id'] = $index+1;
                $timing['time'] = date("H:i:s",$time);
                $timing['from_time'] = date("H:i:s",$time);
                $timing['to_time'] = date("H:i:s",$time);
                $otimings[$timing['time']] = $timing;
            }
        }

        $classrooms = $location->classrooms()->get();

        $schedule_data = [];

        foreach($classrooms as $classroom)
        {
            $schedule = [];
            $schedule['availability'] = 
            $this->classroomAvailability($date,$location_id,$classroom->id,$max_days,$ahamClass);
            $schedule['classroom'] = $classroom;
            $schedule_data[$classroom->id] = $schedule;
        }

        $units = [];
        $units['rule'] = $rule_id;
        $units['max_days'] = $max_days;

        $availability = [];
        $timings = [];

        $allDates = [];

        foreach($ahamClass->timings as $index => $classTiming)
        {
            if(Input::get('restart') != 'restart')
            {
                $allDates[] = $classTiming->date->format('d-m-Y');
            }
               
        }

        for ($i=0; $i < $max_days; $i++) 
        { 
            $allDates[] = Carbon::createFromTimestamp(strtotime($date))->addDays($i)->format('d-m-Y');   
        }

        $allDates = array_unique($allDates);

        $allDates = array_values($allDates);

        usort($allDates, "date_sort");

        foreach($allDates as $index => $allDate)
        {
            $ndate = Carbon::createFromTimestamp(strtotime($allDate));

            $a = [];
            $a['name'] = $ndate->format('d-m-Y');
            $a['id'] = $index+1;

            $availability[$a['name']] = $a;
        }

        // return $availability;

        $units_oc = [];

        if(Input::get('restart') != 'restart')
        {
            foreach($ahamClass->timings as $index => $classTiming)
            {
                $unit = [];
                $unit['unit_id'] = $classTiming->unit_id; 
                $unit['classroom'] = $classTiming->classroom_id;
                $unit['time_start_format'] = $classTiming->start_time;
                $unit['time_start'] = $otimings[$classTiming->start_time]['id'];
                $unit['time_end_format'] = $classTiming->end_time;
                $unit['time_end'] = $otimings[$classTiming->end_time]['id']-1;
                $unit['day'] =  $availability[$classTiming->date->format('d-m-Y')]['id'];
                $unit['day_name'] = $classTiming->date->format('d-m-Y');


                $units_oc[$classTiming->unit_id] = $unit;
            }

        }

        $otimings = [];

        $range = range(strtotime("00:00"),strtotime("23:45"),15*60);

        foreach($range as $index => $time)
        {
            if($time >= strtotime("05:45") && $time <= strtotime("21:00"))
            {
                $timing = [];
                $timing['id'] = $index+1;
                $timing['time'] = date("H:i",$time);
                $timing['from_time'] = date("H:i",$time);
                $timing['to_time'] = date("H:i",$time+15*60);
                $otimings[$timing['id']] = $timing;
            }
        }

        // return $schedule_data;

        return [
            'timings' => $otimings,
            'schedule_data' => $schedule_data,
            'units_meta' => $units,
            'units_oc' => $units_oc,
            'classrooms' => $classrooms->pluck('name','id')->toArray()
        ];

    }

}
