<?php

namespace Aham\Console\Commands;

use Illuminate\Console\Command;
use Aham\Models\SQL\AhamClass;
use Aham\Models\SQL\ClassTiming;
use Aham\Models\SQL\StudentEnrollment;
use Aham\Models\SQL\StudentEnrollmentUnit;
use Aham\Managers\EnrollmentManager;

class FixUnitLevelEnrollment extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'aham:fix_unit_enrollment';

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

            $classes = AhamClass::all();

            foreach($classes as $ahamClass)
            {
                $this->line('Class: '.$ahamClass->id);
                // 1. find class units

                $classUnits = $ahamClass->classUnits;

                foreach($classUnits as $classUnit)
                {
                    $classTiming = ClassTiming::where([
                        'class_unit_id' => $classUnit->id,
                        'class_id' => $ahamClass->id
                    ])->first();

                    if(is_null($classTiming) && $ahamClass->timings->count())
                    {
                        $classUnit->delete();
                        $this->line('Useless Class Unit?: '.$classUnit->id.' in class '.$ahamClass->status);
                    }
                }

            }

            // die;
        
            // 1. get all the ahamclasses.
            $classes = AhamClass::where('free',false)->get();

            foreach($classes as $class) {

                // 2. get student enrollments for each class.
                // 3. count  class enrollments for each class
                $allStudentEnrollmentCount = $class->enrollments->count();

                $this->line('Class: '.$class->id);

                // $credits = ($allStudentEnrollmentCount * $class->charge_multiply)
                //              * $class->classUnits->count();

                // // get total credits
                // if($class->creditsUsed->count() != 0) {
                //     $actualCredits = 0;

                //     foreach($class->creditsUsed as $creditsUsed) {
                //         $actualCredits = $actualCredits + $creditsUsed->refund_remaining;
                //     }

                //     if($credits > $actualCredits) {
                //         var_dump('for ahamClass '.$class->id.' Credits:- '. $credits. ' Actual Credits:- '.$actualCredits. ' Credits Lost:- '.($credits - $actualCredits));
                //     }    

                // }

                // 4. get all the class units for each class.
                foreach($class->classUnits as $classUnit) {

                    // 5. get all the enrolled students count for each class units.
                    // 6. check class_enrollment_count > unit_enrollment_count, Means enrolled students are missing in unit level.
                    if($allStudentEnrollmentCount > $classUnit->enrollments->count() ) {

                        $this->line('AhamClass:- '.$class->id.' Unit:- '.$classUnit->id);

                        $classEnrollments = StudentEnrollment::where([
                            'class_id' => $class->id,
                        ])->get();

                        foreach($classEnrollments as $classEnrollment)
                        {
                            $studentEnrollmentUnit = StudentEnrollmentUnit::where([
                                'class_unit_id' => $classUnit->id,
                                'student_enrollment_id' => $classEnrollment->id,
                                'student_id' => $classEnrollment->student_id
                            ])->first();

                            if(is_null($studentEnrollmentUnit))
                            {
                                $this->line('Missing Enrollment:- '.$class->id.' Unit:- '.$classUnit->id.' Student:- '.$classEnrollment->student_id);

                                $enrollmentManager = new EnrollmentManager($class,$classEnrollment->student);
                                $enrollmentManager->rerunEnroll($classEnrollment);

                            }
                        }


                        $credits = ($allStudentEnrollmentCount * $class->charge_multiply)
                                     * $class->classUnits->count();

                        // get total credits
                        if($class->creditsUsed->count() != 0) {
                            $actualCredits = 0;

                            foreach($class->creditsUsed as $creditsUsed) {
                                $actualCredits = $actualCredits + $creditsUsed->refund_remaining;
                            }

                            if($credits > $actualCredits) {
                                $this->line('for ahamClass '.$class->id.' Credits:- '. $credits. ' Actual Credits:- '.$actualCredits. ' Credits Lost:- '.($credits - $actualCredits));
                            }    

                        }


                    }

                }

                
            }


    }

}
