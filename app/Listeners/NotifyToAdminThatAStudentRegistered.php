<?php

namespace Aham\Listeners;

use Aham\Events\StudentRegistered;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class NotifyToAdminThatAStudentRegistered implements ShouldQueue
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
     * @param  StudentRegistered  $event
     * @return void
     */
    public function handle(StudentRegistered $event)
    {
        //
    }
}
