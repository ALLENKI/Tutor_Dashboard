<?php

namespace Aham\Http\Controllers\Dashboard\Student;

use Aham\Http\Controllers\Dashboard\BaseController;

use Sentinel;
use Request;
use JWTAuth;
use Aham\Models\SQL\AhamClass;
use Carbon\Carbon;

class StudentDashboardBaseController extends BaseController
{
	public $student;

    public function __construct()
    {
        parent::__construct();

        $user = Sentinel::getUser();

        if(is_null($user)) {
            return redirect('login');
        } else {
           $student =  $user->student;
        }

        if(!$student)
        {
        	abort(404);
        }

        if(!$student->active)
        {
            abort(404);
        }

        $invitations = $student->invitations->pluck('class_id')->toArray();
        $enrollments = $student->enrollments->pluck('class_id')->toArray();
        $invitedClasses = AhamClass::whereIn('status',['open_for_enrollment','scheduled'])
                            ->whereIn('id',$invitations)
                            ->whereNotIn('id',$enrollments)
                            ->whereDate('start_date','>=',Carbon::today())
                            ->paginate(10);


        $student = Sentinel::getUser()->student;
        $token = JWTAuth::fromUser(Sentinel::getUser());

        view()->share('student', $student);
        view()->share('token', $token);
        view()->share('invitedClasses',$invitedClasses);

        $this->student = $student;
    }


}
