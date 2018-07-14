<?php

namespace Aham\Listeners\Teacher;

use Aham\Events\ClassScheduled;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class NotifyThatClassScheduled implements ShouldQueue
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
     * @param  ClassScheduled  $event
     * @return void
     */
    public function handle(ClassScheduled $event)
    {
        $class = $event->class;

        dispatch(new \Aham\Jobs\Teacher\SendTeacherMailThatClassIsScheduled($class));
    }
}
