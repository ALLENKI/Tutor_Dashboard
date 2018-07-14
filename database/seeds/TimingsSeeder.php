<?php

use Illuminate\Database\Seeder;

use Aham\Models\SQL\DayType;
use Aham\Models\SQL\Slot;

class TimingsSeeder extends Seeder
{
    public function run()
    {
        DB::table('day_types')->truncate();
        DB::table('location_calendar')->truncate();

        $day_types = ['Weekday','Weekend','Holiday Weekday'];

        foreach($day_types as $day_type)
        {
            DayType::create(['name' => $day_type]);
        }

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
    }
}
