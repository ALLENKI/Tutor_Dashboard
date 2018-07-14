<?php

namespace Aham\Listeners\Teacher;

use Aham\Events\TeacherAwarded;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class TeacherIsAwarded implements ShouldQueue
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

        dispatch(new \Aham\Jobs\SendTeacherAwardedMail($invite));
        dispatch(new \Aham\Jobs\Teacher\SendAwardedPushNotification($invite));

        
    }
}
