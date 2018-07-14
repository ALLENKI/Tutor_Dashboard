<?php

namespace Aham\Managers;

use Aham\Models\SQL\AhamClass;
use Carbon\Carbon;
use Aham\Models\SQL\ClassTiming;
use DB;

class StudentClassesManager
{
    public $student;

    public function __construct($student)
    {
        $this->student = $student;
    }

    public function getAllClasses()
    {
    }

    public function getOngoingTimings($per_page = 15)
    {
        $enrollmentUnits = $this->student
                                ->enrollmentUnits()
                                ->where('date', '=', Carbon::now()->format('Y-m-d'))
                                ->where('end_time', '>=', Carbon::now()->format('H:i:s'))
                                ->orderBy('date', 'asc')
                                ->orderBy('start_time', 'asc')
                                ->paginate($per_page);

        return $enrollmentUnits;

    }

    public function getUpcomingTimings($per_page = 15)
    {
        $enrollmentUnits = $this->student
                                ->enrollmentUnits()
                                ->where('date', '>', Carbon::now()->format('Y-m-d'))
                                ->orderBy('date', 'asc')
                                ->orderBy('start_time', 'asc')
                                ->paginate($per_page);

        return $enrollmentUnits;

    }

    public function getCompletedTimings($per_page = 15)
    {

        $enrollmentUnits = $this->student
                                ->enrollmentUnits()
                                ->whereRaw("TIMESTAMP(date,end_time) < '" . Carbon::now() . "'")
                                ->orderBy('date', 'desc')
                                ->orderBy('start_time', 'desc')
                                ->paginate($per_page);

        return $enrollmentUnits;
    }

    public function getEnrolledClasses($limit = null)
    {
        $enrollments = $this->student->enrollments->pluck('class_id')->toArray();

        $enrolledClasses = AhamClass::whereIn('status', ['open_for_enrollment'])
                                    ->whereIn('id', $enrollments)
                                    ->orderBy('start_date', 'desc')
                                    ->get();

        return $enrolledClasses;
    }

    public function getEligibleClasses($limit = null)
    {
    }

    public function getEligibilityStatus($class)
    {
    }
}
