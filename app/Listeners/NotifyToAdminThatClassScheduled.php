<?php

namespace Aham\Listeners;

use Aham\Events\ClassScheduled;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

use Aham\Helpers\HipchatHelper;

class NotifyToAdminThatClassScheduled implements ShouldQueue
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

        $hipchat = new HipchatHelper();

        $message = view('notifications.class_scheduled',compact('class'))->render();

        $hipchat->sendMessage('Aham',
        [
            'message' => $message,
            'id' => 'Aham',
            'from' => env('APP_URL'),
            'notify' => true,
            'color' => 'green'
        ]);
    }
}
