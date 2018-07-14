<?php

use Illuminate\Database\Seeder;

use Aham\Models\SQL\SchedulingRule;

class SchedulingRulesSeeder extends Seeder
{
    public function run()
    {
        DB::table('scheduling_rules')->truncate();

        $units = [
            4 => ['4','3-1','1-3','2-2','2-1-1','1-2-1','1-1-2','1-1-1-1'],
            3 => ['3','2-1','1-2','1-1-1'],
            2 => ['2','1-1'],
            1 => ['1']
        ];

        foreach($units as $index => $unit)
        {
            foreach($unit as $division)
            {
                
                $parts = explode('-', $division);

                $days = count($parts);

                var_dump($days);

                $str = [];

                if($days == 1)
                {
                    $str[] = $index.' '.str_plural('unit',intval($index)).' in one day';
                }
                else
                {
                    foreach($parts as $day => $unit_count)
                    {
                        $str[] = $unit_count.' '.str_plural('unit',intval($unit_count)).' on '.ordinalSuffix($day+1).' day';
                        
                    }
                }

                $rule = SchedulingRule::firstOrCreate([
                    'no_of_units' => $index,
                    'division' => $division,
                ]);

                $rule->days = $days;

                $rule->description = implode(', ', $str);

                $rule->save();

            }
        }

    }
}
