<?php namespace Aham\Managers;

use Input;
use File;

use Aham\Models\SQL\AhamClass;
use Aham\Models\SQL\Student;
use Aham\Models\SQL\Teacher;
use Aham\Models\SQL\ClassTiming;
use Aham\Models\SQL\StudentEnrollment;
use Aham\Models\SQL\StudentInvitation;
use Aham\Models\SQL\StudentEnrollmentUnit;
use Aham\Models\SQL\ClassUnit;

use Aham\CreditsEngine\Used;
use Aham\CreditsEngine\Refund;
use Aham\CreditsEngine\Add;

class EnrollmentManager
{
    public $class;
    public $student;

    public function __construct(AhamClass $class, Student $student)
    {
        $this->class = $class;
        $this->student = $student;
    }

    public function pendingClass()
    {
        return false;

        $topic = $this->class->topic;

        $otherClasses = $topic->classes()
                                ->whereIn('status', ['open_for_enrollment','scheduled','in_session'])
                                ->get();


        $enrolled = $this->student->enrollments()
                        ->whereIn('class_id', $otherClasses->pluck('id')->toArray())
                        ->count();

        return $enrolled ? true: false;
    }

    public function alreadyEnrolled()
    {
        $enrollment = StudentEnrollment::where([
            'class_id' => $this->class->id,
            'student_id' => $this->student->id
        ])
        ->where('cancelled', false)
        ->first();

        return $enrollment ? true : false;
    }

    public function checkAvailability()
    {
        // dd($this->student);

        $classes = $this->student->classes->pluck('class_id')->toArray();

        // dd($classes);

        $classTimings = $this->class->timings;

        // dd($classTimings);

        // dd($classes);

        // Find if this student is doing any other classes in these timings

        $busy = $this->searchBusySlot($classes, $classTimings);

        // busy false means user is not busy

        if (!$busy) {   // Only search if busy is false
            // If this student is also a teacher, find if he is busy
            if ($this->student->user->teacher) {
                $classes = $this->student->user->teacher->classes->pluck('id')->toArray();
                $busy = $this->searchBusySlot($classes, $classTimings);
            }
        }

        // In the end if user is busy, he can't take this class! So negate it.

        return !$busy;
    }

    public function searchBusySlot($classes, $classTimings)
    {
        foreach ($classTimings as $classTiming) {
            $slot = $classTiming->slot;

            $busyTimings = ClassTiming::whereIn('class_id', $classes)
                                ->where('date', $classTiming->date)
                                ->where(function ($query) use ($slot,$classTiming) {
                                    $query->whereBetween('start_time', [$classTiming->start_time,$classTiming->end_time])
                                          ->orWhereBetween('end_time', [$classTiming->start_time,$classTiming->end_time]);
                                })
                                ->first();

            if ($busyTimings) {
                return true;
            }
        }
    }

    public function hasAvailability()
    {
        return	$this->class->enrollments->count() >= $this->class->maximum_enrollment ? false : true;
    }

    public function hasEnoughCredits($ahamClass)
    {
        $creditsUsedEngine = new Used();
        $buckets = $creditsUsedEngine->findBuckets($this->student->user, ($ahamClass->classUnits->count() * $ahamClass->charge_multiply), $ahamClass->location_id);

        return count($buckets) > 0;
    }

    public function isEligible()
    {
        $student = $this->student;
        $topic = $this->class->topic;

        $eligible = false;

        $eligible = $student->assessments()->where('topic_id', $topic->id)->count();

        if ($eligible) {
            return true;
        }

        $prerequisiteAssessments = [];

        foreach ($topic->prerequisites as $prerequisite) {
            $prerequisiteAssessments[] =
                        $student->assessments()->where('topic_id', $prerequisite->id)->count();
        }

        return !in_array(0, $prerequisiteAssessments);
    }

