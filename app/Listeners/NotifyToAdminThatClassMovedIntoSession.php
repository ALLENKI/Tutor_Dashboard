<?php

namespace Aham\Listeners;

use Aham\Events\ClassInSession;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

use Aham\Helpers\HipchatHelper;

class NotifyToAdminThatClassMovedIntoSession implements ShouldQueue
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
     * @param  ClassInSession  $event
     * @return void
     */
    public function handle(ClassInSession $event)
    {
        $class = $event->class;

        $hipchat = new HipchatHelper();

        $message = view('notifications.class_moved_into_session',compact('class'))->render();

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
