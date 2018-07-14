<?php

namespace Aham\Listeners;

use Aham\Events\TeacherRegistered;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

use Aham\Helpers\HipchatHelper;

class NotifyToAdminThatATeacherRegistered implements ShouldQueue
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
     * @param  TeacherRegistered  $event
     * @return void
     */
    public function handle(TeacherRegistered $event)
    {
        $user = $event->user;

        $hipchat = new HipchatHelper();

        $message = view('notifications.teacher_registered',compact('user'))->render();

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
