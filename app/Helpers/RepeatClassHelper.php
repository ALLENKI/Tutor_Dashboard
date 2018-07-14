<?php

namespace Aham\Helpers;

use Carbon;
use DB;

use Aham\Models\SQL\AhamClass;
use Aham\Models\SQL\Topic;
use Aham\Models\SQL\ClassUnit;
use Aham\Models\SQL\ClassTiming;
use Aham\Models\SQL\Student;
use Aham\Models\SQL\StudentInvitation;
use Aham\Models\SQL\ClassInvitation;
use Aham\Managers\EnrollmentManager;


class RepeatClassHelper {

    public $repeatClass;
    public $fromDate;
    public $toDate;
    public $days;
    public $enrollments;
    public $invitations;
    public $dates = [];

    public function __construct($repeatClass)
    {
        $this->repeatClass = $repeatClass;
    }

    public function addToEnrollments($id)
    {
        $this->enrollments[] = $id;
    }

    public function process()
    {
        $payload = unserialize($this->repeatClass->payload);

        if(isset($payload['from_date']))
        {
            $this->fromDate = Carbon::createFromTimestamp(strtotime($payload['from_date']));
        }

        if(isset($payload['to_date']))
        {
            $this->toDate = Carbon::createFromTimestamp(strtotime($payload['to_date']));
        }

        if(isset($payload['enrollments']))
        {
            $this->enrollments = $payload['enrollments'];
        }


        if(isset($payload['invitations']))
        {
            $this->invitations = $payload['invitations'];
        }

        if(isset($payload['days']))
        {
            $this->days = $payload['days'];
        }

        $this->findDates();

        foreach($this->dates as $date)
        {
            $this->createClass($date);
        }

    }

    public function shouldShortlist($number)
    {
        $carbonDays = [
            0 => 'Sunday',
            1 => 'Monday',
            2 => 'Tuesday',
            3 => 'Wednesday',
            4 => 'Thursday',
            5 => 'Friday',
            6 => 'Saturday'
        ];

        $dayOfWeek = $carbonDays[$number];

        return in_array($dayOfWeek,$this->days);

    }

    public function findDates()
    {
        $startDate = clone $this->fromDate;

        while(!$startDate->isSameDay($this->toDate))
        {
            if($this->shouldShortlist($startDate->dayOfWeek))
            {
                $this->dates[] = $startDate->format('d-m-Y');
            }
            $startDate->addDays(1);
        }

        $this->dates[] = $this->toDate->format('d-m-Y');
    }

    public function createNewClass($ahamClass)
    {
        $newClassData = array_only($ahamClass->toArray(),['location_id', 'topic_id', 'minimum_enrollment', 'maximum_enrollment', 'charge_multiply','type','unit_duration','creator_id','free','no_tutor_comp','auto_cancel','topic_name','topic_description']);

        $newClassData['of_id'] = $newClassData['topic_id']; 
        $newClassData['of_type'] = Topic::class; 
        $newClassData['source_class_id'] = $this->repeatClass->class_id;

        $newClass = AhamClass::create($newClassData);

        foreach($ahamClass->classUnits as $oldClassUnit)
        {
            ClassUnit::create([
                'name' => $oldClassUnit->name,
                'description' => $oldClassUnit->description,
                'order' => $oldClassUnit->order,
                'topic_id' => $oldClassUnit->topic_id,
                'class_id' => $newClass->id,
                'original_unit_id' => $oldClassUnit->original_unit_id
            ]);
        }

        $newClass->status = 'initiated';
        $newClass->save();

        return $newClass;
    }

    public function scheduleClass($ahamClass, $newClass, $startDate)
    {
        // Shortlist dates, timings and classrooms

        $classTimings = $this->shortlistTimings($ahamClass, $newClass, $startDate);

        $result = array_filter($classTimings,function($classTiming){
            return $classTiming['classroom_id'] == 0;
        });

        if(!count($result))
        {
            foreach($classTimings as $classTiming)
            {
                $createdTiming = ClassTiming::firstOrCreate(array_only($classTiming,[
                    'class_id','class_unit_id','date','start_time','end_time'
                ]));
                $createdTiming->fill($classTiming);
                $createdTiming->save();

                $invitation = ClassInvitation::firstOrCreate([
                    'class_id' => $classTiming['class_id'],
                    'teacher_id' => $classTiming['teacher_id']
                ]);
                $invitation->status = 'awarded';
                $invitation->save();

            }

            $newClass->start_date = $classTimings[0]['date']->format('Y-m-d').' '.
                                     $classTimings[0]['start_time'];
            

            $newClass->status = 'open_for_enrollment';
            $newClass->save();

            return true;
        }

        return false;

        // If all units found classrooms, then create them
    }

