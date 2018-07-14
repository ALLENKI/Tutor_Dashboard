<?php

namespace App\Http\Controllers\Backend\Content;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Page;
use App\MobileOtp;

use App\Http\Controllers\Backend\BaseController;

use Input;
use Validator;




class OTPsController extends BaseController
{

    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $mobileOtps = MobileOtp::all();

        return view('backend.content.otps.index',compact('mobileOtps'));
    }

}
