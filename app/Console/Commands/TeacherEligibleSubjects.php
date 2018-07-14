<?php

namespace Aham\Console\Commands;

use Illuminate\Console\Command;

use Aham\Models\SQL\Teacher;

class TeacherEligibleSubjects extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'aham:teacher_eligible_subjects';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $teachers = Teacher::all();

        foreach($teachers as $teacher)
        {
            $data['categories'] = [];
            $data['subjects'] = [];
            $data['sub_categories'] = [];
            $data['topics'] = [];

            foreach($teacher->certifications as $certification)
            {
                $topic = $certification->topic;

                switch ($topic->type) {
                    case 'topic':
                        $data['categories'][] = $topic->parent->parent->parent->id;
                        $data['subjects'][] = $topic->parent->parent->name;
                        $data['sub_categories'][] = $topic->parent->id;
                        $data['topics'][] = $topic->id;
                        break;
                    
                    case 'sub-category':
                        $data['categories'][] = $topic->parent->parent->id;
                        $data['subjects'][] = $topic->parent->name;
                        $data['sub_categories'][] = $topic->id;
                        break;
                    
                    case 'subject':
                        $data['categories'][] = $topic->parent->id;
                        $data['subjects'][] = $topic->name;
                        break;

                    case 'category':
                        $data['categories'][] = $topic->id;
                        break;
                }

            }

            $teacher->eligible_subjects = implode(', ',array_unique($data['subjects']));
            $teacher->save();

        }
    }
}
