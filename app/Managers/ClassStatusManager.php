<?php namespace Aham\Managers;

use Carbon;

use Aham\Managers\CreditsManager;

use Aham\Models\SQL\AhamClass;

use Illuminate\Contracts\Bus\Dispatcher;

class ClassStatusManager
{
    public static function moveClassesToInSession()
    {
        $classes = AhamClass::where('status', 'scheduled')
                          ->where('start_date', '<=', Carbon::now())
                          ->get();

        foreach ($classes as $ahamClass) {
            $ahamClass->status = 'in_session';
            $ahamClass->save();

            event(new \Aham\Events\ClassInSession($ahamClass));
        }

        return true;
    }

    public static function scheduleClasses()
    {
        $classes = AhamClass::where('status', 'open_for_enrollment')
                          ->where('schedule_cutoff', '>', Carbon::now())
                          ->get();

        foreach ($classes as $ahamClass) {
            $enrollment_count = $ahamClass->enrollments->count();

            if ($enrollment_count >= $ahamClass->minimum_enrollment) {
                $ahamClass->status = 'scheduled';
                $ahamClass->save();

                event(new \Aham\Events\ClassScheduled($ahamClass));
            }
        }
    }

    public static function cancelClasses()
    {
        // Classes which are past schedule cutoff date

        $classes = AhamClass::where('status', 'open_for_enrollment')
                          ->where('schedule_cutoff', '<', Carbon::now())
                          ->get();

        foreach ($classes as $ahamClass) {
            $enrollment_count = $ahamClass->enrollments->count();
            // If min enrollment is met - do schedule
            if ($enrollment_count >= $ahamClass->minimum_enrollment) {
                $ahamClass->status = 'scheduled';
                $ahamClass->save();

                event(new \Aham\Events\ClassScheduled($ahamClass));
            } else {
                // If the minimum enrollment is not met, it means it should be cancelled

                static::cancelClass($ahamClass, 'Minimum enrollment not met, and it is past enrollment cutoff date');
            }
        }

        $classes = AhamClass::where('status', 'ready_for_enrollment')
                          ->where('schedule_cutoff', '<=', Carbon::now())
                          ->get();

        foreach ($classes as $ahamClass) {
            static::cancelClass($ahamClass, 'Class is still in not opened for enrollment, it is past start date');
        }

        return true;
    }

    public static function cancelClass($ahamClass, $reason)
    {
        if ($ahamClass->auto_cancel) {
            $ahamClass->status = 'cancelled';
            $ahamClass->cancellation_reason = $reason;
            $ahamClass->cancelled_at = Carbon::now();
            $ahamClass->save();

            static::giveBackCredits($ahamClass);

            event(new \Aham\Events\SystemCancelledClass($ahamClass));
        }

        return true;
    }

    public static function giveBackCredits($ahamClass)
    {
        if (!$ahamClass->free) {
            foreach ($ahamClass->enrollments as $enrollment) {

                $manager = new EnrollmentManager($enrollment->ahamClass, $enrollment->student);

                $manager->cancelEnrollment('cancelled_by_admin');

                // \Log::info('Cancel Enrollment of Student:'.$enrollment->id);

                // CreditsManager::addClassCancelledCredits($enrollment);
            }
        }
    }
}
