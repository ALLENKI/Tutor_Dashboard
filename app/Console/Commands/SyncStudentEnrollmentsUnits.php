<?php

namespace Aham\Console\Commands;

use Illuminate\Console\Command;


use Aham\Models\SQL\ClassTiming;
use Aham\Models\SQL\AhamClass;
use Aham\Models\SQL\ClassUnit;
use Aham\Models\SQL\StudentEnrollment;
use Aham\Models\SQL\StudentEnrollmentUnit;

class SyncStudentEnrollmentsUnits extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'aham:sync_student_enrollment_unit';

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
        \DB::table('student_enrollment_units')->truncate();

        $studentEnrollments = StudentEnrollment::all();

        foreach ($studentEnrollments as $studentEnrollment) {
            $this->line('Class:'.$studentEnrollment->class_id);

            $classTimings = ClassTiming::where('class_id', $studentEnrollment->class_id)->withTrashed()->get();
    
            if ($studentEnrollment->status == 'cancelled_by_aham' || $studentEnrollment->status =='cancelled_by_student') {
                $studentEnrollment->status ='cancelled';
            }
    
            $creditsPerUnit =  ($studentEnrollment->credits/$classTimings->count());
    
            foreach ($classTimings as $classTiming) {
                $classUnit = ClassUnit::where([
                    'class_id' => $classTiming->class_id,
                    'original_unit_id' => $classTiming->unit_id
                ])->withTrashed()->first();

                if (is_null($classUnit)) {
                    $classUnit = ClassUnit::firstOrCreate([
                        'class_id' => $classTiming->class_id,
                        'original_unit_id' => $classTiming->unit_id
                    ]);
                }


                $studentEnrollment =
                                    StudentEnrollmentUnit::firstOrCreate([
                                        'class_id' => $classTiming->class_id,
                                        'class_unit_id' => $classUnit->id,
                                        'student_id' => $studentEnrollment->student_id,
                                        'status' => 'enrolled'
                                    ]);
            
                $studentEnrollment->fill([
                    'location_id' => $classTiming->location_id,
                    'credits_used' => $creditsPerUnit,
                    'classroom_id' => $classTiming->classroom_id,
                    'date' => $classTiming->date,
                    'start_time' => $classTiming->start_time,
                    'end_time' => $classTiming->end_time
                ]);
                
                $studentEnrollment->save();
            }
        }
    }
}
