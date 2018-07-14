<?php

namespace Aham\Http\Controllers\Dashboard\Teacher;

use Aham\Http\Controllers\Dashboard\BaseController;

use Sentinel;
use Request;

class TeacherDashboardBaseController extends BaseController
{
	public $teacher;

    public function __construct()
    {
        parent::__construct();

        $teacher = Sentinel::getUser()->teacher; 

        if(!$teacher)
        {
        	abort(404);
        }

        if(!$teacher->active)
        {
            abort(404);
        }

       // $teacher = Sentinel::getUser()->teacher;

        view()->share('teacher', $teacher);

        $this->teacher = $teacher;
    }
}
