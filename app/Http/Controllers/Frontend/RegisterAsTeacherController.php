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

use Aham\Services\Storage\CDNInterface;


use Aham\Helpers\SMSHelper;

use Aham\Models\SQL\User;
use Aham\Models\SQL\Topic;

class RegisterAsTeacherController extends BaseController
{

    public function __construct(CDNInterface $cdn)
    {
        parent::__construct();

        $this->cdn = $cdn;
    }

    public function get()
    {
        // view()->share('headerClass', 'dark trans');

        $topics = Topic::ofType('subject')->pluck('name','name')->toArray();
        
        return View::make('frontend.teacher.register',compact('topics'));
    }

    /**
     * [postRegister description]
     * @return [type] [description]
     */
    public function post()
    {
        //Form Validation

        $rules = [
            'mobile' => 'required|numeric|digits_between:10,10|unique:users,mobile',
            'otp' => 'required|exists:mobile_otps,otp,mobile,'.Input::get('mobile'),
            'password' => 'required|confirmed',
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'interested_in' => 'required|in:user,student,teacher,student_teacher',
            'resume' => 'max:2000|mimes:pdf,doc,docx',
            'current_profession' => 'in:Teaching,Research,Technology,Business,Other',
        ];

        $messages = [
            'interested_in.in' => 'Please select what you are interesed in.',
            'otp.exists' => 'Please enter a valid OTP',
            'mobile.required' => 'Please enter a valid mobile number',
            'mobile.numeric' => 'Please enter a valid mobile number',
            'mobile.digits_between' => 'Please enter a valid mobile number',
            'mobile.unique' => 'This mobile number is already registered',
        ];

        $v = Validator::make(Input::all(), $rules, $messages);

        if ($v->fails()) {
            flash()->error('Please check for errors in red.');
            return redirect()->back()->withErrors($v->errors())->withInput();
        }

        $data = Input::only('name','email','password','interested_in','mobile','current_profession','why_teacher','linkedin');

        $data['interested_subjects'] = implode(',',Input::get('interested_subjects',[]));

        $user = Sentinel::register($data);

        if(Input::file('resume'))
        {
            $this->uploadResume($user);
        }

        $this->dispatch(new SendActivationEmail($user));

        flash()->success('Please check your email and activate. Please check spam/junk folder just in case.');

        return redirect()->route('pages::join-as-a-tutor');

    }


    public function uploadResume($user)
    {
        $formFile = Input::file('resume');
        $extension = $formFile->getClientOriginalExtension();
        $filename = $user->username.'-'.time().'.'.$extension;
        $upload_success = $formFile->move(storage_path('uploads'), $filename);

        $data['key'] = 'user/resumes/'.$filename;
        $data['source'] = storage_path('uploads/'.$filename);

        $result = $this->cdn->upload($data);

        $resume = $result['url'];

        $user->resume_file = $resume;
        $user->save();

        \File::delete(storage_path('uploads/'.$filename));

        return true;
    }

}
