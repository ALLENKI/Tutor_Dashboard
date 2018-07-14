<?php

namespace Aham\Listeners;

use Illuminate\Queue\Events\JobExceptionOccurred;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class JobExceptionListener implements ShouldQueue
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
     * @param  JobExceptionOccurred  $event
     * @return void
     */
    public function handle(JobExceptionOccurred $event)
    {
        app('sentry')->captureException($event->exception);
    }
}
