<?php

namespace Aham\Listeners\Student;

use Aham\Events\AdminCancelledClass as AdminCancelledClassEvent;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class AdminCancelledClass implements ShouldQueue
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
     * @param  AdminCancelledClass  $event
     * @return void
     */
    public function handle(AdminCancelledClassEvent $event)
    {
        $class = $event->class;

        foreach($class->allEnrollments as $enrollment)
        {
            dispatch(new \Aham\Jobs\Student\SendAdminCancelledClassMail($enrollment));
        }
    }
}
