<?php

namespace Aham\Http\Controllers\Frontend\StudentDashboard;

use Aham\Http\Controllers\Frontend\BaseController;

use Sentinel;
use Request;

class StudentDashboardBaseController extends BaseController
{

	public $student;

    public function __construct()
    {
        parent::__construct();

        $usernameInUrl = Request::segment(2);

        $student = Sentinel::getUser()->student;

        if($usernameInUrl != $student->user->username && !Sentinel::getUser()->hasAccess('admin'))
        {
        	abort(404);
        }

        $student = Sentinel::getUser()->student;

        view()->share('student', $student);

        $this->student = $student;
    }

}