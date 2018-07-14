<?php

namespace Aham\Console\Commands;

use Illuminate\Console\Command;


use Aham\Models\SQL\ClassTiming;
use Aham\Models\SQL\AhamClass;
use Aham\Models\SQL\ClassUnit;
use Aham\Models\SQL\StudentEnrollment;
use Aham\Models\SQL\StudentEnrollmentUnit;

class SyncStudentEnrollmentsCredits extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'aham:sync_student_enrollment_credits';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        /**
            1. Get Student Enrollments before 2018-01-09 13:30:13
            2. Find all those enrollments, whose class_id didn't occur more than once
            3. Find student enrollment units rows for this enrollment.
        **/

        $studentEnrollments = StudentEnrollment::with('ahamClass.classTimings')
                                    ->where('created_at','>=','2018-01-09 16:12:37')
                                    ->get();

        foreach($studentEnrollments as $studentEnrollment)
        {
            $totalEnrollmentsCount = StudentEnrollment::where([
                                        'class_id' => $studentEnrollment->class_id,
                                        'student_id' => $studentEnrollment->student_id,
                                     ])->count();

            $totalEnrollmentsUnitsCount = StudentEnrollmentUnit::where([
                                        'class_id' => $studentEnrollment->class_id,
                                        'student_id' => $studentEnrollment->student_id,
                                     ])->count();

            if($totalEnrollmentsCount > 1)
            { 
                // If student enrolled more than once in a class, he should have more than one set of entries in student enrollment units
                $this->line('StudentId: '.$studentEnrollment->student_id.' ClassId: '.$studentEnrollment->class_id.' Total Enrollments: '.$totalEnrollmentsCount.' Total Units: '.$studentEnrollment->ahamClass->classTimings->count().' Total Enrollment Units: '.$totalEnrollmentsUnitsCount);
            }
            else
            {
                $findEnrollmentUnits = StudentEnrollmentUnit::where([
                                        'class_id' => $studentEnrollment->class_id,
                                        'student_id' => $studentEnrollment->student_id,
                                     ])
                                     ->whereNull('student_enrollment_id')
                                     ->get();

                if($studentEnrollment->ahamClass->classTimings->count() != $findEnrollmentUnits->count())
                {
                    // $this->line('StudentId: '.$studentEnrollment->student_id.' ClassId:'.$studentEnrollment->class_id.' classUnits: '.$studentEnrollment->ahamClass->classTimings->count().' findEnrollmentUnits: '.$findEnrollmentUnits->count());
                }
            }
            
        }
    }
}
