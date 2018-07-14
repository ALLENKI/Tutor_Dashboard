<?php

namespace Aham\Listeners\Student;

use Aham\Events\Student\StudentCancelledEnrollment as StudentCancelledEnrollmentEvent;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class StudentCancelledEnrollment implements ShouldQueue
{
    use InteractsWithQueue;

    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  StudentCancelledEnrollment  $event
     * @return void
     */
    public function handle(StudentCancelledEnrollmentEvent $event)
    {
        $enrollment = $event->enrollment;

        dispatch(new \Aham\Jobs\SendStudentCancelledEnrollmentMail($enrollment));

    }
}
