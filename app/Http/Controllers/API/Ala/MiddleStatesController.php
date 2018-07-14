<?php

namespace Aham\Http\Controllers\API\Ala;

use Aham\Http\Controllers\Controller;
use Aham\Http\Requests;
use Illuminate\Http\Request;

use League\Fractal;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;
use Dingo\Api\Routing\Helpers;

use League\Fractal\Manager;
use League\Fractal\Serializer\DataArraySerializer;
use League\Fractal\Serializer\ArraySerializer;
use Aham\Transformers\AhamClassTransformer;

use League\Fractal\Pagination\IlluminatePaginatorAdapter;

use Input;
use Validator;
use Carbon;

use Aham\Models\SQL\Location;
use Aham\Models\SQL\AhamClass;
use Aham\Models\SQL\ClassTiming;
use Aham\Models\SQL\ClassUnit;
use Aham\Models\SQL\Topic;
use Aham\Models\SQL\Teacher;
use Aham\Models\SQL\SchedulingRule;

use Aham\Helpers\TeacherHelper;
use Aham\Helpers\StudentHelper;
use Aham\Helpers\ClassStatusHelper;

class MiddleStatesController extends BaseController
{
    public function __construct()
    {
        parent::__construct();
    }

    //Show classes that are in "Initiated" state:

    public function index(Request $request)
    {
        $location = Location::where('slug',$request->get('location'))->first();

        $model = AhamClass::with('topic','teacher.user')->where('location_id',$location->id);

        $status = Input::get('status','all');

        switch ($status) {

            case 'created':
                $model = $model->whereIn('status',['created']);
                break;

            case 'no_invitations':
                $model = $model->doesntHave('invitations')
                                ->where('status','<>','cancelled');
                break;

            case 'has_invitations_no_teacher':
                $model = $model->has('invitations')
                                ->whereNull('teacher_id')
                                ->where('status','<>','cancelled');
                break;

            case 'invited':
                $model = $model->whereIn('status',['invited']);
                break;
            
            case 'min-enrollment-not-met':
                $model = $model->whereIn('status',['open_for_enrollment']);
                break;

            case 'get-feedback':
                $model = $model->whereIn('status',['get_feedback']);
                break;

            default:
                $model = $model;
                break;

        }

        if(Input::has('query')) {
            $query = Input::get('query');
            $model =  $model->
                            whereHas('topic',function($q) use($query){
                                $q->where('name','LIKE','%'.$query.'%');
                            })
                            ->orWhereHas('teacher.user',function($q) use($query){
                                $q->where('name','LIKE','%'.$query.'%');
                            });
        }


        if(Input::has('selected_states'))
        {
            $states = explode(',', Input::get('selected_states'));

            $model = $model->whereIn('status',$states);
        }


       if(Input::has('teacher_id')){
            $model = $model->where('teacher_id','=',Input::get('teacher_id'));
       }


       if(Input::has('topic_id')){
           $model = $model->where('topic_id','=',Input::get('topic_id'));
       }

        switch (Input::get('sort')) {
            case 'start_date_asc':
                $sort = 'start_date';
                $order = 'asc';
                break;

            case 'start_date_desc':
                $sort = 'start_date';
                $order = 'desc';
                break;

            default:
                $sort = 'created_at';
                $order = 'desc';
                break;
        }

        $model = $model->orderBy($sort,$order);

        $paginator = $model->paginate(Input::get('per_page',6));

        $classes = $paginator->getCollection();

        $resource = new Fractal\Resource\Collection($classes, new AhamClassTransformer);
        $paginator->appends($request->only('location','status'));
        $resource->setPaginator(new IlluminatePaginatorAdapter($paginator));

        $manager = new Manager();
        $manager->setSerializer(new ArraySerializer());
        $classes = $manager->createData($resource)->toArray();

        return $classes;

        return $this->response->paginator($classes, new AhamClassTransformer);
    }

    
    // public function show($id)
    // {
    //     $class = AhamClass::find($id);

    //     return $this->response->item($class, new AhamClassTransformer);
    // }

}
