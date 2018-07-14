<?php

namespace Aham\Http\Controllers\Frontend\StudentDashboard;

use Aham\Models\SQL\StudentEnrollment;

class EnrollmentController extends StudentDashboardBaseController
{

    public function __construct()
    {
        parent::__construct();
    }

    public function enroll($username, $class)
    {
    	StudentEnrollment::firstOrCreate([
    		'class_id' => $class,
    		'student_id' => $this->student->id
    	]);

    	return redirect()->back();
    }

}