<?php

namespace Aham\Jobs\student;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

use Aham\Models\SQL\StudentEnrollment;

use Aham\Managers\SuperMail;

class SendAdminCancelledClassMail implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $enrollment;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(StudentEnrollment $enrollment)
    {
        $this->enrollment = $enrollment;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $enrollment = $this->enrollment;
        $user = $enrollment->student->user;
        $class = $enrollment->ahamClass;

        SuperMail::mail('emails_new.student.admin_cancelled_class',['user' => $user,'class'=> $class],'Dear Learner, '.$class->topic->name.' Class is Cancelled',
                [$user->email => $user->name]);
    }
}
