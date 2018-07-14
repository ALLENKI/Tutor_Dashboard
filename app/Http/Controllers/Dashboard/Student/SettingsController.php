<?php

namespace Aham\Http\Controllers\Dashboard\Student;

use Aham\Models\SQL\Topic;
use Aham\Models\SQL\Student;

use Aham\Services\Storage\CDNInterface;

use Input;
use Validator;
use Sentinel;
use JWT;


class SettingsController extends StudentDashboardBaseController
{
    public function __construct(CDNInterface $cdn)
    {
        parent::__construct();

        $this->cdn = $cdn;
    }


    public function getProfile()
    {
        $topics = Topic::ofType('subject')->pluck('name','name')->toArray();

    	return view('dashboard.student.home.profile',compact('topics'));
    }

    public function updateProfile()
    {

        $rules = [
            'name' => 'required',
        ];

        $messages = [
        ];

        $v = Validator::make(Input::all(), $rules, $messages);

        if ($v->fails()) {
            flash()->error('Please check for errors in red.');
            return redirect()->back()->withErrors($v->errors())->withInput();
        }

        $user = Sentinel::getUser();

        $data = Input::only('name');

        $user->fill($data);

        $user->save();

        return redirect()->back();

    }


    public function getPassword()
    {
        return view('dashboard.student.home.password');
    }


    public function getManage()
    {

        return view('dashboard.student.home.manage',compact('token'));
    }

    public function updateManage()
    {
        $student = Student::find($id);

        $data = [];
        $data['selected_days_of_week'] = implode(',', Input::get('selected_days_of_week'));
        $data['selected_subjects'] = implode(',', Input::get('selected_subjects'));
        $data['selected_times_text'] = serialize(Input::get('weekly_times_of_day',[]));
        $data['curriculum'] = Input::get('curriculum','');
        $data['school'] = Input::get('school','');

        $student->fill($data);

        $student->save();


        $rules = [

        ];

        //$validation = Validator::make(Input::all(), $rules);


        $user = $student->user;
        $user->name = Input::get('username');
        $user->mobile = Input::get('mobilenumber');
        $user->grade = Input::get('selected_grade');
        $user->save();

        return $this->response->item($student, new StudentTransformer);
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

}