    public function rerunEnroll($enrollment)
    {
        $class = $enrollment->ahamClass;

        if(!$this->hasEnoughCredits($class))
        {
            return false;
        }

        $student = $enrollment->student;

        $creditsPerUnit =  ($class->classUnits->count() * $class->charge_multiply)  / $class->classUnits->count();

        foreach ($class->timings as $unit) {

            $studentEnrollmentUnit = StudentEnrollmentUnit::firstOrCreate([
                'class_id' => $class->id,
                'class_unit_id' => $unit->class_unit_id,
                'student_enrollment_id' => $enrollment->id,
                'student_id' => $student->id,
            ]);

            $studentEnrollmentUnit->fill([
                'classroom_id' => $unit->classroom_id,
                'date' => $unit->date,
                'start_time' => $unit->start_time,
                'end_time' => $unit->end_time,
                'location_id' => $class->location_id,
                'credits_used' => $creditsPerUnit,
                'status' => 'enrolled',
            ]);

            $studentEnrollmentUnit->save();

        }

        $invitation = StudentInvitation::where([
            'class_id' => $class->id,
            'student_id' => $student->id,
        ])->first();

        if (!is_null($invitation)) {
            $invitation->status = 'enrolled';
            $invitation->save();
        }

        $creditsUsedEngine = new Used();
        $creditsUsedEngine->chargeUser($class, $student->user);
        
        return true;

    }

    public function enroll()
    {

        if(!$this->hasEnoughCredits($this->class))
        {
            return false;
        }

        // Find if already enrolled
        $enrollment = StudentEnrollment::where([
            'class_id' => $this->class->id,
            'student_id' => $this->student->id,
            'cancelled' => 0
        ])->first();

        // Make sure class is not cancelled

        if (!$enrollment && $this->class->status != 'cancelled') {
           $studentEnrollment = StudentEnrollment::create([
                'class_id' => $this->class->id,
                'student_id' => $this->student->id,
                'credits' => ($this->class->classUnits->count() * $this->class->charge_multiply)
            ]);
        
            $creditsPerUnit =  ($this->class->classUnits->count() * $this->class->charge_multiply)                                   / $this->class->classUnits->count();

            foreach ($this->class->timings as $unit) {
                StudentEnrollmentUnit::create([
                    'class_id' => $this->class->id,
                    'class_unit_id' => $unit->class_unit_id,
                    'classroom_id' => $unit->classroom_id,
                    'date' => $unit->date,
                    'start_time' => $unit->start_time,
                    'end_time' => $unit->end_time,
                    'student_id' => $this->student->id,
                    'location_id' => $this->class->location_id,
                    'credits_used' => $creditsPerUnit,
                    'status' => 'enrolled',
                    'student_enrollment_id' => $studentEnrollment->id,
                ]);
            }
            
            $invitation = StudentInvitation::where([
                'class_id' => $this->class->id,
                'student_id' => $this->student->id,
            ])->first();

            if (!is_null($invitation)) {
                $invitation->status = 'enrolled';
                $invitation->save();
            }

            $creditsUsedEngine = new Used();
            $creditsUsedEngine->chargeUser($this->class, $this->student->user);
            
            $this->student->credits -= ($this->class->classUnits->count() * $this->class->charge_multiply);
            $this->student->save();

            $this->class->enrolled += 1;
            $this->class->save();
        }

        return true;
    }


