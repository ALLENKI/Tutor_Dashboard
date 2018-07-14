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

use League\Fractal\Pagination\IlluminatePaginatorAdapter;
use Aham\Transformers\TeacherTransformer;
use Aham\Transformers\StudentTransformer;
use Aham\Transformers\ClassTimingTransformer;
use Aham\Transformers\ClassroomTransformer;

use Input;
use Validator;
use Carbon;

use Aham\Models\SQL\Location;
use Aham\Models\SQL\Student;
use Aham\Models\SQL\ClassTiming;
use Aham\Models\SQL\AhamClass;
use Aham\Models\SQL\Classroom;

class ClassroomsController extends BaseController
{
    public function __construct()
    {
        parent::__construct();
    }

    public function index(Request $request)
    {
        $location = Input::get('location');

        $output_mode = Input::get('o','json');

        $q = Input::get('search')['value'];

        $column = Input::get('order')[0]['column'];

        $sort = Input::get('order')[0]['dir'];

        $column = Input::get('columns')[$column]['name'];

        // dd($q);

        $model = Classroom::where('name', 'LIKE', '%'.$q.'%');

        $iTotalRecords = $model->count();
        $iDisplayLength = intval(Input::get('length',10));
        $iDisplayLength = $iDisplayLength < 0 ? $iTotalRecords : $iDisplayLength; 
        $iDisplayStart = intval(Input::get('start',0));
        $sEcho = intval(Input::get('draw',1));


        $classrooms = $model
                    ->skip($iDisplayStart)
                    ->take($iDisplayLength)
                    ->orderBy($column,$sort)
                    ->get();

        $records = array();
        $records["data"] = array(); 

        foreach($classrooms as $classroom)
        {
            $row = [];
            $id = $classroom->id;

            $row['classrooms']['code'] = "<a href='#/location/$location/classrooms/$id'>".$classroom->code."</a>";
            $row['classrooms']['name'] = $classroom->name;
            $row['classrooms']['active'] = $classroom->active ? 'yes' : 'no';
            $records["data"][] = $row;
        }

        $records["draw"] = $sEcho;
        $records["recordsTotal"] = $iTotalRecords;
        $records["recordsFiltered"] = $iTotalRecords;

        return $records;

    }


    public function show($id)
    {
        $classroom = Classroom::find($id);

        return $this->response->item($classroom, new ClassroomTransformer);
    }

    public function classes($id)
    {
        $classroom = Classroom::find($id);

        $from_date = Carbon::createFromTimestamp(strtotime(Input::get('from_date')));
        $to_date = Carbon::createFromTimestamp(strtotime(Input::get('to_date')));

        // dd(Input::get('from_date'));

        // $cancelledClasses = AhamClass::where('status','cancelled')->pluck('id')->toArray();

        //Find enrolled classes

        $timings = ClassTiming::with('ahamClass','classUnit','classroom')
                    ->where('classroom_id',$classroom->id)
                    ->whereBetween('date',[$from_date,$to_date])
                    ->whereNotIn('status',['cancelled'])
                    // ->whereNotIn('class_id',$cancelledClasses)
                    ->orderBy('date','asc')
                    ->orderBy('start_time','asc')
                    ->get();

        return $this->response->collection($timings, new ClassTimingTransformer);
    }

    public function classesForCalendar($id)
    {
        $classroom = Classroom::find($id);

        $from_date = Carbon::createFromTimestamp(strtotime(Input::get('start')));
        $to_date = Carbon::createFromTimestamp(strtotime(Input::get('end')));

        // $cancelledClasses = AhamClass::where('status','cancelled')->pluck('id')->toArray();

        $timings = ClassTiming::with('ahamClass','classUnit','classroom')
                    ->where('classroom_id',$classroom->id)
                    ->whereBetween('date',[$from_date,$to_date])
                    ->whereNotIn('status',['cancelled'])
                    // ->whereNotIn('class_id',$cancelledClasses)
                    ->orderBy('date','asc')
                    ->orderBy('start_time','asc')
                    ->get();

        $events = [];

        foreach($timings as $timing)
        {
            $event = [];

            $event['title'] = $timing->classUnit->name.' '.$timing->ahamClass->topic_name;

            $dt = Carbon::parse($timing->date->format('Y-m-d').' '.$timing->start_time);

            $event['start'] = $dt->toIso8601String();

            $dt = Carbon::parse($timing->date->format('Y-m-d').' '.$timing->end_time);

            $event['end'] = $dt->toIso8601String();

            if($timing->status == 'done')
            {
                $event['color'] = '#39c529';
            }

            $events[] = $event;
        }

        return $events;
    }


}
