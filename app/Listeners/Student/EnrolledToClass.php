<?php

namespace Aham\Listeners\Student;

use Aham\Events\Student\EnrolledToClass as EnrolledToClassEvent;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class EnrolledToClass implements ShouldQueue
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
     * @param  EnrolledToClass  $event
     * @return void
     */
    public function handle(EnrolledToClassEvent $event)
    {
        $enrollment = $event->enrollment;

        dispatch(new \Aham\Jobs\SendStudentEnrolledToClassMail($enrollment));

    }
}
