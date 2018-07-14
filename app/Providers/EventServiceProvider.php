<?php

namespace Aham\Providers;

use Illuminate\Support\Facades\Event;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        'Illuminate\Queue\Events\JobExceptionOccurred' => [
            // 'Aham\Listeners\JobExceptionListener',
        ],

        'Aham\Events\LogMyName' => [
            'Aham\Listeners\LogRajivName',
        ],

        'Aham\Events\TeacherRegistered' => [
            'Aham\Listeners\NotifyToAdminThatATeacherRegistered',
        ],

        'Aham\Events\StudentRegistered' => [
            'Aham\Listeners\NotifyToAdminThatAStudentRegistered',
        ],

        'Aham\Events\ClassInSession' => [
            'Aham\Listeners\NotifyToAdminThatClassMovedIntoSession',
        ],

        'Aham\Events\ClassScheduled' => [
            'Aham\Listeners\NotifyToAdminThatClassScheduled',
            'Aham\Listeners\Student\NotifyThatClassScheduled',
            'Aham\Listeners\Teacher\NotifyThatClassScheduled',
        ],

        'Aham\Events\ClassCompleted' => [
            'Aham\Listeners\Student\NotifyStudentToGiveFeedback',
        ],

        'Aham\Events\SystemCancelledClass' => [
            'Aham\Listeners\NotifyToAdminThatClassIsCancelled',
            'Aham\Listeners\Teacher\SystemCancelledClass',
            'Aham\Listeners\Student\SystemCancelledClass',
        ],

        'Aham\Events\AdminCancelledClass' => [
            'Aham\Listeners\NotifyToAdminThatClassIsCancelledManually',
            'Aham\Listeners\Teacher\AdminCancelledClass',
            'Aham\Listeners\Student\AdminCancelledClass',
        ],

        'Aham\Events\TeacherInvited' => [
            // 'Aham\Listeners\NotifyToAdminTeacherInvited',
            'Aham\Listeners\Teacher\TeacherIsInvited',
        ],

        'Aham\Events\TeacherAccepted' => [
            'Aham\Listeners\NotifyToAdminTeacherAccepted',
        ],

        'Aham\Events\TeacherAwarded' => [
            'Aham\Listeners\NotifyToAdminTeacherAwarded',
            'Aham\Listeners\Teacher\TeacherIsAwarded',
        ],

        'Aham\Events\Student\AddedCredits' => [
            'Aham\Listeners\Student\AddedCredits',
        ],

        'Aham\Events\Student\RefundedCredits' => [
            'Aham\Listeners\Student\RefundedCredits',
        ],

        'Aham\Events\Student\SubscribedToGoal' => [
            'Aham\Listeners\Student\SubscribedToGoal',
        ],

        'Aham\Events\Student\Activated' => [
            'Aham\Listeners\Student\StudentIsActivated',
        ],

        'Aham\Events\Student\EnrolledToClass' => [
            //Mention that credits are deducted, consider free class
            'Aham\Listeners\Student\EnrolledToClass',
        ],

        'Aham\Events\Student\StudentCancelledEnrollment' => [
            //Mention that credits are deducted, consider free class
            'Aham\Listeners\Student\StudentCancelledEnrollment',
        ],

        'Aham\Events\Teacher\Created' => [
            'Aham\Listeners\Admin\TeacherIsCreated',
        ],

        'Aham\Events\Teacher\Activated' => [
            'Aham\Listeners\Teacher\TeacherIsActivate',
        ],

        'Aham\Events\Teacher\Certified' => [
            'Aham\Listeners\Teacher\TeacherIsCertified',
            'Aham\Listeners\Admin\TeacherIsCertified',
        ],

        'Aham\Events\Teacher\GetFeedback' => [
            'Aham\Listeners\Teacher\NotifyTeacherToGiveFeedback',
        ],
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        parent::boot();
    }
}
