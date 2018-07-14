<?php

use Illuminate\Database\Seeder;

use Aham\Models\SQL\Country;
use Aham\Models\SQL\State;
use Aham\Models\SQL\City;
use Aham\Models\SQL\Locality;
use Aham\Models\SQL\Location;
use Aham\Models\SQL\DayType;
use Aham\Models\SQL\LocationCalendar;
use Aham\Models\SQL\Classroom;
use Aham\Models\SQL\ClassroomSlot;
use Aham\Models\SQL\Slot;

class LocationsTableSeeder extends Seeder
{
    public function run()
    {
        DB::table('locations')->truncate();
        DB::table('location_calendar')->truncate();
        DB::table('classrooms')->truncate();
        DB::table('classroom_slots')->truncate();

        $faker = Faker\Factory::create();

        $city =  City::orderBy(DB::raw('RAND()'))->first()->id;
        $locality =  Locality::orderBy(DB::raw('RAND()'))->first()->id;

        $location =  Location::create([
                'name' => 'Aham @ Lanco Hills',
                'city_id' => $city,
                'locality_id' => $locality,
                'street_address' => $faker->streetAddress,
                'landmark' => $faker->streetName,
                'pincode' => $faker->postcode,
            ]);

        $this->addLocationCalendar($location);
        $this->createClassrooms($location);

    }

    public function createClassrooms($location)
    {
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
            $dayTypes = DayType::all();

            foreach($dayTypes as $dayType)
            {
                $counter = 15;

                for ($i=0; $i < $counter; $i++) { 

                    $slot = Slot::where('day_type_id',$dayType->id)
                                ->orderBy(DB::raw('RAND()'))
                                ->first();

                    $data = [

                        'classroom_id' => $classroom->id,
                        'slot_id' => $slot->id,
                        'day_type_id' => $dayType->id,
                        'start_time' => $slot->start_time,
                        'end_time' => $slot->end_time,
                    ];
                    
                    $exists = ClassroomSlot::where('day_type_id',$dayType->id)
                        ->where('classroom_id',$classroom->id)
                        ->where(function ($query) use($slot) {
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

        return true;
    }

    public function addLocationCalendar($location)
    {
        $data = [

            'location_id' => $location->id,
            'day_type_id' => DayType::orderBy(DB::raw('RAND()'))->first()->id,
            'from_date' => Carbon::createFromTimestamp(strtotime('01-01-2016')),
            'to_date' => Carbon::createFromTimestamp(strtotime('31-03-2016')),

        ];

        LocationCalendar::create($data);

        $data = [

            'location_id' => $location->id,
            'day_type_id' => DayType::orderBy(DB::raw('RAND()'))->first()->id,
            'from_date' => Carbon::createFromTimestamp(strtotime('01-04-2016')),
            'to_date' => Carbon::createFromTimestamp(strtotime('31-08-2016')),

        ];

        LocationCalendar::create($data);

        $data = [

            'location_id' => $location->id,
            'day_type_id' => DayType::orderBy(DB::raw('RAND()'))->first()->id,
            'from_date' => Carbon::createFromTimestamp(strtotime('01-09-2016')),
            'to_date' => Carbon::createFromTimestamp(strtotime('31-12-2016')),

        ];

        LocationCalendar::create($data);

        return true;
    }
}
