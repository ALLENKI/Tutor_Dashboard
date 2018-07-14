<?php

namespace Aham\Http\Controllers\Frontend\Content;

use View;
use Sentinel;
use Activation;
use Reminder;
use Validator;
use Input;
use Mail;
use Carbon;
use DB;
use Assets;

use Aham\Models\SQL\Page;
use Aham\Models\SQL\User;
use Aham\Models\SQL\Topic;

use Aham\Http\Controllers\Frontend\BaseController;

class TutorsController extends BaseController
{
    public function __construct()
    {
        parent::__construct();
    }

    public function index()
    {

    }


    public function show($slug)
    {
        $user = User::where('username',$slug)->first();

        $tutor = $user->teacher;

        // dd($tutor);

        $topics = Topic::ofType('subject')->pluck('name','name')->toArray();

        $public_profile = json_decode($user->public_profile,true);

        $eligibleSubjects = explode(',',$tutor->eligible_subjects);

        return view('frontend.content.tutors.show',compact('tutor','eligibleSubjects','public_profile','user'));
    }


}
