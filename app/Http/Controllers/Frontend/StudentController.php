<?php

namespace Aham\Http\Controllers\Frontend;

use View;

use Aham\Models\SQL\Topic;          

class StudentController extends BaseController
{
    public function __construct()
    {
        parent::__construct();
    }

    public function profile()
    {
        return view('frontend.student_dashboard.profile');
    }

     public function learning()
    {
        return view('frontend.student_dashboard.learning');
    }

    public function assessment()
    {
        return view('frontend.student_dashboard.assessment');
    }

    public function payment()
    {
        return view('frontend.student_dashboard.payment');
    }
}
