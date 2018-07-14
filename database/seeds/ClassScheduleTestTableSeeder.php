<?php

use Illuminate\Database\Seeder;

use Aham\Models\SQL\DayType;
use Aham\Models\SQL\Slot;

use Aham\Models\SQL\Location;
use Aham\Models\SQL\City;
use Aham\Models\SQL\Classroom;
use Aham\Models\SQL\ClassroomSlot;

use Aham\Models\SQL\Topic;
use Aham\Models\SQL\AhamClass;
use Aham\Models\SQL\ClassTiming;
use Aham\Models\SQL\User;

use Aham\Interactions\ClassSchedule;

class ClassScheduleTestTableSeeder extends Seeder
{
    public function run()
    {
        $faker = Faker\Factory::create();

        $location = Location::orderBy(DB::raw('RAND()'))
                            ->first();

        DB::table('classes')->truncate();
        DB::table('class_timings')->truncate();
        DB::table('student_enrollments')->truncate();
        DB::table('class_invitations')->truncate();

        //Pick a Topic and create a Class

        $topic = Topic::topic()->has('units')
                    ->orderBy(DB::raw('RAND()'))
                    ->first();

        /** CREATE CLASSES **/

        for ($i=0; $i < 7; $i++) { 
            $ahamClass = AhamClass::create([
                    'topic_id' => $topic->id,
                    'location_id' => $location->id,
                    'creator_id' => User::orderBy(DB::raw('RAND()'))->first()->id
            ]);
        }

        /** SCHEDULING **/

        $classes = AhamClass::all();

        foreach($classes as $ahamClass)
        {
            $date = '17-05-2016';

            foreach($ahamClass->topic->units as $unit)
            {

                $availableSlots = ClassSchedule::getAvailableSlots($date,$ahamClass->location);

                if(count($availableSlots))
                {

                    $slot = array_rand($availableSlots);

                    ClassTiming::create([
                        'class_id' => $ahamClass->id,
                        'slot_id' => $slot,
                        'unit_id' => $unit->id,
                        'date' => Carbon::createFromTimestamp(strtotime($date)),
                        'location_id' => $ahamClass->location->id
                    ]);
                    
                }
                else
                {
                    var_dump('Sorry No Slots Available');
                }
            }

        }

    }

    public function createMasterData()
    {


        //Create Day Type
        DB::table('day_types')->truncate();

        $day_types = ['Weekday','Weekend','Holiday Weekday'];

        foreach($day_types as $day_type)
        {
            DayType::create(['name' => $day_type]);
        }

        //Create Slots
        DB::table('slots')->truncate();

        $predetermined_slots = 
                [
                    '08:00',
                    '08:15',
                    '08:30',
                    '08:45',
                    '09:00',
                    '09:15',
                    '09:30',
                    '09:45',
                    '10:00',
                    '10:15',
                    '10:30',
                    '10:45',
                    '11:00',
                    '11:15',
                    '11:30',
                    '11:45',
                    '12:00',
                    '12:15',
                    '12:30',
                    '12:45',
                    '13:00',
                    '13:15',
                    '13:30',
                    '13:45',
                    '14:00',
                    '14:15',
                    '14:30',
                    '14:45',
                    '15:00',
                    '15:15',
                    '15:30',
                    '15:45',
                    '16:00',
                    '16:15',
                    '16:30',
                    '16:45',
                    '17:00',
                    '17:15',
                    '17:30',
                    '17:45',
                    '18:00',
                    '18:15',
                    '18:30',
                    '18:45',
                    '19:00',
                    '19:15',
                    '19:30',
                    '19:45',
                    '20:00',
                ];

        for ($i=0; $i < 100 ; $i++) { 

            $point = $predetermined_slots[array_rand($predetermined_slots)];

            $point = strtotime($point);

            $start_time = Carbon::createFromTimestamp($point);

            $end_time = clone $start_time;

            $end_time->addHours(2);

            $day_type_id = DayType::orderBy(DB::raw('RAND()'))->first()->id;

            $data = [
                        'start_time' => $start_time,
                        'end_time' => $end_time,
                        'day_type_id' => $day_type_id,
                    ];

            $rules = [
                'start_time' => 'required|unique:slots,start_time,NULL,id,day_type_id,'.$day_type_id,
            ];

            $v = Validator::make($data, $rules);

            if ($v->fails()) {
                // var_dump($rules);
            }
            else
            {
                Slot::create($data);
            }

        }

        //Create Location

        DB::table('locations')->truncate();
        DB::table('location_calendar')->truncate();
        DB::table('classrooms')->truncate();
        DB::table('classroom_slots')->truncate();

        $location = Location::create([
            'name' => 'Lanco Hills',
            'city_id' => City::orderBy(DB::raw('RAND()'))->first()->id
        ]);

        Classroom::create([
            'name' => 'Classroom 1',
            'location_id' => $location->id,
            'size' => 10
        ]);

        Classroom::create([
            'name' => 'Classroom 2',
            'location_id' => $location->id,
            'size' => 20
        ]);

        Classroom::create([
            'name' => 'Classroom 3',
            'location_id' => $location->id,
            'size' => 30
        ]);

        $classrooms = Classroom::all();

        foreach($classrooms as $index => $classroom)
        {
            var_dump('Classroom: '.$classroom->name);

            // Pick a DayType

            $dayTypes = DayType::all();

            foreach($dayTypes as $dayType)
            {
                // Add Slots for this Classroom

                $counter = 15;

                var_dump('Counter: '.$counter);
                var_dump('DayType: '.$dayType->name);

                for ($i=0; $i < $counter; $i++) { 

                    var_dump('$i: '.$i);
                   
                    $slot = Slot::where('day_type_id',$dayType->id)
                                ->orderBy(DB::raw('RAND()'))
                                ->first();

                    var_dump('Slot: '.$slot->id);

                    $data = [

                        'classroom_id' => $classroom->id,
                        'slot_id' => $slot->id,
                        'day_type_id' => $dayType->id,
                        'start_time' => $slot->start_time,
                        'end_time' => $slot->end_time,
                    ];

                    $exists = ClassroomSlot::where('day_type_id',$dayType->id)
                        ->where('classroom_id',$classroom->id)
                        ->where(function ($query) use($start_time,$end_time,$slot) {
                            $query->whereBetween('start_time', [$slot->start_time,$slot->end_time])
                                  ->orWhereBetween('end_time', [$slot->start_time,$slot->end_time]);
                        })
                        ->first();

                    if(!$exists)
                    {
                        ClassroomSlot::firstOrCreate($data);
                    }
                    
                }
            }
        }  
    }
}
