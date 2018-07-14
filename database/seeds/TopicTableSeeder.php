<?php

use Illuminate\Database\Seeder;

use Aham\Models\SQL\Topic;
use Aham\Models\SQL\Unit;

class TopicTableSeeder extends Seeder
{
    public function run()
    {
        DB::table('topics')->truncate();
        DB::table('topic_prerequisites')->truncate();
        DB::table('units')->truncate();
        DB::table('student_assessments')->truncate();
        DB::table('teacher_certification')->truncate();
        DB::table('classes')->truncate();
        DB::table('class_timings')->truncate();

        $faker = Faker\Factory::create();

        $levels = ['1','2','3'];

        for ($i=0; $i < 3; $i++) 
        { 
        	$topic = Topic::create([
                    'name' => 'Category',
                    'type' => 'category',
                    'parent_id' => 0
                ]);

            $level = $levels[array_rand($levels)];
            $topic->level = $level;
            $topic->name = $topic->name.' '.$topic->id;
            $topic->save();

        }

        for ($i=0; $i < 9; $i++) 
        { 
            $topic = Topic::create([
                    'name' => 'Subject',
                    'type' => 'subject',
                    'parent_id' => Topic::where('type','category')->orderBy(DB::raw('RAND()'))->first()->id
                ]);

            $level = $levels[array_rand($levels)];
            $topic->level = $level;
            $topic->name = $topic->name.' '.$topic->id;
            $topic->save();
        }

        for ($i=0; $i < 27; $i++) 
        { 
            $topic = Topic::create([
                    'name' => 'Sub Category',
                    'type' => 'sub-category',
                    'parent_id' => Topic::where('type','subject')->orderBy(DB::raw('RAND()'))->first()->id
                ]);

            $level = $levels[array_rand($levels)];
            $topic->level = $level;
            $topic->name = $topic->name.' '.$topic->id;
            $topic->save();
        }

        

        for ($i=0; $i < 108; $i++) 
        { 
            $topic = Topic::create([
                    'name' => 'Topic',
                    'type' => 'topic',
                    'parent_id' => Topic::where('type','sub-category')->orderBy(DB::raw('RAND()'))->first()->id
                ]);

            $level = $levels[array_rand($levels)];
            $topic->level = $level;
            $topic->name = $topic->name.' '.$topic->id;
            $topic->minimum_enrollment = 10;
            $topic->maximum_enrollment = 20;
            $topic->description = $faker->paragraph(3);
            $topic->save();
        }

        $topics = Topic::topic()->get();

        $units = [1,2,3,4];

        foreach($topics as $topic)
        {
            $no_of_units = $units[array_rand($units)];

            for ($i=0; $i < $no_of_units; $i++) { 
                
                $unit = Unit::create([
                    'name' => 'Unit '.($i+1),
                    'description' => $faker->paragraph(3)
                ]);

                $topic->units()->save($unit);

            }
        }

    }
}
