<?php

namespace Aham\Http\Controllers\API\Tutor;

use Aham\Http\Controllers\Controller;
use Aham\Http\Requests;
use Illuminate\Http\Request;

use Aham\Helpers\TeacherHelper;

use League\Fractal;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;
use Dingo\Api\Routing\Helpers;

use League\Fractal\Manager;
use League\Fractal\Serializer\DataArraySerializer;
use League\Fractal\Serializer\ArraySerializer;

use League\Fractal\Pagination\IlluminatePaginatorAdapter;

use Input;
use Validator;
use Carbon;

use Aham\Managers\TeacherClassesManager;

use Aham\Transformers\TeacherAvailabilityTransformer;
use Aham\Transformers\TeacherTransformer;

use Aham\Models\SQL\ClassInvitation;
use Aham\Models\SQL\TeacherAvailability;

class AvailabilityController extends BaseController
{
    public function __construct()
    {
        parent::__construct();

    }

    public function index()
    {    
        $user = $this->auth->user();

        $teacher = $user->teacher;

        $availabilities = TeacherAvailability::where('teacher_id',$teacher->id)->get();

        return $this->response->collection($availabilities, new TeacherAvailabilityTransformer);
    }

    public function store()
    {    

        $user = $this->auth->user();

        $teacher = $user->teacher;

        $daysOfTheWeek = Input::get('days_of_week');
        $sessions = Input::get('sessions');

        $session_array = [];

        foreach($sessions as $session)
        {
            $session_array[$session] = true;
        }

        foreach($daysOfTheWeek as $dayofTheWeek)
        {
            $data = [];

            $data['teacher_id'] = $teacher->id;
            $data['day_of_the_week'] = $dayofTheWeek;
            $data['from_date'] = Carbon::createFromTimestamp(strtotime(Input::get('from_date')));
            $data['to_date'] = Carbon::createFromTimestamp(strtotime(Input::get('to_date')));

            $data = array_merge($data, $session_array);

            $there = TeacherAvailability::where('teacher_id',$data['teacher_id'])
                      ->where('day_of_the_week',$data['day_of_the_week'])
                      ->where(function($query) use ($data){
                            $query->whereBetween('from_date', [$data['from_date'],$data['to_date']])
                                  ->orWhereBetween('to_date', [$data['from_date'],$data['to_date']]);
                        })
                      ->first();

            
            if(!is_null($there))
            {
                return $this->response->withArray([
                    'result'=>'error',
                    'messages' => 'Availability is already marked for these dates'
                ])->setStatusCode(400);
            }

        }

        foreach($daysOfTheWeek as $dayofTheWeek)
        {
            $data = [];

            $data['teacher_id'] = $teacher->id;
            $data['day_of_the_week'] = $dayofTheWeek;
            $data['from_date'] = Carbon::createFromTimestamp(strtotime(Input::get('from_date')));
            $data['to_date'] = Carbon::createFromTimestamp(strtotime(Input::get('to_date')));

            $data = array_merge($data, $session_array);

            TeacherAvailability::create($data);
        }

        return $this->response->withArray([
            'result'=>'success',
            'messages' => 'Added successfully'
        ])->setStatusCode(200);

    }


    public function delete($id)
    {
        TeacherAvailability::destroy($id);

        return $this->response->withArray([
            'result'=>'success',
            'messages' => 'Deleted successfully'
        ])->setStatusCode(200);
    }


    public function updateIgnoreCalendar()
    {
        $user = $this->auth->user();

        $teacher = $user->teacher;

        if(Input::get('value') == 'yes')
        {
            $teacher->ignore_calendar = true;
        }

        if(Input::get('value') == 'no')
        {
            $teacher->ignore_calendar = false;
        }

        $teacher->save();
        
        $resource = new Fractal\Resource\Item($teacher, new TeacherTransformer);

        $token = JWTAuth::fromUser($user);

        $manager = new Manager();
        $manager->setSerializer(new ArraySerializer());

        return $this->response->withArray([
            'result'=>'success',
            'token' => $token,
            'user' => $manager->createData($resource)->toArray()
        ])->setStatusCode(200);

    }

}
