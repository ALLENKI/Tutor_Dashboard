<?php

namespace Aham\Http\Controllers\Backend\ClassesMgmt;

use Illuminate\Http\Request;

use Aham\Http\Requests;
use Aham\Http\Controllers\Controller;

use Aham\Models\SQL\AhamClass;
use Aham\Models\SQL\Topic;
use Aham\Models\SQL\Location;
use Aham\Models\SQL\ClassTiming;
use Aham\Models\SQL\Slot;
use Aham\Models\SQL\Student;

use Aham\Interactions\ClassSchedule;
use Aham\Managers\EnrollmentManager;

use Aham\Http\Controllers\Backend\BaseController;
use Input;
use Validator;
use Assets;
use Carbon;
use DB;

class EnrollmentController extends BaseController
{
    public function __construct()
    {
        parent::__construct();
    }

    public function enrollStudent($class, $student)
    {
        $class = AhamClass::find($class);

        $student = Student::find($student);

        $manager = new EnrollmentManager($class, $student);

        if ($manager->alreadyEnrolled()) {
            dd('Already enrolled to this class');
        }

        if ($manager->pendingClass()) {
            return view('backend.classes_mgmt.enrollments.finish_pending', compact('class', 'student'));
        }

        if (!$manager->hasAvailability()) {
            dd('Maximum enrollment reached');
        }

        if (!$manager->checkAvailability()) {
            return view('backend.classes_mgmt.enrollments.not_available', compact('class', 'student'));
        }

        if ($class->free) {
            return view('backend.classes_mgmt.enrollments.free_enroll', compact('class', 'student'));
        }

        // If has enough credits, fine.

        if (!$manager->hasEnoughCredits($class)) {
            return view('backend.classes_mgmt.enrollments.no_credits', compact('class', 'student'));
        }

        // If yes, proceed

        if (!$manager->isEligible()) {
            return view('backend.classes_mgmt.enrollments.not_eligible', compact('class', 'student'));
        }

        return view('backend.classes_mgmt.enrollments.direct_enroll', compact('class', 'student'));
    }

    public function directEnroll($class, $student)
    {
        $class = AhamClass::find($class);

        $student = Student::find($student);

        $manager = new EnrollmentManager($class, $student);

        $manager->enroll();

        return \Response::json(array(
            'success' => true,
            'errors' => [['Successfully enrolled']]
        ), 200);
    }


    public function enrollAsGhost($class, $student)
    {
        $class = AhamClass::find($class);

        $student = Student::find($student);

        $manager = new EnrollmentManager($class, $student);

        $manager->enrollAsGhost();

        return \Response::json(array(
            'success' => true,
            'errors' => [['Successfully enrolled']]
        ), 200);
    }

    public function freeEnroll($class, $student)
    {
        $class = AhamClass::find($class);

        $student = Student::find($student);

        $manager = new EnrollmentManager($class, $student);

        $manager->freeEnroll();

        return \Response::json(array(
            'success' => true,
            'errors' => [['Successfully enrolled']]
        ), 200);
    }


    public function cancelModal($id, $student)
    {
        $class = AhamClass::find($id);

        $student = Student::find($student);

        if ($class->free) {
            dd('Cannot cancel a free class');
        }

        return view('backend.classes_mgmt.enrollments.cancel_class', compact('class', 'student'));
    }

    public function cancel($id, $student)
    {
        $class = AhamClass::find($id);

        $student = Student::find($student);

        // dd($class);

        $manager = new EnrollmentManager($class, $student);

        $manager->cancelEnrollment();

        flash()->success('You have successfully cancelled enrollment to the class');

        return \Response::json(array(
            'success' => true,
            'errors' => [['Successfully cancelled']]
        ), 200);
    }
}
