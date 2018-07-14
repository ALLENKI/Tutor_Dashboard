<?php

namespace Aham\Http\Controllers\API;

use Aham\Http\Controllers\Controller;
use Aham\Http\Requests;
use Illuminate\Http\Request;
use Dingo\Api\Routing\Helpers;

use Aham\Helpers\TeacherHelper;


use League\Fractal\Pagination\IlluminatePaginatorAdapter;

use Input;
use Validator;

use Aham\Managers\TeacherClassesManager;

use Aham\Transformers\TopicTransformer;

use Aham\Models\SQL\ClassInvitation;
use Aham\Models\SQL\Topic;


class TopicsController extends Controller
{
    use Helpers;

    public function show($id)
    {    
        return $this->response->item(Topic::find($id), new TopicTransformer);
    }
    
}
