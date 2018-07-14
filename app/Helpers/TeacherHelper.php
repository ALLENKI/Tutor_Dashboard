<?php

namespace Aham\Helpers;

use Carbon;
use DB;

use Aham\Models\SQL\Teacher;
use Aham\Models\SQL\TeacherCertification;
use Aham\Models\SQL\ClassTiming;
use Aham\Models\SQL\TeacherAvailability;
use Aham\Models\SQL\StudentEnrollment;
use Aham\Helpers\TeacherHelper;
use Aham\Models\SQL\StudentEnrollmentUnitsNew;

class TeacherHelper {

    public static function eligibleTeachers($ahamClass)
    {

        $topic =  $ahamClass->topic;

        $alreadyInvitedTeachers = $ahamClass
                                        ->invitations
                                        ->pluck('teacher_id')
                                        ->toArray();

        // He is certified to teach it

        $eligibleTeachers = TeacherCertification::with('teacher.classes','teacher.user')
                                                ->where('topic_id', $topic->id)
                                                ->get();
        //dd($eligibleTeachers->pluck('teacher_id')->toArray());


        $availableTeachers = [];

        foreach($eligibleTeachers as $eligibleTeacher)
        {
            $result = static::isAvailable($ahamClass, $eligibleTeacher);

            if($result)
            {
                $availableTeachers[] = $eligibleTeacher;
            }

        }
       
       //who are enroled as student
        $enrollStudents = StudentEnrollment::with('student.classes','student.user')
                                                ->where('class_id',$ahamClass->id)
                                                ->where('status','enrolled')
                                                ->get();
        
        //remove the teachers whos are enroled to class
        foreach ($enrollStudents as $enrollStudent)
        {
            $student=$enrollStudent->student;
            foreach ($availableTeachers as $key=>$availableTeacher)
            {
                $teacher=$availableTeacher->teacher;
               //var_dump($teacher->user->id);
                if($student->user->id==$teacher->user->id)
                {
                     unset($availableTeachers[$key]);
                }

            }
        }
        
        
        $shortlistedTeachers = [];

        foreach($availableTeachers as $teacherInvitation)
        {
            $teacher = $teacherInvitation->teacher;

            // if(in_array($teacher->id, $alreadyInvitedTeachers))
            // {
            //     continue;
            // }

            $shortlistedTeachers[$teacher->id] = $teacher->user->name.' ('.$teacher->user->email.')';
        }

        return $shortlistedTeachers;

       
    }

    public static function isAvailable($ahamClass,$model)
    {
        $daysOfTheWeek = [
            1 => 'monday',
            2 => 'tuesday',
            3 => 'wednesday',
            4 => 'thursday',
            5 => 'friday',
            6 => 'saturday',
            0 => 'sunday'
        ];


        $teacher = $model->teacher;

        // var_dump($teacher->user->name);

        // Find classes taken by teacher
        $classes = $teacher->classesTimings->pluck('class_id')->toArray();
           
        // Find timings of current class
        $classTimings = $ahamClass->timings;    

        $available = true;

        foreach($classTimings as $classTiming)
        {
            $start_time = $classTiming->start_time;
            $end_time = $classTiming->end_time;

            $dayOfWeek = $daysOfTheWeek[$classTiming->date->dayOfWeek];

            $busyTimings = ClassTiming::whereIn('class_id',$classes)
                                ->where('date',$classTiming->date)
                                ->where('teacher_id',$teacher->id)
                                ->whereHas('ahamClass',function($query){
                                    $query->where('status','<>','cancelled');
                                })
                                ->where(function ($query) use($start_time,$end_time) {
                                    $query->whereBetween('start_time', [$start_time,$end_time])
                                          ->orWhereBetween('end_time', [$start_time,$end_time]);
                                })
                                ->first();
                               
            if($busyTimings)
            {   
                $available = false;
                return $available;
            }

            // Find Teacher availability

            if(!is_null($classTiming->session) && !$teacher->ignore_calendar)
            {
                $there = TeacherAvailability::where('teacher_id',$teacher->id)
                          ->where('day_of_the_week',$dayOfWeek)
                          ->where('from_date','<=',$classTiming->date)
                          ->where('to_date','>=',$classTiming->date)
                          ->where($classTiming->session,true)
                          ->first();

                if(!$there)
                {   
                    // var_dump($there);
                    $available = false;
                    return $available;
                }
            }

        }

        return $available;
    }

