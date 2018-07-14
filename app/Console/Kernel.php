<?php

namespace Aham\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        \Aham\Console\Commands\Inspire::class,
        \Aham\Console\Commands\Install::class,
        \Aham\Console\Commands\SyncNeo4J::class,
        \Aham\Console\Commands\ExploreTree::class,
        \Aham\Console\Commands\ExpireCoupons::class,
        \Aham\Console\Commands\SyncTopicsLookup::class,
        \Aham\Console\Commands\MoveToInSession::class,
        \Aham\Console\Commands\CancelClasses::class,
        \Aham\Console\Commands\ScheduleClasses::class,
        \Aham\Console\Commands\RemoveExpiredOTPs::class,
        \Aham\Console\Commands\TeacherEligibleSubjects::class,
        \Aham\Console\Commands\GenerateInvoiceNos::class,
        \Aham\Console\Commands\SendRoboticsPromotionalEmail::class,
        \Aham\Console\Commands\FixClassTimings::class,
        \Aham\Console\Commands\FixClassUnits::class,
        \Aham\Console\Commands\ClassNotifications::class,
        \Aham\Console\Commands\StudentReconcile::class,
        \Aham\Console\Commands\SingleStudentReconcile::class,
        \Aham\Console\Commands\FixSelectedTimings::class,
        \Aham\Console\Commands\MarkAsCompleted::class,
        \Aham\Console\Commands\TestQueues::class,
        \Aham\Console\Commands\SyncStudentEnrollmentsUnits::class,
        \Aham\Console\Commands\SyncCreditsEngine::class,
        \Aham\Console\Commands\SyncToNeo4J::class,
        \Aham\Console\Commands\ParseIndusStudents::class,
        \Aham\Console\Commands\ResetBuckets::class,
        \Aham\Console\Commands\ProcessRepeatClasses::class,
        \Aham\Console\Commands\CreditsUsedDate::class,
        \Aham\Console\Commands\CancelUselessClasses::class,
        \Aham\Console\Commands\ClassesNullDates::class,
        \Aham\Console\Commands\MoveFeedbackClassesStatus::class,
        \Aham\Console\Commands\SyncStudentEnrollmentsCredits::class,
        \Aham\Console\Commands\SyncStudentEnrolmentsUnitsNew::class,
        \Aham\Console\Commands\FixClasseNullNameAndMorphToTopic::class,
        \Aham\Console\Commands\FixUnitLevelEnrollment::class,
        \Aham\Console\Commands\EffectiveBucketPrices::class,
    ];

    /**
     * Define the application's command schedule.
     *
     * @param \Illuminate\Console\Scheduling\Schedule $schedule
     */
    protected function schedule(Schedule $schedule)
    {
        // $schedule->command('aham:cancel_classes')
        //          ->everyMinute();

        // $schedule->command('aham:move_to_in_session')
        //          ->everyMinute();

        // $schedule->command('aham:schedule_classes')
        //          ->everyMinute();

        $schedule->command('aham:remove_otps')
                 ->everyMinute();

        $schedule->command('aham:class_notifications')
                 ->everyFiveMinutes();

        $schedule->command('aham:teacher_eligible_subjects')
                 ->daily()->at('01:00');

        $schedule->command('backup:clean')->daily()->at('01:00');
        $schedule->command('backup:run --only-db')->hourly();
        
        $schedule->command('aham:process_repeat_classes')->everyFiveMinutes();
    }
}
