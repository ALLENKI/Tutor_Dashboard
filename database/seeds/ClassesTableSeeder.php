<?php

use Illuminate\Database\Seeder;

use Aham\Models\SQL\DayType;
use Aham\Models\SQL\Slot;
use Aham\Models\SQL\Topic;
use Aham\Models\SQL\Location;
use Aham\Models\SQL\AhamClass;
use Aham\Models\SQL\SchedulingRule;
use Aham\Models\SQL\User;
use Aham\Models\SQL\ClassTiming;

use Aham\Interactions\ClassSchedule;

class ClassesTableSeeder extends Seeder
{
    public function run()
    {
        DB::table('classes')->truncate();
        DB::table('class_timings')->truncate();
        DB::table('class_invitations')->truncate();
        DB::table('student_enrollments')->truncate();

    	$this->createClasses();
    	
    	$classes = AhamClass::get();

    	foreach($classes as $class)
    	{
			$this->scheduleClass($class);
    	}
    	
    }

    public function createClasses()
    {
    	for ($i=0; $i < 200; $i++) { 

	    	// Pick a Topic and a Location

	    	$topic = Topic::topic()->has('units')
                            ->orderBy(DB::raw('RAND()'))
                            ->first();

	    	$location = Location::orderBy(DB::raw('RAND()'))->first();
	    	$user = User::orderBy(DB::raw('RAND()'))->first();
	    	$rule = SchedulingRule::where('no_of_units',$topic->units->count())
	    					->orderBy(DB::raw('RAND()'))->first();

            var_dump($topic->units->count());

	    	// Create a Class

	    	$class = [
	    		'topic_id' => $topic->id,
	    		'minimum_enrollment' => $topic->minimum_enrollment,
	    		'maximum_enrollment' => $topic->maximum_enrollment,
	    		'maximum_days' => 7,
	    		'scheduling_rule_id' => $rule->id,
	    		'location_id' => $location->id,
	    		'creator_id' => $user->id,
	    	];

	    	$class = AhamClass::create($class);
    	}
    }

    public function scheduleClass($class)
    {
    	$faker = Faker\Factory::create();

    	var_dump('Scheduling '.$class->code);

    	// Schedule Each Unit

    	$units = $class->topic->units;

    	foreach($units as $unit)
    	{
    		var_dump('Schedule '.$unit->name);

    		$range = ClassSchedule::getDateRange($class, $unit->id);

    		if(is_null($range['startDate']))
    		{
    			$date = $faker->dateTimeBetween(Carbon::now()->format('d-m-Y'),'01-12-2016',date_default_timezone_get());

    		}
    		else
    		{
    			$date = $faker->dateTimeBetween($range['startDate']->format('d-m-Y'),$range['endDate']->format('d-m-Y'),date_default_timezone_get());
    		}

    		$status = $this->createClassTiming($class,$unit,$date);

    		if(!$status)
    		{
    			break;
    		}

    	}

        $class->status = 'created';

        $class->save();
    }

    public function createClassTiming($class, $unit, $date)
    {
		$date = strtotime($date->format('d-m-Y'));

        $firstUnit = $class->topic->units->first();

		$date = Carbon::createFromTimestamp($date);

		$slot = $this->getRandomSlot($class,$unit,$date);

		if($slot <= 0)
		{
			return false;
		}

        $timing = ClassTiming::firstOrCreate([
            'class_id' => $class->id,
            'unit_id' => $unit->id,
        ]);

        $timing->date = $date;
        $timing->slot_id = $slot;
        $timing->location_id = $class->location->id;

        $dbSlot = Slot::find($slot);
        
        $timing->start_time = $dbSlot->start_time;
        $timing->end_time = $dbSlot->end_time;

        $timing->save();

        if($firstUnit->id == $unit->id)
        {
            $class->start_date = $date;
            $class->save();
        }

        return true;
    }

    public function getRandomSlot($class, $unit, $date)
    {
    	$slots = ClassSchedule::prepareAndGetAvailableSlots($class->id, $unit->id, $date->format('Y-m-d'));

        $slot = key($slots);

    	// $slot = array_rand($slots);

        $slot = trim($slot,'"');
        $slot = trim($slot,"'");

		var_dump($slot);

        return $slot;
    }
}

