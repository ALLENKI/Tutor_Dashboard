<?php

namespace Aham\Listeners\Student;

use Aham\Events\Student\Activated;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class StudentIsActivated implements ShouldQueue
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
     * @param  Activated  $event
     * @return void
     */
    public function handle(Activated $event)
    {
        $student = $event->student;

        dispatch(new \Aham\Jobs\SendStudentActivatedMail($student));

    }
}
