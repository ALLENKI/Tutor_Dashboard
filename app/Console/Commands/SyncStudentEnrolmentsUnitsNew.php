<?php

namespace Aham\Console\Commands;

use Illuminate\Console\Command;

use Aham\Models\SQL\StudentEnrollment;
use Aham\Models\SQL\StudentEnrollmentUnitsNew;
use Aham\Models\SQL\StudentEnrollmentUnit;
use Aham\Models\SQL\ClassUnit;
use Aham\Models\SQL\ClassTiming;

class SyncStudentEnrolmentsUnitsNew extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'aham:new_sync_student_enrolments_units';

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

        \DB::table('student_enrollment_units_new')->truncate();

        // mark very thing as false when we rerun the script.
        $this->markEnrolledUnitUsedAsfalse();

        // start from where we left.
        // $studentEnrollments = $this->start();

        // start all over again.
        $studentEnrollments = StudentEnrollment::orderby('class_id')->get();
        // $studentEnrollments = StudentEnrollment::where('class_id',6829)->get();

        foreach($studentEnrollments as $studentEnrollment)
        {
                $classId = $studentEnrollment->class_id;

                // get units from class units.
                $classUnits =  ClassUnit::where('class_id',$studentEnrollment->class_id)
                                         ->orderBy('created_at','asc')
                                         ->get();

                foreach($classUnits as $classUnit)
                {

                    // for each classunits check unit is befor '2017-03-24 12:22:28', check units are scheduled.
                    if($classUnit->created_at <= '2017-03-24 12:22:28') 
                    {

                        $timing = ClassTiming::where('class_unit_id',$classUnit->id)->first();

                        // $this->line('classUnits',$classUnit->original_unit_id);
                        
                        if(!is_null($timing))
                        {
                            // $this->line('timings units',$timing->unit_id);
                            // status check
                            $status = $this->statusCheck($studentEnrollment->status);
                            $status =  $this->statusCheckPerUnit($studentEnrollment,$classUnit->id,$status);

                            StudentEnrollmentUnitsNew::create([
                                'student_id' => $studentEnrollment->student_id,
                                'class_id' => $studentEnrollment->class_id,
                                'class_unit_id' => $classUnit->id,
                                'classroom_id' => $timing->classroom_id,
                                'location_id' => $timing->location_id,
                                'date' => $timing->date,
                                'start_time' => $timing->start_time,
                                'end_time' => $timing->end_time,
                                'remarks' => $timing->remarks,
                                'status' => $status,
                                'credits_used' => $studentEnrollment->ahamClass->charge_multiply,
                                'student_enrollment_id' => $studentEnrollment->id,
                            ]);
                            
                        }

                    } 
                    else {
                        // after '2017-03-24 12:22:28'
                        $timing = ClassTiming::where('class_unit_id',$classUnit->id)->first();

                        if(!is_null($timing)) {

                            // if classes timings are scheduled enroll student fill date,start_time,end_time

                            // status check
                            $status = $this->statusCheck($studentEnrollment->status);
                            $status =  $this->statusCheckPerUnit($studentEnrollment,$classUnit->id,$status);

                            StudentEnrollmentUnitsNew::create([
                                    'student_id' => $studentEnrollment->student_id,
                                    'class_id' => $studentEnrollment->class_id,
                                    'class_unit_id' => $classUnit->id,
                                    'classroom_id' => $timing->classroom_id,
                                    'location_id' => $timing->location_id,
                                    'date' => $timing->date,
                                    'start_time' => $timing->start_time,
                                    'end_time' => $timing->end_time,
                                    'remarks' => $studentEnrollment->remarks,
                                    'status' => $status,
                                    'credits_used' => $studentEnrollment->ahamClass->charge_multiply,
                                    'student_enrollment_id' => $studentEnrollment->id,
                            ]);

                            
                        } else {

                            // if classes timings are not scheduled enroll studen with empty timings
                            
                            // status check
                            $status = $this->statusCheck($studentEnrollment->status);
                            $status =  $this->statusCheckPerUnit($studentEnrollment,$classUnit->id,$status);

                            StudentEnrollmentUnitsNew::create([
                                'student_id' => $studentEnrollment->student_id,
                                'class_id' => $studentEnrollment->class_id,
                                'class_unit_id' => $classUnit->id,
                                'location_id' => $studentEnrollment->ahamClass->location_id,
                                'status' => $status,
                                'credits_used' => $studentEnrollment->ahamClass->charge_multiply,
                                'student_enrollment_id' => $studentEnrollment->id,
                            ]);

                        }

                    }


                }
        
            var_dump(" class_id: ".$studentEnrollment->class_id);
               
        }


    }

    public function markEnrolledUnitUsedAsfalse()
    {
       $studentEnrollments =  StudentEnrollmentUnit::where('used',true)->get();

       foreach($studentEnrollments as $studentEnrollment)
       {
            var_dump('used '.$studentEnrollment->used);
            $studentEnrollment->used = false;
            $studentEnrollment->save();
       }    

    }

    // public function start()
    // {
    //     $newEnrolledUnitsArray =  StudentEnrollmentUnitsNew::orderBy('class_id')
    //                                                         ->select('class_id')                      ->get()
    //                                                         ->toArray();

    //     $studentEnrollment = StudentEnrollment::whereNotIn('class_id',$newEnrolledUnitsArray)
    //                                             ->orderBy('class_id')
    //                                             ->get();

    //     // dd($studentEnrollment->count());

    //     if($studentEnrollment->count()) {
    //         return $studentEnrollment;
    //     } else {
    //         return StudentEnrollment::orderby('class_id')->get();
    //     }

    // }

    public function statusCheck($status) 
    {

        switch ($status) {
            case 'cancelled_by_admin':
                return 'cancelled';
                break;
            
            case 'cancelled_by_aham':
                return 'cancelled';
                break;

            case 'cancelled_by_student':
                return 'cancelled';
                break;

            default:
                return $status;
                break;
        }

    }

    public function statusCheckPerUnit($studentEnrollment,$unitId,$status)
    {
        $unitEnrolment =  StudentEnrollmentUnit::where('student_id',$studentEnrollment->student_id)
                            ->where('class_id',$studentEnrollment->class_id)
                            ->where('used',false)
                            ->where('class_unit_id',$unitId)
                            ->where('status','cancelled')
                            ->first();

        if(is_null($unitEnrolment)) {
            return $status;
        } else {
            $unitEnrolment->used = true;
            $unitEnrolment->save();
            return 'cancelled';
        }

    }


}
