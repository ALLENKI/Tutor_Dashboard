<?php

namespace Aham\Http\Controllers\Frontend;

use View;

use Aham\Models\SQL\Topic;          

class TeacherController extends BaseController
{
    public function __construct()
    {
        parent::__construct();
    }

    public function profile()
    {
        return view('frontend.teacher_dashboard.profile');
    }

     public function teaching()
    {
        return view('frontend.teacher_dashboard.teaching');
    }

    public function assessment()
    {
        return view('frontend.teacher_dashboard.certification');
    }

    public function payment()
    {
        return view('frontend.teacher_dashboard.payment');
    }
}
