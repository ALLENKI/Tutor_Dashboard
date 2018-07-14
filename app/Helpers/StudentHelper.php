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

class StudentHelper {

    public static function eligibleStudents($topic)
    {
        // dd($topic->id);

        // dd($topic->prerequisites->pluck('id')->toArray());

        // dd($topic->studentAssessments->pluck('student_id')->toArray());

        $eligibleStudents = [];

        $shortlistedStudents = [];


        // Find students who are already assessed

        $shortlistedStudents[] =
                        $topic->studentAssessments->pluck('student_id')->toArray();

        foreach($topic->prerequisites as $prerequisite)
        {
            $shortlistedStudents[] =
                        $prerequisite->studentAssessments->pluck('student_id')->toArray();
        }

        if(count($shortlistedStudents) == 1)
        {
            return Student::get();
        }

        // dd($shortlistedStudents);

        // dd($shortlistedStudents);

        //Intersect multiple arrays

        for ($i=0; $i < (count($shortlistedStudents)-1) ; $i++) { 
        
            if($i == 0)
            {
               $eligibleStudents = array_intersect($shortlistedStudents[$i], $shortlistedStudents[$i+1]); 
            }
            else
            {
                $eligibleStudents = array_intersect($eligibleStudents, $shortlistedStudents[$i+1]);
            }
            
        }

        array_unique($eligibleStudents);

        // dd($topic->studentAssessments->pluck('student_id')->toArray());

        return Student::whereIn('id',$eligibleStudents)->get();
    }

    public static function isAvailable($ahamClass, $student)
    {   
        $classes = $student->classes->pluck('class_id')->toArray();
        
        $classTimings = $ahamClass->timings;

        foreach($classTimings as $classTiming)
        {
            $slot = $classTiming->slot;

            $busyTimings = ClassTiming::whereIn('class_id',$classes)
                                ->where('date',$classTiming->date)
                                ->whereHas('ahamClass',function($query){
                                    $query->where('status','<>','cancelled');
                                })
                                ->where(function ($query) use($slot) {
                                    $query->whereBetween('start_time', [$slot->start_time,$slot->end_time])
                                          ->orWhereBetween('end_time', [$slot->start_time,$slot->end_time]);
                                })
                                ->first();

            if($busyTimings)
            {
                return false;
            }

        }

        if($student->user->teacher)
        {
            $classes = $student->user->teacher->classes->pluck('id')->toArray();

            foreach($classTimings as $classTiming)
            {
                $slot = $classTiming->slot;

                $busyTimings = ClassTiming::whereIn('class_id',$classes)
                                    ->where('date',$classTiming->date)
                                    ->where(function ($query) use($slot) {
                                        $query->whereBetween('start_time', [$slot->start_time,$slot->end_time])
                                              ->orWhereBetween('end_time', [$slot->start_time,$slot->end_time]);
                                    })
                                    ->first();

                if($busyTimings)
                {
                    return false;
                }

            }
        }

        return true;
    }

    public static function isAssessed($topic, $student)
    {
        $eligible = $student->assessments()->where('topic_id',$topic->id)->count();

        return $eligible;
    }

    public static function isEligible($topic, $student)
    {
        $eligible = false;

        $eligible = $student->assessments()->where('topic_id',$topic->id)->count();

        if($eligible)
        {
            return true;
        }

        $prerequisiteAssessments = [];

        foreach($topic->prerequisites as $prerequisite)
        {
            $prerequisiteAssessments[] =
                        $student->assessments()->where('topic_id',$prerequisite->id)->count();
        }

        return !in_array(0, $prerequisiteAssessments);

    }

    public static function getEligibilityStatus($course)
    {

        $eligibility['result'] = true;
        $eligibility['reason'] = 'good_to_go'; // Join Aham

        // Guest User

        if(!Sentinel::check())
        {
            $eligibility['result'] = false;
            $eligibility['reason'] = 'guest'; // Join Aham
        }
        else
        {
            $user = Sentinel::getUser();

            $classes = $course->classes()
                                ->whereIn('status',['open_for_enrollment','scheduled','in_session'])
                                ->get();

            if(!$classes->count())
            {
                $eligibility['result'] = false;
                $eligibility['reason'] = 'no_classes'; // Apply for Assessment

                return $eligibility;
            }

            // User is not a Student
            if(!$user->student)
            {
                $eligibility['result'] = false;
                $eligibility['reason'] = 'not_a_student'; // Apply for Assessment

                return $eligibility;
            }

            // Student doesn't have enough credits
            if($user->student->credits < $course->units->count())
            {
                $eligibility['result'] = false;
                $eligibility['reason'] = 'no_credits'; // Buy Credits

                return $eligibility;
            }

            // dd($classes->pluck('id')->toArray());

            // Student is not meeting Prerequisite Criteria
            if(!static::isEligible($course, $user->student))
            {
                $eligibility['result'] = false;
                $eligibility['reason'] = 'prerequisite_fail'; // Not Eligible

                return $eligibility;
            }

            // Student is already enrolled into a different $classes

            $enrolled = $user->student->enrollments()->whereIn('class_id',$classes->pluck('id')->toArray())->count();

            if($enrolled)
            {
                $eligibility['result'] = false;
                $eligibility['reason'] = 'finish_first'; // Finish that class first
            }

            // // Student took this topic course something back in past

            // $eligibility['result'] = true;
            // $eligibility['reason'] = 'already_took'; // You already finished this class
            
        }


        return $eligibility;
    }

}
