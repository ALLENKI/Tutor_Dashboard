<?php

namespace Aham\Http\Controllers\Frontend\AhamClass;

use View;
use Sentinel;
use Activation;
use Reminder;
use Validator;
use Input;
use Mail;
use Carbon;
use DB;

use Aham\Models\SQL\AhamClass;
use Aham\Models\SQL\StudentEnrollment;

use Aham\Http\Controllers\Frontend\BaseController;

class ClassController extends BaseController
{
    public function __construct()
    {
        parent::__construct();
    }

    public function index($code)
    {
        view()->share('bodyClass', 'fullwidth sticky-header  course-single');
    	            
    	$ahamClass = AhamClass::where('code',$code)->first();

    	$course = $ahamClass->topic;

        // foreach($ahamClass->enrollments as $enrollment)
        // {
        //     dd($enrollment->student->user->name);
        // }

        return view('frontend.class.index',compact('ahamClass','course'));
    }

    public function leaveFeedback($code)
    {
        $feedbacks = Input::get('feedback');

        foreach($feedbacks as $feedback)
        {
            $enrollment = StudentEnrollment::find($feedback['enrollment_id']);

            $enrollment->feedback = $feedback['feedback'];
            $enrollment->save();
        }

        return redirect()->back();

    }
}
