<?php

namespace Aham\Listeners;

use Aham\Events\TeacherAccepted;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

use Aham\Helpers\HipchatHelper;

class NotifyToAdminTeacherAccepted implements ShouldQueue
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
     * @param  TeacherAccepted  $event
     * @return void
     */
    public function handle(TeacherAccepted $event)
    {
        $invite = $event->invite;

        $hipchat = new HipchatHelper();

        $message = view('notifications.teacher_accepted',compact('invite'))->render();

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
