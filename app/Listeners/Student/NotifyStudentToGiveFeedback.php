<?php

namespace Aham\Listeners\Student;

use Aham\Events\ClassCompleted;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class NotifyStudentToGiveFeedback implements ShouldQueue
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
     * @param  ClassCompleted  $event
     * @return void
     */
    public function handle(ClassCompleted $event)
    {
        $class = $event->class;


        foreach($class->enrollments as $enrollment)
        {
            dispatch(new \Aham\Jobs\Student\SendStudentMailForFeedback($enrollment));
        }
    }
}
