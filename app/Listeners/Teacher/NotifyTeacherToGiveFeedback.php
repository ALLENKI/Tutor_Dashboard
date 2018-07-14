<?php

namespace Aham\Listeners\Teacher;

use Aham\Events\Teacher\GetFeedback;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class NotifyTeacherToGiveFeedback implements ShouldQueue
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
     * @param  GetFeedback  $event
     * @return void
     */
    public function handle(GetFeedback $event)
    {
        $class = $event->class;

        dispatch(new \Aham\Jobs\Teacher\SendTeacherMailForFeedback($class));
    }
}
