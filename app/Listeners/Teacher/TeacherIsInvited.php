<?php

namespace Aham\Listeners\Teacher;

use Aham\Events\TeacherInvited;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

use Aham\Models\SQL\Notification;

class TeacherIsInvited implements ShouldQueue
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
     * @param  TeacherInvited  $event
     * @return void
     */
    public function handle(TeacherInvited $event)
    {
        $invite = $event->invite;

        $user = $invite->teacher->user;

        Notification::create([
            'user_id' => $user->id,
            'hash' => md5($user->email.'invite'.$invite->id),
            'note' => serialize([
                'title' => 'New Invitation',
                'body' => 'Invitation for:'.$invite->ahamClass->topic->name
            ]),
            'destination' => serialize([
                'type'=>'class_invitation',
                'invite_id' => $invite->id,
                'class_id' => $invite->ahamClass->id
            ]),
            'notify_on' => serialize([
                'push_android',
                'push_apple',
                'email',
            ]),
            'role' => 'tutor'
        ]);

        // $this->dispatch(new \Aham\Jobs\SendTeacherInvitedMail($invite));
        dispatch(new \Aham\Jobs\Teacher\SendInvitedPushNotification($invite));
        
    }
}
