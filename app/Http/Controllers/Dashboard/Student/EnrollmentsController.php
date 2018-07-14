<?php

namespace Aham\Http\Controllers\Dashboard\Student;

use Validator;
use Input;

use Aham\Models\SQL\AhamClass;

use Aham\Managers\EnrollmentManager;

class EnrollmentsController extends StudentDashboardBaseController
{
    public function __construct()
    {
        parent::__construct();
    }

    public function create($id)
    {
        $class = AhamClass::find($id);

        view()->share('class', $class);

        $manager = new EnrollmentManager($class, $this->student);

        // 1. Check availability

        if ($manager->alreadyEnrolled()) {
            dd('Already enrolled to this class');
        }

        if ($manager->pendingClass()) {
            return view('dashboard.student.enrollments.finish_pending');
        }

        // - Maximum enrollment reached?
        // Cant enroll
        if (!$manager->hasAvailability()) {
            dd('Maximum enrollment reached');
        }

        // - Student is busy in another class
        
        // Cant enroll

        if (!$manager->checkAvailability()) {
            return view('dashboard.student.enrollments.not_available');
        }

        // 2. Already enrolled into same course, which is in progress

        // Can't enroll

        // 3. Already assessed for this course

        // Can enroll, show message

        // 4. Already finished this course in Aham

        // Can enroll, show message

        // 5. Check eligibility

        if ($class->free) {
            return view('dashboard.student.enrollments.free_enroll', compact('class'));
        }

        // If has enough credits, fine.

        if (!$manager->hasEnoughCredits($class)) {
            return view('dashboard.student.enrollments.no_credits', compact('class'));
        }

        // If yes, proceed

        if (!$manager->isEligible()) {
            return view('dashboard.student.enrollments.not_eligible', compact('class'));
        }

        return view('dashboard.student.enrollments.direct_enroll', compact('class'));

        // If free class, fine

        // If not eligible - enable ghost mode - only with enough credits or free class
    }

    public function store($id)
    {
        $class = AhamClass::find($id);

        view()->share('class', $class);

        $manager = new EnrollmentManager($class, $this->student);

        $manager->enroll();

        $redirect = route('student::classes.enrolled');

        if ($class->status == 'scheduled') {
            $redirect = route('student::classes.upcoming');
        }

        return \Response::json(array(
            'success' => true,
            'redirect' => $redirect,
            'errors' => [['Successfully enrolled']]
        ), 200);
    }

    public function enrollAsGhost($id)
    {
        $class = AhamClass::find($id);

        view()->share('class', $class);

        $manager = new EnrollmentManager($class, $this->student);

        $manager->enrollAsGhost();

        $redirect = route('student::classes.enrolled');

        if ($class->status == 'scheduled') {
            $redirect = route('student::classes.upcoming');
        }

        return \Response::json(array(
            'success' => true,
            'redirect' => $redirect,
            'errors' => [['Successfully enrolled']]
        ), 200);
    }


    public function freeEnroll($id)
    {
        $class = AhamClass::find($id);

        view()->share('class', $class);

        $manager = new EnrollmentManager($class, $this->student);

        $manager->freeEnroll();

        $redirect = route('student::classes.enrolled');

        if ($class->status == 'scheduled') {
            $redirect = route('student::classes.upcoming');
        }

        return \Response::json(array(
            'success' => true,
            'redirect' => $redirect,
            'errors' => [['Successfully enrolled']]
        ), 200);
    }

    public function cancelModal($id)
    {
        $class = AhamClass::find($id);

        if ($class->free) {
            dd('Cannot cancel a free class');
        }

        return view('dashboard.student.enrollments.cancel_class', compact('class'));
    }

    public function cancel($id)
    {
        $class = AhamClass::find($id);

        // dd($class);

        $manager = new EnrollmentManager($class, $this->student);

        $manager->cancelEnrollment();

        flash()->success('You have successfully cancelled enrollment to the class');

        return \Response::json(array(
            'success' => true,
            'errors' => [['Successfully cancelled']]
        ), 200);
    }
}
