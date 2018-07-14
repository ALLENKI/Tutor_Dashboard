<?php

namespace Aham\Listeners;

use Aham\Events\TeacherAwarded;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

use Aham\Helpers\HipchatHelper;

class NotifyToAdminTeacherAwarded implements ShouldQueue
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
     * @param  TeacherAwarded  $event
     * @return void
     */
    public function handle(TeacherAwarded $event)
    {
        $invite = $event->invite;

        $hipchat = new HipchatHelper();

        $message = view('notifications.teacher_awarded',compact('invite'))->render();

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
