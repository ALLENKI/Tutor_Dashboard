<?php

namespace Aham\Http\Controllers\Frontend;

use Aham\Jobs\SendActivationEmail;
use Aham\Jobs\SendWelcomeEmail;

use View;
use Sentinel;
use Activation;
use Reminder;
use Validator;
use Input;
use Mail;

use Aham\Models\SQL\User;
use Aham\Models\SQL\Topic;
use Aham\Models\SQL\City;

class RegisterAsStudentController extends BaseController
{

    public function __construct()
    {
        parent::__construct();
    }

    public function get()
    {
        // view()->share('headerClass', 'dark trans');
        
        $cities = City::pluck('name','id')->toArray();

        return View::make('frontend.student.register',compact('cities'));
    }

    /**
     * [postRegister description]
     * @return [type] [description]
     */
    public function post()
    {
        //Form Validation
        $rules = [
            'password' => 'required|confirmed',
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'interested_in' => 'required|in:user,student,teacher,student_teacher',
            'grade' => 'required|in:5-8,9-12,Under_Grad,Grad_or_Higher,Working_Professional,other',
        ];

        $messages = [
            'interested_in.in' => 'Please select what you are interesed in.',
            'grade.in' => 'Please select your grade',
        ];

        $v = Validator::make(Input::all(), $rules, $messages);

        if ($v->fails()) {
            flash()->error('Please check for errors in red.');
            return redirect()->back()->withErrors($v->errors())->withInput();
        }

        $data = Input::only('name','email','password','grade','interested_in','city_id','what_learn');

        $user = Sentinel::register($data);

        $this->dispatch(new SendActivationEmail($user));

        flash()->success('Please check your email and activate. Please check spam/junk folder just in case.');

        return redirect()->route('pages::join-as-a-student');

    }


}
