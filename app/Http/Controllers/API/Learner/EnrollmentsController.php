<?php

namespace Aham\Http\Controllers\API\Learner;

use Aham\Http\Controllers\Controller;
use Aham\Http\Requests;
use Illuminate\Http\Request;
use Aham\Helpers\TeacherHelper;


use League\Fractal\Pagination\IlluminatePaginatorAdapter;

use Input;
use Validator;

use Aham\Managers\StudentClassesManager;

use Aham\Transformers\AhamClassTransformer;

use Aham\Models\SQL\ClassInvitation;

use Aham\Models\SQL\AhamClass;
use Aham\Models\SQL\User;

use Aham\Models\SQL\FbChat;
use Aham\Models\SQL\FbUser;
use Aham\Models\SQL\FbParticipant;
use Aham\Models\SQL\Student;
use Aham\Models\SQL\StudentEnrollmentUnit;

use Aham\Managers\EnrollmentManager;

use Aham\Helpers\FBHelper;

class EnrollmentsController extends BaseController
{
    public function __construct()
    {
        parent::__construct();
    }

    public function getEnroll($id)
    {
        $user = $this->auth->user();

        $student = $user->student;

        $class = AhamClass::find($id);

        $manager = new EnrollmentManager($class, $student);

        if ($manager->alreadyEnrolled()) {
            return $this->response->withArray([
                'result'=>'error',
                'can_enroll' => false,
                'messages' => 'Already Enrolled'
            ])->setStatusCode(200);
        }

        if ($manager->pendingClass()) {
            return $this->response->withArray([
                'result'=>'error',
                'can_enroll' => false,
                'messages' => 'First Finish the pending class'
            ])->setStatusCode(200);
        }

        if (!$manager->hasAvailability()) {
            return $this->response->withArray([
                'result'=>'error',
                'can_enroll' => false,
                'messages' => 'Maximum enrollment reached'
            ])->setStatusCode(200);
        }

        if (!$manager->checkAvailability()) {
            return $this->response->withArray([
                'result'=>'error',
                'can_enroll' => false,
                'messages' => 'Maximum enrollment reached'
            ])->setStatusCode(200);
        }

        if ($class->free) {
            return $this->response->withArray([
                'result'=>'success',
                'can_enroll' => true,
                'messages' => 'This class is free to enroll'
            ])->setStatusCode(200);
        }

        // If has enough credits, fine.

        if (!$manager->hasEnoughCredits($class)) {
            return $this->response->withArray([
                'result'=>'error',
                'can_enroll' => false,
                'messages' => "You don't have enough credits"
            ])->setStatusCode(200);
        }

        if (!$manager->isEligible()) {
            return $this->response->withArray([
                'result'=>'error',
                'can_enroll' => true,
                'messages' => "You are not eligible to take this class"
            ])->setStatusCode(200);
        }

        return $this->response->withArray([
            'result'=>'success',
            'can_enroll' => true,
            'messages' => "Proceed to enroll"
        ])->setStatusCode(200);
    }

    public function postEnroll($id)
    {
        $class = AhamClass::find($id);

        $user = $this->auth->user();

        $student = $user->student;

        $manager = new EnrollmentManager($class, $student);

        if ($manager->alreadyEnrolled()) {
            return $this->response->withArray([
                'result'=>'error',
                'enrolled' => false,
                'messages' => 'Already Enrolled'
            ])->setStatusCode(200);
        }

        if ($manager->pendingClass()) {
            return $this->response->withArray([
                'result'=>'error',
                'enrolled' => false,
                'messages' => 'First Finish the pending class'
            ])->setStatusCode(200);
        }

        if (!$manager->hasAvailability()) {
            return $this->response->withArray([
                'result'=>'error',
                'enrolled' => false,
                'messages' => 'Maximum enrollment reached'
            ])->setStatusCode(200);
        }

        if (!$manager->checkAvailability()) {
            return $this->response->withArray([
                'result'=>'error',
                'enrolled' => false,
                'messages' => 'Maximum enrollment reached'
            ])->setStatusCode(200);
        }

        if ($class->free) {
            $manager->freeEnroll();

            return $this->response->withArray(array(
                'success' => true,
                'enrolled' => true,
                'messages' => 'Successfully enrolled'
            ), 200);
        }

        if (!$manager->hasEnoughCredits($class)) {
            return $this->response->withArray([
                'result'=>'error',
                'enrolled' => false,
                'messages' => "You don't have enough credits"
            ])->setStatusCode(200);
        }

        if (!$manager->isEligible()) {
            $manager->enrollAsGhost();

            return $this->response->withArray(array(
                'success' => true,
                'enrolled' => true,
                'messages' => 'Successfully enrolled'
            ), 200);
        }

        $manager->enroll();

        return $this->response->withArray(array(
            'success' => true,
            'enrolled' => true,
            'messages' => 'Successfully enrolled'
        ), 200);
    }


    public function cancelUnitEnrollment($unitId)
    {
        $studentEnrollmentUnit = StudentEnrollmentUnit::find($unitId);
        $class = $studentEnrollmentUnit->ahamClass;
        $student = Student::find($studentEnrollmentUnit->student_id);
        $manager = new EnrollmentManager($class, $student);
        $manager->cancelUnitEnrollment($unitId,'cancelled_by_student');


        return $this->response->withArray(array(
            'success' => true,
            'cancelled' => true,
            'messages' => 'Successfully cancelled'
        ), 200);
    }
}
