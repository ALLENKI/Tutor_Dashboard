<?php

namespace Aham\Http\Controllers\Dashboard\Teacher;

use Aham\Models\SQL\Topic;

use Aham\Services\Storage\CDNInterface;

use Input;
use Validator;
use Sentinel;

use Aham\Models\SQL\City;


class SettingsController extends TeacherDashboardBaseController
{
    public function __construct(CDNInterface $cdn)
    {
        parent::__construct();

        $this->cdn = $cdn;
    }


    public function getProfile()
    {
        $topics = Topic::ofType('subject')->pluck('name','name')->toArray();

        $cities = City::pluck('name','id')->toArray();

    	return view('dashboard.teacher.home.profile',compact('topics','cities'));
    }

    public function updateProfile()
    {

        $rules = [
            'name' => 'required',
            'resume' => 'max:2000|mimes:pdf,doc,docx',
            'aadhar_card' => 'max:2000|mimes:jpeg,pdf,doc,docx',
            'pan_card' => 'max:2000|mimes:jpeg,pdf,doc,docx',
            'Form_16' => 'max:2000|mimes:jpeg,pdf,doc,docx',
            'cheque' => 'max:2000|mimes:jpeg,png,pdf',
            'current_profession' => 'in:Teaching,Research,Technology,Business,Other',
        ];

        $messages = [
        ];

        $v = Validator::make(Input::all(), $rules, $messages);

        if ($v->fails()) {
            flash()->error('Please check for errors in red.');
            return redirect()->back()->withErrors($v->errors())->withInput();
        }

        $user = Sentinel::getUser();

        $data = Input::only('name','interested_in','current_profession','why_teacher','linkedin','city_id');

        $data['interested_subjects'] = implode(',',Input::get('interested_subjects',[]));

        $user->fill($data);

        $user->save();

        if(Input::file('resume'))
        {
            $this->uploadResume($user,Input::file('resume'),'resume_file');
        }

        if(Input::file('aadhar_card'))
        {
            $this->uploadResume($user,Input::file('aadhar_card'),'Aadhar_card');
        }

        if(Input::file('pan_card'))
        {
            $this->uploadResume($user,Input::file('pan_card'),'pan_card');
        }

        if(Input::file('Form_16'))
        {
            $this->uploadResume($user,Input::file('Form_16'),'form_16');
        }

        if(Input::file('cheque'))
        {
            $this->uploadResume($user,Input::file('cheque'),'cheque');
        }

        flash()->success('Updated Successfully!');

        return redirect()->back();

    }

    public function uploadResume($user,$fileInputData,$metaData)
    {
        $formFile = $fileInputData;
        $extension = $formFile->getClientOriginalExtension();
        $filename = $user->username.'-'.time().'.'.$extension;
        $upload_success = $formFile->move(storage_path('uploads'), $filename);

        $data['key'] = 'user/'.$metaData.'/'.$filename;
        $data['source'] = storage_path('uploads/'.$filename);


        $result = $this->cdn->upload($data);

        $url = $result['url'];


        $user->$metaData = $url;
        $user->save();

        //dd($metaData,$filename,$fileInputData,$data,$user->$metaData,$url);

        \File::delete(storage_path('uploads/'.$filename));

        return true;
    }

    public function getPassword()
    {
        return view('dashboard.teacher.home.password');
    }

    public function updatePassword()
    {
        $rules = [
            'old_password' => 'required',
            'password' => 'required|confirmed',
        ];

        $v = Validator::make(Input::all(), $rules);

        if ($v->fails()) {
            flash()->error('Please check for errors in red.');
            return redirect()->back()->withErrors($v->errors())->withInput();
        }

        $user = Sentinel::getUser();

        $credentials = [
            'email'    => $user->email,
            'password' => Input::get('old_password'),
        ];

        $validate = Sentinel::validateCredentials($user, $credentials);

        if(!$validate)
        {
            flash()->error('Old password does not match');
            return redirect()->back();
        }

        $credentials = [
            'password' => Input::get('password'),
        ];

        $user = Sentinel::update($user, $credentials);

        flash()->overlay('Successfully changed','Success');

        return redirect()->back();
    }


    public function getMobile()
    {
        return view('dashboard.teacher.home.mobile');
    }

    public function updateMobile()
    {
        $user = Sentinel::getUser();

        $rules = [
            'mobile' => 'required|numeric|digits_between:10,10|unique:users,mobile,'.$user->id,
            'otp' => 'required|exists:mobile_otps,otp,mobile,'.Input::get('mobile'),
        ];

        $messages = [
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

        $user = Sentinel::getUser();

        $user->mobile = Input::get('mobile');
        $user->save();

        flash()->success('Successfully Updated');

        return redirect()->back();
    }

    /*** Public Profile ***/

    public function getPublicProfile()
    {
        $topics = Topic::ofType('subject')->pluck('name','name')->toArray();

        $user = Sentinel::getUser();

        $public_profile = json_decode($user->public_profile,true);

        return view('dashboard.teacher.home.public_profile',compact('topics','public_profile'));
    }

    public function updatePublicProfile()
    {
        $public_profile = Input::only('name','tagline','bio','education','experience','research','linkedin','facebook','twitter');

        $user = Sentinel::getUser();

        // dd($public_profile);

        $data['public_profile'] = json_encode($public_profile);

        $data['interested_subjects'] = implode(',',Input::get('interested_subjects',[]));

        $user->fill($data);

        $user->save();

        flash()->success('Profile updated successfully.');

        return redirect()->back();
    }
}
