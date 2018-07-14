<?php

namespace Aham\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

use Aham\Models\SQL\Teacher;
use Aham\Models\SQL\Topic;

class SendTeacherInterestedMail implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $teacher;
    public $topic;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(Teacher $teacher, Topic $topic)
    {
        $this->teacher = $teacher;
        $this->topic = $topic;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $user = $this->teacher->user;
        $topic = $this->topic;

        SuperMail::mail('emails.teacher.interested',['user' => $user, 'topic' => $topic], $user->name.' is Interested to teach '.$topic->name,
                ['contactus@ahamlearning.com' => 'Aham ContactUs']);   //
    }
}
