<?php

namespace Aham\Http\Controllers\V2;

use Aham\Http\Controllers\Controller;
use Dingo\Api\Routing\Helpers;
use Aham\Transformers\UserTransformer;

class AccountController extends Controller
{
    use Helpers;

    public function account()
    {
        $user = $this->auth->user();
        return $this->response->item($user, new UserTransformer);
    }
}