    public function enrollAsGhost()
    {
        if(!$this->hasEnoughCredits($this->class))
        {
            return false;
        }
        
        $enrollment = StudentEnrollment::where([
            'class_id' => $this->class->id,
            'student_id' => $this->student->id,
            'cancelled' => 0
        ])
        ->first();

        \Log::info('About to ghost enroll');

        if (!$enrollment && $this->class->status != 'cancelled') {
            \Log::info('Enrolling as ghost');

           $studentEnrollment =  StudentEnrollment::create([
                'class_id' => $this->class->id,
                'student_id' => $this->student->id,
                'credits' => $this->class->classUnits->count()*$this->class->charge_multiply,
                'ghost' => true
            ]);

            $creditsPerUnit =  ($this->class->classUnits->count() * $this->class->charge_multiply)                                   / $this->class->classUnits->count();

            foreach ($this->class->timings as $unit) {
                StudentEnrollmentUnit::create([
                    'class_id' => $this->class->id,
                    'class_unit_id' => $unit->class_unit_id,
                    'classroom_id' => $unit->classroom_id,
                    'date' => $unit->date,
                    'start_time' => $unit->start_time,
                    'end_time' => $unit->end_time,
                    'student_id' => $this->student->id,
                    'location_id' => $this->class->location_id,
                    'credits_used' => $creditsPerUnit,
                    'status' => 'enrolled',
                    'student_enrollment_id' => $studentEnrollment->id
                ]);
            }

            $invitation = StudentInvitation::where([
                'class_id' => $this->class->id,
                'student_id' => $this->student->id,
            ])->first();

            if (!is_null($invitation)) {
                $invitation->status = 'enrolled';
                $invitation->save();
            }

            $creditsUsedEngine = new Used();
            $creditsUsedEngine->chargeUser($this->class, $this->student->user);
            
            $this->student->credits -= $this->class->classUnits->count()*$this->class->charge_multiply;
            $this->student->save();

            $this->class->enrolled += 1;
            $this->class->save();
        }
        
        return true;
    }

    public function freeEnroll()
    {
        $enrollment = StudentEnrollment::where([
            'class_id' => $this->class->id,
            'student_id' => $this->student->id
        ])->first();


        if (!$enrollment && $this->class->status != 'cancelled') {
        
            $coupon = CouponManager::createFreeClassCoupon($this->class, $this->student);


            $creditsAddEngine = new Add(
                $this->student->user_id,
                'INR',
                $this->class->location_id
            );

            $creditsAddEngine->promotional(0, 
                        'Free credits to enroll in '.$this->class->code, 
                        $coupon->coupon, 1, 0);


            // return false;

            $creditsPerUnit =  ($this->class->classUnits->count() * $this->class->charge_multiply)                                   / $this->class->classUnits->count();

            
           $studentEnrollment = StudentEnrollment::create([
                'class_id' => $this->class->id,
                'student_id' => $this->student->id,
                'credits' => ($this->class->classUnits->count() * $this->class->charge_multiply)
            ]);

            foreach ($this->class->timings as $unit) {
                StudentEnrollmentUnit::create([
                    'class_id' => $this->class->id,
                    'class_unit_id' => $unit->class_unit_id,
                    'classroom_id' => $unit->classroom_id,
                    'date' => $unit->date,
                    'start_time' => $unit->start_time,
                    'end_time' => $unit->end_time,
                    'student_id' => $this->student->id,
                    'location_id' => $this->class->location_id,
                    'credits_used' => $creditsPerUnit,
                    'status' => 'enrolled',
                    'student_enrollment_id' => $studentEnrollment->id,
                ]);
            }
            
            $invitation = StudentInvitation::where([
                'class_id' => $this->class->id,
                'student_id' => $this->student->id,
            ])->first();

            if (!is_null($invitation)) {
                $invitation->status = 'enrolled';
                $invitation->save();
            }

            $creditsUsedEngine = new Used();
            $creditsUsedEngine->chargeUser($this->class, $this->student->user);
            
            $this->student->credits -= ($this->class->classUnits->count() * $this->class->charge_multiply);
            $this->student->save();

            $this->class->enrolled += 1;
            $this->class->save();
        }

        return true;
    }

    

