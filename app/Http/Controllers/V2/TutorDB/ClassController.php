<?php

namespace Aham\Http\Controllers\V2\TutorDB;

use Aham\Http\Controllers\Controller;
use Tymon\JWTAuth\Facades\JWTAuth;
use Cartalyst\Sentinel\Laravel\Facades\Sentinel;
use Aham\Repositories\ClassRepository;
use League\Fractal;
use Aham\Managers\EnrollmentManager;
use Aham\Helpers\PaymentCalculatorHelper;

use Aham\Transformers\AhamClassTransformer;
use League\Fractal\Pagination\IlluminatePaginatorAdapter;

use League\Fractal\Manager;
use League\Fractal\Serializer\DataArraySerializer;
use League\Fractal\Serializer\ArraySerializer;

use Aham\Models\SQL\AhamClass;
use Aham\Models\SQL\Note;
use Aham\Models\SQL\ClassTiming;
use Aham\Models\SQL\Location;
use Aham\Models\SQL\Student;
use Aham\Models\SQL\Teacher;
use Aham\Models\SQL\Topic;
use Aham\Models\SQL\TeacherCertification;
use Aham\Models\SQL\ClassInvitation;
use Aham\Models\SQL\StudentEnrollmentUnit;
use Aham\Models\SQL\StudentEnrollment;
use Aham\Models\SQL\RepeatClass;
use Aham\Models\SQL\StudentAssessment;
use Carbon;
use Input;
use Aham\Helpers\TeacherHelper;
use Aham\Transformers\NoteTransformer;
use Aham\Models\SQL\StudentInvitation;
use Aham\Managers\ClassStatusManager;


class ClassController extends BaseController
{
    private $ahamClass;

    public function __construct(ClassRepository $ahamClass)
    {
        $this->ahamClass = $ahamClass;
    }


    public function enrollments($id)
    {
        $ahamClass = $this->ahamClass
                            ->makeModel()
                            ->with('classUnits.enrollments.learner.user')
                            ->find($id);


        $students = Student::with('user.creditBuckets')
                            ->where('active', true)
                            ->get();

        $allStudents = [];

       
        foreach ($students as $student) {
            $allStudents[] = [
                'id' => $student->id,
                'name' => $student->user->name,
                'email' => $student->user->email,
                'text' => $student->user->name.' ('.$student->user->email.')'.' Credits:'.$student->user->creditBuckets()->sum('total_remaining')
            ];
        }////

        $students = Student::with('user.creditBuckets')
                            ->where('active', true)
                            ->whereNotIn('id', $ahamClass->enrollments->pluck('student_id')->toArray())
                            /*->whereHas('hubs',function($query) use ($ahamClass){
                                $query->whereIn('id',[$ahamClass->location->id]);
                            })*/
                            ->get();


        $eligibleStudents = [];

        $students = $this->removeAllocatedTutorFromStudent($ahamClass, $students);                            

        foreach ($students as $student) {
            $eligibleStudents[] = [
                'id' => $student->id,
                'name' => $student->user->name,
                'email' => $student->user->email,
                'text' => $student->user->name.' ('.$student->user->email.')'.' Credits:'.$student->user->creditBuckets()->sum('total_remaining')
            ];
        }
        $enrolledStudents = [];

        foreach ($ahamClass->allEnrollments as $enrollment) {
            $student = $enrollment->student;
            
            $user = $student->user;

            $enrolledStudents[] = [
                'code' => $student->code,
                'id' => $student->id,
                'enrolled_id' => $enrollment->id,
                'class_id' => $ahamClass->id,
                //'unit_id' => $ahamClass->classUnits->pluck('id')->toArray(),
                'name' => $student->user->name,
                'email' => $student->user->email,
                'enrolled_on' => $enrollment->created_at->format('jS M Y H:i A'),
                'ghost' => $enrollment->ghost,
                'status' => $enrollment->status,
                'cancelled' => $enrollment->cancelled,
                'feedback' => $enrollment->present()->typeFeedback,
                'feedbacks' => $enrollment->feedback,
                'remarks' => $enrollment->remarks
            ];
        }


        return [
            'eligibleStudents' => $eligibleStudents,
            'enrolledStudents' => $enrolledStudents,
            'allStudents' => $allStudents,
            'classUnits' => $ahamClass->classUnits
        ];
    }

    /*public function cancelClassEnrollment($classId, $learnerId)
    {
        $class = AhamClass::find($classId);

        $student = Student::find($learnerId);

        $manager = new EnrollmentManager($class, $student);

        $manager->cancelEnrollment('cancelled_by_admin');

        return \Response::json(array(
            'success' => true,
            'errors' => [['Successfully cancelled (enrollment) ']]
        ), 200);

    }*/

