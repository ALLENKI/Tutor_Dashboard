<?php

namespace Aham\Http\Controllers\Frontend;

use Aham\Http\Controllers\Controller;

use View;
use Reminder;
use Validator;
use Input;


use Aham\Services\Storage\CDNInterface;


use Aham\Models\SQL\User;
use Aham\Models\SQL\BetApplicant;


class RegisterForBetController extends BaseController
{

    public function __construct(CDNInterface $cdn)
    {
        parent::__construct();

        $this->cdn = $cdn;
    }

    /**
     * [postRegister description]
     * @return [type] [description]
     */
    public function post()
    {

        $data = Input::only('full_name','age','school','email','mobile','address','other_programs','programming_exp','business_vertical','summer_exp','fav_books','challenge');

        // Take data from form 

        BetApplicant::create($data);

        // Save to database

        return redirect()->route('bet');

    }


}
