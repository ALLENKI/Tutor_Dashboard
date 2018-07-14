<?php

namespace Aham\Jobs\Teacher;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

use Aham\Models\SQL\AhamClass;


use Aham\Managers\SuperMail;

class SendTeacherMailThatClassIsScheduled implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $class;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(AhamClass $class)
    {
        $this->class = $class;
    }


    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $class = $this->class;
        $user = $class->teacher->user;

        SuperMail::mail('emails_new.teacher.class_is_scheduled',['user' => $user,'class'=> $class],'Dear Tutor, '.$class->topic->name.' is Scheduled',
                [$user->email => $user->name]);
    }
}