    public static function eligibleTeachersPerUnit($unitTiming)
    {
        //dd($unitTiming);
        $availableTeachers = [];

        $topic = $unitTiming->ahamClass->topic;

        $eligibleTeachers = TeacherCertification::with('teacher.classes','teacher.user')
                                ->where('topic_id', $topic->id)
                                ->get();
        foreach($eligibleTeachers as $eligibleTeacher)
        {
            $result = static::isAvailablePerUnit($unitTiming, $eligibleTeacher);

            if($result)
            {
                $availableTeachers[] = $eligibleTeacher;
            }
        }

        //who are enroled as student per unit
        $enrollStudents = StudentEnrollmentUnitsNew::with('learner.user')
                                                ->where('class_id',$unitTiming->class_id)
                                                ->where('class_unit_id',$unitTiming->class_unit_id)
                                                ->where('status','enrolled')
                                                ->get();

        //remove the teachers whos are enroled to class
        foreach ($enrollStudents as $enrollStudent)
        {
            $student=$enrollStudent->learner;
            
            foreach ($availableTeachers as $key=>$availableTeacher)
            {
                $teacher=$availableTeacher->teacher;
                if($student->user->id==$teacher->user->id)
                {
                     unset($availableTeachers[$key]);
                }

            }
        }


        $shortlistedTeachers = [];

        foreach($availableTeachers as $teacherInvitation)
        {
            $teacher = $teacherInvitation->teacher;
            $shortlistedTeachers[$teacher->id] = $teacher->user->name.' ('.$teacher->user->email.')';
        }

        return $shortlistedTeachers;
    }

    public static function isAvailablePerUnit($unitTiming,$model){

        $daysOfTheWeek = [
            1 => 'monday',
            2 => 'tuesday',
            3 => 'wednesday',
            4 => 'thursday',
            5 => 'friday',
            6 => 'saturday',
            0 => 'sunday'
        ];

        $teacher = $model->teacher;

         //var_dump($teacher->user->name);

        // Find classes taken by teacher
        $classes = $teacher->classesTimings->pluck('id')->toArray();

        // Find timings of current class
        $classTiming = $unitTiming;

        $available = true;

            $start_time = $classTiming->start_time;
            $end_time = $classTiming->end_time;

            //dd($classTiming->date->format('Y-m-d H:i:s'),$slot,$start_time,$end_time);

            $dayOfWeek = $daysOfTheWeek[$classTiming->date->dayOfWeek];

            $busyTimings = ClassTiming::whereIn('id',$classes)
                                ->where('date',$classTiming->date)
                                ->whereHas('ahamClass',function($query){
                                        $query->where('status','<>','cancelled');
                                })
                                ->where(function ($query) use($start_time,$end_time) {
                                    $query->whereBetween('start_time', [$start_time,$end_time])
                                          ->orWhereBetween('end_time', [$start_time,$end_time]);
                                })
                                ->where('teacher_id',$teacher->id)
                                ->first();

            if($busyTimings)
            {
                $available = false;
                return $available;
            }

            // Find Teacher availability

            if(!is_null($classTiming->session) && !$teacher->ignore_calendar)
            {
                $there = TeacherAvailability::where('teacher_id',$teacher->id)
                          ->where('day_of_the_week',$dayOfWeek)
                          ->where('from_date','<=',$classTiming->date)
                          ->where('to_date','>=',$classTiming->date)
                          ->where($classTiming->session,true)
                          ->first();

                if(!$there)
                {
                    // var_dump($there);
                    $available = false;
                    return $available;
                }
            }

        return $available;
    }

	public static function getBusyTimings($teacher)
	{
        if($teacher->classes->count())
        {
            return [];
        }

        return [];
    }

}
