<?php

namespace Aham\Http\Controllers\API\Ala;

use Aham\Http\Controllers\Controller;
use Aham\Http\Requests;
use Illuminate\Http\Request;

use League\Fractal;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;
use Dingo\Api\Routing\Helpers;

use League\Fractal\Manager;
use League\Fractal\Serializer\DataArraySerializer;
use League\Fractal\Serializer\ArraySerializer;
use Aham\Transformers\AhamClassTransformer;
use Aham\Managers\EnrollmentManager;

use League\Fractal\Pagination\IlluminatePaginatorAdapter;

use Input;
use Validator;
use Carbon;

use Aham\Models\SQL\Location;
use Aham\Models\SQL\AhamClass;
use Aham\Models\SQL\ClassTiming;
use Aham\Models\SQL\ClassUnit;
use Aham\Models\SQL\Topic;
use Aham\Models\SQL\SchedulingRule;
use Aham\Models\SQL\TeacherCertification;
use Aham\Models\SQL\Teacher;
use Aham\Models\SQL\ClassInvitation;
use Aham\Models\SQL\Student;

use Aham\Helpers\TeacherHelper;
use Aham\Helpers\StudentHelper;
use Aham\Helpers\ClassStatusHelper;

class EnrollmentsController extends BaseController
{
    public function __construct()
    {
        parent::__construct();
    }

    public function getEnrollments(Request $request, $id)
    {
        $ahamClass = AhamClass::find($id);

        $students = Student::where('active', true)->whereNotIn('id', $ahamClass->enrollments->pluck('student_id')->toArray())->get();

        $eligibleStudents = [];

        foreach ($students as $student) {
            $eligibleStudents[] = [
                'id' => $student->id,
                'name' => $student->user->name,
                'email' => $student->user->email,
                'text' => $student->user->name.' ('.$student->user->email.')'.' Credits:'.$student->credits
            ];
        }

        $enrolledStudents = [];

        foreach ($ahamClass->enrollments as $enrollment) {
            $student = $enrollment->student;
            $user = $student->user;

            $enrolledStudents[] = [
                'code' => $student->code,
                'id' => $student->id,
                'name' => $student->user->name,
                'email' => $student->user->email,
                'enrolled_on' => $enrollment->created_at->format('jS M Y H:i A'),
                'ghost' => $enrollment->ghost,
                'feedback' => $enrollment->present()->typeFeedback
            ];
        }

        return [
            'eligibleStudents' => $eligibleStudents,
            'enrolledStudents' => $enrolledStudents,
        ];
    }

    public function checkEligibility(Request $request, $id, $stu)
    {
        $class = AhamClass::find($id);

        $student = Student::find($stu);

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
                'reason' => "Proceed to Enroll"
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

    public function freeEnroll($class, $student)
    {
        $manager = new EnrollmentManager($class, $student);

        $manager->freeEnroll();

        return \Response::json(array(
            'success' => true,
            'errors' => [['Successfully enrolled']]
        ), 200);
    }

    public function enroll(Request $request, $id, $stu)
    {
        $class = AhamClass::find($id);

        $student = Student::find($stu);

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

    public function cancelEnrollment(Request $request, $id, $stu)
    {
        $class = AhamClass::find($id);

        $student = Student::find($stu);

        $manager = new EnrollmentManager($class, $student);

        $manager->cancelEnrollment();

        return \Response::json(array(
            'success' => true,
            'errors' => [['Successfully cancelled (enrollment) ']]
        ), 200);
    }
}