    public function shortlistTimings($ahamClass, $newClass, $startDate)
    {
        $classTimings = $ahamClass->classTimings;

        $classrooms = $ahamClass->location->classrooms()->where('active',true)->pluck('id')->toArray();

        $classUnits = $newClass->classUnits;

        $classTimings = [];

        $originalFirstClassTiming = $ahamClass->classTimings->first();

        foreach($classUnits as $index => $classUnit)
        {
            $originalClassUnit = $ahamClass
                                ->classUnits()
                                ->where('original_unit_id',$classUnit->original_unit_id)
                                ->first();

            $originalClassTiming = $ahamClass
                                    ->classTimings()
                                    ->where('class_unit_id',$originalClassUnit->id)
                                    ->first();

            $classTiming = [];

            $classTiming['class_id'] = $newClass->id;
            $classTiming['class_unit_id'] = $classUnit->id;
            $classTiming['unit_id'] = $classUnit->original_unit_id;

            if($index == 0)
            {
                $classTiming['date'] = Carbon::createFromTimestamp(strtotime($startDate));

            }else {

                $diffInDays = $originalClassTiming->date->diffInDays($originalFirstClassTiming->date);

                $classTiming['date'] = Carbon::createFromTimestamp(strtotime($startDate))->addDays($diffInDays);
            }

            $classTiming['start_time'] = $originalClassTiming->start_time;
            $classTiming['end_time'] = $originalClassTiming->end_time;
            $classTiming['location_id'] = $newClass->location_id;
            $classTiming['of_id'] = $newClass->id;
            $classTiming['of_type'] = get_class($newClass);
            $classTiming['teacher_id'] = $originalClassTiming->teacher_id;
            $classTiming['commission'] = $originalClassTiming->commission;
            $classTiming['classroom_id'] = 0;

            $classTiming['classroom_id'] = $this->findAClassroom($classTiming,$classrooms);

            $classTimings[] = $classTiming;

        }

        return $classTimings;

    }


    public function findAClassroom($classTiming,$classrooms)
    {
        $busyClassrooms  = ClassTiming::where([
                            'date' => $classTiming['date'],
                            'location_id' => $classTiming['location_id']
                        ])
                        ->where(function($query) use ($classTiming){
                            $query->where(function($query) use ($classTiming) {
                                    $query
                                    ->where('start_time','<=',$classTiming['start_time'])
                                    ->where('end_time','>=',$classTiming['start_time']);
                                  })
                                ->orWhere(function($query) use ($classTiming) {
                                    $query
                                    ->where('start_time','<=',$classTiming['end_time'])
                                    ->where('end_time','>=',$classTiming['end_time']);
                                });
                        })
                        ->whereNotIn('status',['cancelled'])
                        ->pluck('classroom_id')
                        ->toArray();

        $remainingClassrooms = array_values(array_diff($classrooms,$busyClassrooms));

        if(count($remainingClassrooms))
        {
            return $remainingClassrooms[0];
        }
        else
        {
            return 0;
        }

    }

    public function inviteStudents($ahamClass)
    {
        foreach($this->invitations as $student_id)
        {
            // $student = Student::find($student_id);

            StudentInvitation::firstOrCreate([
                'class_id' => $ahamClass->id,
                'student_id' => $student_id
            ]);

        }

        return true;

    }

    public function enrollStudents($ahamClass)
    {
        foreach($this->enrollments as $student_id)
        {
            $student = Student::find($student_id);

            $result = $this->checkEligibilityAndEnroll($ahamClass, $student);

            var_dump($result);
        }

        return true;

    }

    public function checkEligibilityAndEnroll($ahamClass, $student)
    {
        $manager = new EnrollmentManager($ahamClass, $student);

        if ($manager->alreadyEnrolled()) {
            return [
                'status' => false,
                'reason' => 'Already Enrolled'
            ];
        }   

        if ($manager->pendingClass()) {
            return [
                'status' => false,
                'reason' => 'Finish Pending Class'
            ];
        }

        if (!$manager->hasAvailability()) {
            $this->enrollAStudent($ahamClass,$student);
            return true;
        }

        if (!$manager->checkAvailability()) {
            return [
                'status' => false,
                'reason' => 'Not Available'
            ];
        }

        if($ahamClass->free){
            $this->enrollAStudent($ahamClass,$student);   
            return true;         
        }

        if (!$manager->hasEnoughCredits($ahamClass)) {
            return [
                'status' => false,
                'reason' => 'No Enough Credits'
            ];
        }

        if (!$manager->isEligible()) {
            $this->enrollAStudent($ahamClass,$student); 
            return true;
        }

        $this->enrollAStudent($ahamClass,$student);   
        return true;    

    }

    public function enrollAStudent($ahamClass, $student)
    {
        $manager = new EnrollmentManager($ahamClass, $student);

        if ($ahamClass->free) {
            $manager->freeEnroll();
        }

        if (!$manager->isEligible()) {
            $manager->enrollAsGhost();
        }

        $manager->enroll();

        return true;
    }


    public function createClass($date)
    {
        $ahamClass = AhamClass::find($this->repeatClass->class_id);

        $newClass = $this->createNewClass($ahamClass);

        if($this->scheduleClass($ahamClass, $newClass, $date))
        {
            $this->enrollStudents($newClass);
            $this->inviteStudents($newClass);
        }

        return $newClass;
    }


}
