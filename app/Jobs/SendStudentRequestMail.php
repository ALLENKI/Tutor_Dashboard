<?php

namespace Aham\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

use Aham\Models\SQL\Student;
use Aham\Models\SQL\Topic;

use Aham\Managers\SuperMail;

class SendStudentRequestMail implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $student;
    public $topic;
    public $preferred_time;
    public $preferred_day;
    public $preferred_period;
    public $your_message;


    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(Student $student, Topic $topic, $preferred_time, $preferred_day, $preferred_period, $your_message)
    {
        $this->student = $student;
        $this->topic = $topic;
        $this->preferred_time = $preferred_time;
        $this->preferred_day = $preferred_day;
        $this->preferred_period = $preferred_period;
        $this->your_message = $your_message;

    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $user = $this->student->user;
        $topic = $this->topic;
        $preferred_time = $this->preferred_time;
        $preferred_day = $this->preferred_day;
        $preferred_period = $this->preferred_period;
        $your_message = $this->your_message;

        SuperMail::mail(
            'emails.student.request',
            [
                'user' => $user, 
                'topic' => $topic, 
                'preferred_time' => $preferred_time,
                'preferred_day' => $preferred_day,
                'preferred_period' => $preferred_period,
                'your_message' => $your_message,
            ], 
            $user->name.' is requesting a class on '.$topic->name,
            [
                'contactus@ahamlearning.com' => 'Aham ContactUs'
            ]
            );   //
    }
}
