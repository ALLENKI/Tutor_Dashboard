<?php

namespace Aham\Helpers;

use Carbon;
use DB;
use Sentinel;

use Aham\Models\SQL\Teacher;
use Aham\Models\SQL\TeacherCertification;
use Aham\Models\SQL\ClassTiming;
use Aham\Models\SQL\StudentAssessment;
use Aham\Models\SQL\Student;
use Aham\Models\SQL\StudentCredits;
use Aham\Models\SQL\StudentEnrollment;
use Aham\Models\SQL\AhamClass;
use Aham\Models\SQL\UserEnrollment;

class StudentReconcileHelper {

    public $student;

    public function __construct($student)
    {
        $this->student = $student;
    }

    public function evaluate()
    {
        $totalCreditsNow = $this->getStudentCredits();

        $deductedFromGuestSeries = $this->getFromUserEnrollments();
        $deductableFromGuestSeries = $this->deductableFromGuestSeries();

        $deductableCreditsForClasses = $this->deductableCreditsForClassEnrollments();
        $deductedCreditsForClasses = $this->getFromStudentEnrollments();

        $refundableFromGuestSeries = $this->getFromCancelledUserEnrollments();
        $refundableFromCancelledClasses = $this->getFromCancelledClasses();
        $refundableFromCancelledEnrollments = $this->getFromCancelledEnrollments();
        $totalRefunded = StudentCredits::where('student_id',$this->student->id)->where('method','refund')->sum('credits');
        $totalRefundable = $refundableFromCancelledClasses + $refundableFromGuestSeries + $refundableFromCancelledEnrollments;

        $wrongReasons = [];

        $whatsWrong = 'Nothing';

        if($deductedCreditsForClasses != $deductableCreditsForClasses)
        {
            $wrongReasons[] = "Credits deducted for classes and deductable are not matching.";
        }

        if($deductedFromGuestSeries != $deductedFromGuestSeries)
        {
            $wrongReasons[] = "Credits deducted for guest series and deductable are not matching.";
        }


        if($totalRefunded != $totalRefundable)
        {
            $wrongReasons[] = "Refunds are not matching.";
        }

        if(count($wrongReasons))
        {
            $whatsWrong = implode(' , ', $wrongReasons);
        }

        return [

            'whatsWrong' => $whatsWrong,

            'totalCreditsNow' => $totalCreditsNow,

            'deductedCreditsForClasses' => $deductedCreditsForClasses,
            'deductableCreditsForClasses' => $deductableCreditsForClasses,

            'refundableFromCancelledClasses' => $refundableFromCancelledClasses,
            'refundableFromGuestSeries' => $refundableFromGuestSeries,
            'refundableFromCancelledEnrollments' => $refundableFromCancelledEnrollments,
            'totalRefunded' => $totalRefunded,
            'totalRefundable' => $totalRefundable,

            'deductedFromGuestSeries' => $deductedFromGuestSeries,
            'deductableFromGuestSeries' => $deductableFromGuestSeries,
            
        ];
    }

    public function deductableFromGuestSeries()
    {
        $user = $this->student->user;

        $enrollments = UserEnrollment::where('user_id',$user->id)
                                ->where('method','credits')
                                ->get();

        $deductableCredits = 0;

        foreach($enrollments as $enrollment)
        {
            if($enrollment->type == 'episode')
            {
                $series = $enrollment->episode->series;
            }

            if($enrollment->type == 'level')
            {
                $series = $enrollment->level->series;
            }

            if($series->status != 'cancelled')
            {
               if($series->enrollment_restriction == 'restrict_by_level')
               {
                    $level = $enrollment->level;

                    $number_of_credits = round(($series->cost_per_episode/1000)*$level->episodes->count());

                    $deductableCredits += $number_of_credits;
               }

            }
        }

        return $deductableCredits;
    }

    public function deductableCreditsForClassEnrollments()
    {
        $enrollments = StudentEnrollment::where('student_id',$this->student->id)
                                        ->get();

        $deductableCredits = 0;

        foreach($enrollments as $enrollment)
        {
            $ahamClass = $enrollment->ahamClass;

            $deductableCredits += $ahamClass->timings->count();

        }

        return $deductableCredits;
    }

    public function getStudentCredits()
    {
        $credits = StudentCredits::where('student_id',$this->student->id)->sum('credits');

        return $credits ? (float) $credits : 0;
    }

    public function getFromStudentEnrollments()
    {
        $credits = StudentEnrollment::where('student_id',$this->student->id)->sum('credits');

        return $credits ? (float) $credits : 0;
    }

    public function getFromCancelledClasses()
    {
        // Get cancelled classes from student enrollments

        $enrollments = StudentEnrollment::where('student_id',$this->student->id)->pluck('class_id')->toArray();

        $cancelledClasses = AhamClass::whereIn('id',$enrollments)->where('status','cancelled')->where('free',false)->pluck('id')->toArray();

        $credits = StudentEnrollment::where('student_id',$this->student->id)->whereIn('class_id',$cancelledClasses)->sum('credits');

        return $credits ? (float) $credits : 0;
    }


    public function getFromCancelledEnrollments()
    {
        $credits = StudentEnrollment::where('student_id',$this->student->id)
                                        ->where('status','cancelled_by_student')
                                        ->sum('credits');

        return $credits ? (float) $credits : 0;                           
    }

    public function getFromUserEnrollments()
    {
        $user = $this->student->user;

        $credits = UserEnrollment::where('user_id',$user->id)
                                ->where('method','credits')
                                ->sum('credits');

        return $credits ? (float) $credits : 0;
    }

    public function getFromCancelledUserEnrollments()
    {
        $cancelledCredits = 0;

        $user = $this->student->user;

        $enrollments = UserEnrollment::where('user_id',$user->id)->get();

        foreach($enrollments as $enrollment)
        {
            if($enrollment->type == 'episode')
            {
                $series = $enrollment->episode->series;
            }

            if($enrollment->type == 'level')
            {
                $series = $enrollment->level->series;
            }

            if($series->status == 'cancelled')
            {
                $cancelledCredits += $enrollment->credits;
            }
        }

        return (float) $cancelledCredits;

    }

}