    public function cancelEnrollment($reason = "cancelled_by_student")
    {
        $enrollment = StudentEnrollment::with('ahamClass', 'student')
                            ->where([
                                'class_id' => $this->class->id,
                                'student_id' => $this->student->id,
                                'status' => 'enrolled'
                            ])->first();

       
                        
        // if we pass units.
        // $enrolledUnit =  StudentEnrollment::where('student_id',$this->student->id)
        //                                 ->where('class_unit_id',$this->unit->id)
        //                                 ->where('status','enrolled')
        //                                 ->get();

        $enrolledUnits =  StudentEnrollmentUnit::where('student_id', $this->student->id)
                                        ->where('class_id', $this->class->id)
                                        ->where('status', 'enrolled')
                                        ->get();
                                    
      
         
        \DB::beginTransaction();

        // $enrollment->status = 'cancelled_by_student';
        // $enrollment->cancelled = true;
        // $enrollment->save();

        foreach ($enrolledUnits as $enrolledUnit) {
            $this->cancelUnitEnrollment($enrolledUnit->id,$reason);
        }

        // CreditsManager::addEnrollmentCancelledCredits($enrollment);

        // $creditsRefundEngine = new Refund();
        // $creditsRefundEngine->refund($enrollment, $this->student->user);

        // $enrollment->delete();

        \DB::commit();

        return true;
    }
    
    public function cancelUnitEnrollment($unit,$reason)
    {

        $studentEnrollmentUnit = StudentEnrollmentUnit::find($unit);
        $learner = $studentEnrollmentUnit->learner;
        $user = $learner->user;
        $ahamClass = $studentEnrollmentUnit->ahamClass;
        $studentEnrollmentUnit->status = 'cancelled';
        $studentEnrollmentUnit->save();

        $creditsRefundEngine = new Refund();
        $creditsRefundEngine->addCreditsBack($ahamClass,$studentEnrollmentUnit->credits_used,$user);

        $enrolledUnits = StudentEnrollmentUnit::where('student_id', $this->student->id)
                                        ->where('class_id', $this->class->id)
                                        ->where('status', 'enrolled')
                                        ->get();


        if(!$enrolledUnits->count())
        {

           $enrollment = StudentEnrollment::with('ahamClass', 'student')
                    ->where([
                        'class_id' => $this->class->id,
                        'student_id' => $this->student->id,
                        'status' => 'enrolled'
                    ])->first();
                       
            $enrollment->status = $reason;
            $enrollment->cancelled = true;
            $enrollment->save();

            $this->class->enrolled -= 1;
            $this->class->save();
       
            event(new \Aham\Events\Student\StudentCancelledEnrollment($enrollment));

        }

        return true;

    }


    public function syncEnrollmentUnits($enrollment)
    {

        $classTimings = ClassTiming::where('class_id', $this->class->id)->get();

        $creditsPerUnit = ($enrollment->credits/$classTimings->count());

        foreach ($classTimings as $classTiming) {

            $classUnit = $classTiming->classUnit;

            $unitStatus = 'enrolled';

            if ($enrollment->status == 'cancelled_by_aham' || $enrollment->status =='cancelled_by_student' || $enrollment->status =='cancelled_by_admin') {

                $badEnrollment = StudentEnrollmentUnit::where([
                        'class_id' => $classTiming->class_id,
                        'class_unit_id' => $classUnit->id,
                        'student_id' => $enrollment->student_id,
                        'status' => $enrollment->status
                    ])->first();

                if(!is_null($badEnrollment))
                {
                    $badEnrollment->delete();
                }

                $unitStatus = 'cancelled';
            }


            $unitEnrollment = StudentEnrollmentUnit::firstOrCreate([
                                    'class_id' => $classTiming->class_id,
                                    'class_unit_id' => $classUnit->id,
                                    'student_id' => $enrollment->student_id,
                                    'status' => $unitStatus
                                ]);

            $unitEnrollment->fill([
                'location_id' => $classTiming->location_id,
                'credits_used' => $creditsPerUnit,
                'classroom_id' => $classTiming->classroom_id,
                'date' => $classTiming->date,
                'start_time' => $classTiming->start_time,
                'end_time' => $classTiming->end_time
            ]);
            
            $unitEnrollment->save();
        }

        return true;
    }
}
