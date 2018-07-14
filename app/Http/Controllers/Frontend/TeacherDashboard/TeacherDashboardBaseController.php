<?php

namespace Aham\Http\Controllers\Frontend\TeacherDashboard;

use Aham\Http\Controllers\Frontend\BaseController;

use Sentinel;
use Request;

use Aham\Models\SQL\Topic;

class TeacherDashboardBaseController extends BaseController
{

	public $teacher;

    public function __construct()
    {
        parent::__construct();

        $usernameInUrl = Request::segment(2);

        $teacher = Sentinel::getUser()->teacher;

        if($usernameInUrl != $teacher->user->username)
        {
        	abort(404);
        }

        view()->share('teacher', $teacher);

        $this->teacher = $teacher;

        $topics = Topic::topic()->active()->pluck('id')->toArray();

        $certified = $teacher->certifications()->pluck('topic_id')->toArray();

    }

}