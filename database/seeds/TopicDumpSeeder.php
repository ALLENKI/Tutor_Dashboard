<?php

use Illuminate\Database\Seeder;

use Aham\Models\SQL\Topic;
use Aham\Models\SQL\TopicPrerequisite;
use Aham\Models\SQL\Unit;
use Aham\Models\SQL\User;
use Aham\Models\SQL\SchedulingRule;
use Aham\Models\SQL\CloudinaryImage;

class TopicDumpSeeder extends Seeder
{
    public function run()
    {
        $user = User::find(1);

        Sentinel::login($user);

        DB::table('topics')->truncate();
        DB::table('units')->truncate();
        DB::table('topic_prerequisites')->truncate();
        DB::table('student_assessments')->truncate();
        DB::table('teacher_certification')->truncate();
        DB::table('classes')->truncate();
        DB::table('student_enrollments')->truncate();
        DB::table('class_invitations')->truncate();
        DB::table('class_timings')->truncate();
        DB::table('topics_lookup')->truncate();
        DB::table('scheduling_rules')->truncate();
        DB::table('cloudinary_images')->truncate();

        \Excel::load(storage_path('uploads/TopicsDump.xls'), function($reader) {

            $sheets = $reader->all();

            foreach($sheets as $sheet)
            {
                if($sheet->getTitle() == 'Topics')
                {
                    foreach($sheet as $item)
                    {
                        $topic = $item->toArray();

                        if(is_null($topic['parent_id']))
                        {
                            $topic['parent_id'] = 0;
                        }

                        if(is_null($topic['order']))
                        {
                            $topic['order'] = 0;
                        }

                        $topic['id'] = intval($topic['id']);

                        Topic::create($topic);
                    }
                }

                if($sheet->getTitle() == 'Units')
                {
                    foreach($sheet as $item)
                    {
                        $topic = $item->toArray();

                        if(is_null($topic['order']))
                        {
                            $topic['order'] = 0;
                        }

                        $topic['id'] = intval($topic['id']);

                        Unit::create($topic);
                    }
                }

                if($sheet->getTitle() == 'Pre-Requisites')
                {
                    foreach($sheet as $item)
                    {
                        $topic = $item->toArray();

                        TopicPrerequisite::create($topic);
                    }
                }

                if($sheet->getTitle() == 'Scheduling-Rules')
                {
                    foreach($sheet as $item)
                    {
                        $topic = $item->toArray();

                        SchedulingRule::create($topic);
                    }
                }

                if($sheet->getTitle() == 'Cloudinary-Images')
                {
                    foreach($sheet as $item)
                    {
                        $topic = $item->toArray();

                        if(is_null($topic['position']))
                        {
                            $topic['position'] = 0;
                        }

                        CloudinaryImage::create($topic);
                    }
                }

            }

        });

        \Artisan::call('aham:sync_topics');

    }
}
