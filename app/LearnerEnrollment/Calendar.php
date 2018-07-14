<?php

namespace Aham\LearnerEnrollment;

use Aham\Models\SQL\AhamClass;
use Carbon\Carbon;
use Aham\Models\SQL\StudentEnrollmentUnit;
use DB;

class Calendar
{
    public $student;
    public $fromDate;
    public $toDate;

    public function __construct($student)
    {
        $this->student = $student;
    }

    public function getUnitsOnADate($date)
    {
        $date = Carbon::createFromTimestamp(strtotime($date));

        $classTimings = StudentEnrollmentUnit::where('student_id', $this->student->id)
                        ->where('date', $date->format('Y-m-d'))
                        ->where('status', 'enrolled')
                        ->orderBy('date', 'desc')
                        ->orderBy('start_time', 'desc')
                        ->paginate(100);

        return  $classTimings;
    }

    public function getTodayUnits()
    {
        $classTimings = StudentEnrollmentUnit::where('student_id', $this->student->id)
                        ->where('date', '=', Carbon::now()->format('Y-m-d'))
                        ->where('end_time', '>=', Carbon::now()->format('H:i:s'))
                        ->where('status', 'enrolled')
                        ->orderBy('date', 'desc')
                        ->orderBy('start_time', 'desc')
                        ->paginate(100);

        return  $classTimings;
    }

    public function getCompletedUnits()
    {
        $classTimings = StudentEnrollmentUnit::where('student_id', $this->student->id)
                        ->whereRaw("TIMESTAMP(date,end_time) < '" . Carbon::now() . "'")
                        ->where('status', 'enrolled')
                        ->orderBy('date', 'desc')
                        ->orderBy('start_time', 'desc')
                        ->paginate(15);

        return  $classTimings;
    }
}
