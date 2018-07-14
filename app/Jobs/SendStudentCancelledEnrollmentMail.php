<?php

namespace Aham\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

use Aham\Models\SQL\StudentEnrollment;

use Aham\Managers\SuperMail;

class SendStudentCancelledEnrollmentMail implements ShouldQueue
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

        SuperMail::mail('emails_new.student.student_cancelled_enrollment',['user' => $user,'class'=> $class],'You have cancelled enrollment to '.$class->topic->name,
                [$user->email => $user->name]);
    }
}
