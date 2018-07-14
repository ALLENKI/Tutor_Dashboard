<?php

namespace Aham\Http\Controllers\Frontend;

use Aham\Jobs\SendContactRequestMail;

use Validator;
use Input;


class ContactRequestController extends BaseController
{
    public function __construct()
    {
        parent::__construct();
    }

    public function create()
    {
        
        return view('frontend.home.contact');
    }

    public function post()
    {

        $name = Input::get('name');
        $email = Input::get('email');
        $select_option = Input::get('select_option');
        $message = Input::get('message');

        flash()->success('Message sent successfully.');

        $this->dispatch( new SendContactRequestMail($name, $email, $select_option, $message) );

        return redirect()->back();
    }

}