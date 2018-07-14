<?php

namespace Aham\Http\Controllers\Frontend;

use Input;
use Validator;
use Sentinel;

use Aham\Interactions\getOTP;
use Aham\Jobs\SendVerificationSMS;

class OTPController extends BaseController
{
    public function __construct()
    {
        parent::__construct();
    }

    public function get()
    {
        $rules = [
            'mobile' => 'required|numeric|digits_between:10,10|unique:users,mobile',
        ];

        $messages = [
            'mobile.required' => 'Please enter a valid mobile number',
            'mobile.numeric' => 'Please enter a valid mobile number',
            'mobile.digits_between' => 'Please enter a 10 digit mobile number',
            'mobile.unique' => 'This mobile number is already registered',
        ];

        $v = Validator::make(Input::all(), $rules, $messages);

        if ($v->fails()) {

            return \Response::json(array(
                    'success' => false,
                    'errors' => $v->getMessageBag()->toArray()

                ), 400);
        }

        $mobile = Input::get('mobile');

        $this->dispatch(new SendVerificationSMS($mobile));

        return \Response::json('success', 200);
    }

    public function create()
    {
        $rules = [
            'mobile' => 'required|numeric|digits_between:10,10|exists:users,mobile',
        ];

        $messages = [
            'mobile.digits_between' => 'Mobile number should have 10 digits'
        ];

        $v = Validator::make(Input::all(), $rules, $messages);

        if ($v->fails()) {

            return \Response::json(array(
                    'success' => false,
                    'errors' => $v->getMessageBag()->toArray()

                ), 400);
        }

        $mobile = Input::get('mobile');

        $this->dispatch(new SendVerificationSMS($mobile));

        return \Response::json('success', 200);
    }

    public function checkEmailAndGetOTP()
    {
        $rules = [
            'mobile' => 'required|numeric|digits_between:10,10',
        ];

        $messages = [
            'mobile.digits_between' => 'Mobile number should have 10 digits'
        ];

        $v = Validator::make(Input::all(), $rules, $messages);

        if ($v->fails()) {

            return \Response::json(array(
                    'success' => false,
                    'errors' => $v->getMessageBag()->toArray()

                ), 400);
        }

        $credentials = [
            'login' => Input::get('mobile'),
        ];

        $user = Sentinel::findByCredentials($credentials);

        if($user)
        {
            if($user->email)
            {
                return \Response::json(array(
                    'success' => false,
                    'errors' => [['This mobile number is already registered with a different email address.']]

                ), 400);
            }
            else
            {
                $mobile = Input::get('mobile');

                $this->dispatch(new SendVerificationSMS($mobile));

                return \Response::json('success', 200);
            }
        }
        else
        {
            $mobile = Input::get('mobile');

            $this->dispatch(new SendVerificationSMS($mobile));

            return \Response::json('success', 200);
        }

        // 1. Mobile number already exists and doesn't have an email address, so we can basically link email address with user.

        // 2. Mobile number already exists and has email address. So we can't proceed
    }

}
