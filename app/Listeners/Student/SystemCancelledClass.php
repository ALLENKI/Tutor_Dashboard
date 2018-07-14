<?php

namespace Aham\Listeners\Student;

use Aham\Events\SystemCancelledClass as SystemCancelledClassEvent;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class SystemCancelledClass implements ShouldQueue
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
     * @param  SystemCancelledClass  $event
     * @return void
     */
    public function handle(SystemCancelledClassEvent $event)
    {
        $class = $event->class;

        foreach($class->allEnrollments as $enrollment)
        {
            dispatch(new \Aham\Jobs\Student\SendSystemCancelledClassMail($enrollment));
        }
    }
}
