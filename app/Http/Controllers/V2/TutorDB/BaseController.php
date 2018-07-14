<?php

namespace Aham\Http\Controllers\V2\TutorDB;

use Aham\Http\Controllers\Controller;
use Aham\Http\Requests;
use Illuminate\Http\Request;
use Dingo\Api\Routing\Helpers;

class BaseController extends Controller
{
    use Helpers;

    public function __construct()
    {
    }
}
