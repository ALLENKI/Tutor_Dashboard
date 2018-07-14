<?php

use Illuminate\Database\Seeder;
use Aham\Models\SQL\Course;
use Aham\Models\SQL\topic;

class CourseTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        
    $faker = Faker\Factory::create();
    
        //$topics = Topic::where('type','Topic')->take(20)->get();

        $topics = [
                    "One Dimensional Motion (Level 1 & Level 2)",
                    "Two Dimensional Motion (Level 2)",
                    "Forces & Newton’s Laws Of Motion (Level 2)",
                    "Work, Energy & Power (Level 2)",
                    " Collisions & Momentum (Level 2)",
                    "Introduction To Physics",
                    "Classification, Properties and Phases Of Matter",
                    "Writing Effective Emails",
                    "Presentation Skills",
                    "The Art Of Public Speaking",
                    "Fundamentals of Essay Writing",
                    "Interviewing Skills",
                    "Linked List",
                    "Stack",
                    "Queue",
                    "Binary Tree",
                    "Binary Search Tree",
                    "Heap",
                    "Hashing",
                    "Graph",
                    "Advanced Data Structures"
        ];

        foreach ((range(1, 20)) as $index) {

            $course = course::create([
                'name' => $topics[$index],
                'type' => Topic::class
                
            ]);

        }
    
        foreach ((range(1, 20)) as $index) {

            DB::table('coursables')->insert([
                    'course_id' => rand(1, 20),
                    'coursable_id' => rand(1, 20),
                    // rand(0, 1) == 1 ? 'App\Topic' : 'App\Video'
                    'coursable_type' => Topic::class
                ]);    
        }

    }

}
