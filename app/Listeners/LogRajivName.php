<?php

namespace Aham\Listeners;

use Aham\Events\LogMyName;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class LogRajivName implements ShouldQueue
{
    use InteractsWithQueue;

    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
    }

    /**
     * Handle the event.
     *
     * @param  LogMyName  $event
     * @return void
     */
    public function handle(LogMyName $event)
    {
        $class = $event->class;
        \Log::info($class->topic_id);
    }
}
