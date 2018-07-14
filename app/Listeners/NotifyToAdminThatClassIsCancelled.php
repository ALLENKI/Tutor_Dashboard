<?php

namespace Aham\Listeners;

use Aham\Events\SystemCancelledClass;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

use Aham\Helpers\HipchatHelper;

class NotifyToAdminThatClassIsCancelled implements ShouldQueue
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
    public function handle(SystemCancelledClass $event)
    {
        $class = $event->class;

        $hipchat = new HipchatHelper();

        $message = view('notifications.class_cancelled',compact('class'))->render();

        $hipchat->sendMessage('Aham',
        [
            'message' => $message,
            'id' => 'Aham',
            'from' => env('APP_URL'),
            'notify' => true,
            'color' => 'red'
        ]);

    }
}