    public function cancelUnitEnrollment($unitId, $learnerId)
    {
        $studentEnrollmentUnit = StudentEnrollmentUnit::find($unitId);

        $class = $studentEnrollmentUnit->ahamClass;

        $student = Student::find($learnerId);

        $manager = new EnrollmentManager($class, $student);

        $manager->cancelUnitEnrollment($unitId,'cancelled_by_tutor');

        return \Response::json(array(
            'success' => true,
            'errors' => [['Successfully cancelled (enrollment) ']]
        ), 200);

    }
   /* public function attendance($unitId, $learnerId)
    {
        $studentEnrollmentUnit = StudentEnrollmentUnit::find($unitId);

        $class = $studentEnrollmentUnit->ahamClass;

        $student = Student::find($learnerId);

        //$manager = new EnrollmentManager($class, $student);

        $manager->attendancemanager($unitId,1);

        return \Response::json(array(
            'success' => true,
            'errors' => [['Successfully marked (attendance) ']]
        ), 200);

    }*/
     public function attended($unitId, $learnerId)
    {

        $studentEnrollmentUnit = StudentEnrollmentUnit::find($unitId);
        $learner = $studentEnrollmentUnit->learner;
        $user = $learner->user;
        $ahamClass = $studentEnrollmentUnit->ahamClass;
        $studentEnrollmentUnit->attendance = '1';
        $studentEnrollmentUnit->save();

        return \Response::json(array(
            'success' => true,
            'errors' => [['Successfully marked (attendance) ']]
        ), 200);
    }

    
    public function notattended($unitId, $learnerId)
    {

        $studentEnrollmentUnit = StudentEnrollmentUnit::find($unitId);
        $learner = $studentEnrollmentUnit->learner;
        $user = $learner->user;
        $ahamClass = $studentEnrollmentUnit->ahamClass;
        $studentEnrollmentUnit->attendance = '0';
        $studentEnrollmentUnit->save();

        return \Response::json(array(
            'success' => true,
            'errors' => [['Successfully marked (attendance) ']]
        ), 200);
    }


    public function enroll($classId, $learnerId)
    {
        $class = AhamClass::find($classId);

        $student = Student::find($learnerId);

        $manager = new EnrollmentManager($class, $student);

        if ($class->free) {
            $manager->freeEnroll();

            return \Response::json(array(
                'success' => true,
                'errors' => [['Successfully enrolled (free) ']]
            ), 200);
        }

        if (!$manager->isEligible()) {
            $manager->enrollAsGhost();

            return \Response::json(array(
                'success' => true,
                'errors' => [['Successfully enrolled (ghost) ']]
            ), 200);
        }

        $manager->enroll();

        return \Response::json(array(
            'success' => true,
            'errors' => [['Successfully enrolled (direct) ']]
        ), 200);
    }

    public function checkEligibility($classId, $learnerId)
    {
        $class = AhamClass::find($classId);

        $student = Student::find($learnerId);

        $manager = new EnrollmentManager($class, $student);

        if ($manager->alreadyEnrolled()) {
            return [
                'can_enroll' => false,
                'can_force' => true,
                'reason' => "Already enrolled to this class"
            ];
        }

        if ($manager->pendingClass()) {
            return [
                'can_enroll' => false,
                'can_force' => false,
                'reason' => "You have already enrolled to the topic, please finish that first to enroll again."
            ];
        }

        if (!$manager->hasAvailability()) {
            return [
                'can_enroll' => false,
                'can_force' => true,
                'reason' => "Maximum enrollment reached"
            ];
        }

        if (!$manager->checkAvailability()) {
            return [
                'can_enroll' => false,
                'can_force' => false,
                'reason' => "Student is busy in these timings in some other class"
            ];
        }

        if ($class->free) {
            return [
                'can_enroll' => true,
                'reason' => "Proceed to Enroll - Free"
            ];
        }


        if (!$manager->hasEnoughCredits($class)) {
            return [
                'can_enroll' => false,
                'can_force' => false,
                'reason' => "You don't have enough credits"
            ];
        }

        if (!$manager->isEligible()) {
            return [
                'can_enroll' => false,
                'can_force' => true,
                'reason' => "You are not eligible to enroll"
            ];
        }

        return [
            'can_enroll' => true,
            'can_force' => false,
            'reason' => "Proceed to Enroll"
        ];
    }
    public function removeAllocatedTutorFromStudent($ahamClass, $students)
    {
        $classTeachers = ClassTiming::with('teacher.user')
                                    ->where('class_id',$ahamClass->id)
                                    ->where('status','pending')
                                    ->WhereNotNull('teacher_id')
                                    ->groupBy('teacher_id')
                                    ->get();

        //remove the tutor is alloctated to current class 
        if(count($classTeachers)>0)
        {
            foreach ($students as $key => $student)
            {
               foreach ($classTeachers as $classTeacher)
                {
                    $teacher=$classTeacher->teacher;
                    if(!is_null($teacher->user->id))
                    {
                        if($student->user->id==$teacher->user->id)
                        {
                            unset($students[$key]);
                        }
                    }
                }
            }
        }

        return $students;
    } 
       
   

    


}
