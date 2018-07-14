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
use Aham\Transformers\ClassTimingTransformer;

use Input;
use Validator;
use Carbon;

use Aham\Models\SQL\Location;
use Aham\Models\SQL\Teacher;
use Aham\Models\SQL\User;
use Aham\Models\SQL\ClassTiming;
use Aham\Models\SQL\AhamClass;

class TeachersController extends BaseController
{
    public function __construct()
    {
        parent::__construct();
    }

    public function teachersByDate()
    {
        $location = Location::where('slug',Input::get('location'))->first();

        // Find enrollments                            

        $output_mode = Input::get('o','json');

        $q = Input::get('search')['value'];

        $column = Input::get('order')[0]['column'];

        $sort = Input::get('order')[0]['dir'];

        $column = Input::get('columns')[$column]['name'];

        // dd($q);

        $model = ClassTiming::with('ahamClass.enrollments','teacher')
                                ->whereHas('ahamClass', function($query)
                                {
                                    $query
                                    ->where('status', '<>', 'cancelled');
                                })
                                ->where(function ($query) use ($q) {
                                    
                                    $query
                                    ->whereHas('ahamClass', function($query) use ($q)
                                    {
                                        $query->where(function ($query) use ($q)
                                        {
                                            $query->where('code', 'LIKE', '%'.$q.'%');
                                        });
                                        
                                    })
                                    ->orWhereHas('ahamClass.topic', function($query) use ($q)
                                    {
                                        $query->where(function ($query) use ($q)
                                        {
                                            $query->where('name', 'LIKE', '%'.$q.'%');
                                        });
                                        
                                    })
                                    ->orWhereHas('teacher.user', function($query) use ($q)
                                    {
                                        $query->where(function ($query) use ($q)
                                        {
                                            $query->where('name', 'LIKE', '%'.$q.'%')
                                                ->orWhere('email', 'LIKE', '%'.$q.'%');
                                        });
                                        
                                    });

                                })
                                ->where('location_id',$location->id)
                                ->whereNotNull('teacher_id')
                                ->whereDate('date', '=', Carbon::createFromTimestamp(strtotime(Input::get('date'))));

        $iTotalRecords = $model->count();
        $iDisplayLength = intval(Input::get('length',10));
        $iDisplayLength = $iDisplayLength < 0 ? $iTotalRecords : $iDisplayLength; 
        $iDisplayStart = intval(Input::get('start',0));
        $sEcho = intval(Input::get('draw',1));


        $timings = $model
                    ->skip($iDisplayStart)
                    ->take($iDisplayLength)
                    ->orderBy($column,$sort)
                    ->get();

        $records = array();
        $records["data"] = array(); 

        foreach($timings as $timing)
        {
            $row = [];

            $image = cloudinary_url($timing->teacher->user->present()->picture,['secure' => true]);

            $row['timings']['teacher'] = "<a href='#/location/".$location->slug."/"."teachers/".$timing->teacher->id."'>".$timing->teacher->user->name." (".$timing->teacher->user->email.")"."</a>";
            $row['timings']['class_unit'] = $timing->classUnit->name;
            $row['timings']['class_code'] = $timing->ahamClass->code;
            $row['timings']['class_name'] = $timing->ahamClass->topic->name;
            $row['timings']['id'] = $timing->id;
            $row['timings']['avatar'] = "<img src='$image' class='img-responsive' style='height:35px;' />";
            $records["data"][] = $row;
        }

        $records["draw"] = $sEcho;
        $records["recordsTotal"] = $iTotalRecords;
        $records["recordsFiltered"] = $iTotalRecords;

        return $records;

        return $classTimings;
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

        $model = Teacher::with('user')
                        ->where(function ($query) use ($q) {
                                    
                            $query
                            ->where('code', 'LIKE', '%'.$q.'%')
                            ->orWhereHas('user', function($query) use ($q)
                            {
                                $query->where(function ($query) use ($q)
                                {
                                    $query->where('name', 'LIKE', '%'.$q.'%')
                                        ->orWhere('email', 'LIKE', '%'.$q.'%');
                                });
                                
                            });

                        });

        if(Input::has('active')) {
            if( Input::get('active') === 'true' ){
                $model =  $model->where('active', true);
            } else {
                $model =  $model->where('active', false);
            }
        }


        $iTotalRecords = $model->count();
        $iDisplayLength = intval(Input::get('length',10));
        $iDisplayLength = $iDisplayLength < 0 ? $iTotalRecords : $iDisplayLength; 
        $iDisplayStart = intval(Input::get('start',0));
        $sEcho = intval(Input::get('draw',1));


        $teachers = $model
                    ->skip($iDisplayStart)
                    ->take($iDisplayLength)
                    ->orderBy($column,$sort)
                    ->get();

        $records = array();
        $records["data"] = array(); 

        //$row['teachers']['active'] = $teacher->active ? 'yes' : 'no';
        foreach($teachers as $teacher)
        {
            $row = [];
            $id = $teacher->id;

            $row['teachers']['code'] = "<a href='#/location/$location/teachers/$id'>".$teacher->code."</a>";
            $row['teachers']['name'] = $teacher->user->name;
            $row['teachers']['email'] = $teacher->user->email;
            $records["data"][] = $row;
        }

        $records["draw"] = $sEcho;
        $records["recordsTotal"] = $iTotalRecords;
        $records["recordsFiltered"] = $iTotalRecords;

        return $records;
    }

    public function show($id)
    {
        $teacher = Teacher::find($id);

        return $this->response->item($teacher, new TeacherTransformer);
    }

    public function getAnalytics($id) {

        $teacher = Teacher::find($id);
        $date = \Carbon::now();
        $currentDate = $date->format('Y-m-d');

        //units this Month, with no.of students * charge multiple
        $classTimings = ClassTiming::with('ahamClass.enrollments')
                                        ->where('teacher_id',$teacher->id)
                                        ->whereHas('ahamClass', function($query)
                                        {
                                            $query
                                            ->where('status', '<>', 'cancelled');
                                        })
                                        ->whereBetween('date',
                                        [
                                            $date->startOfMonth(),
                                            $currentDate
                                        ])
                                        ->get();


        $analytic = [];
        $analytic['id'] = "units_this_month";
        $analytic['name'] = 'Units This Month';
        $analytic['value'] = $this->getTotalUnits($classTimings);
        $analytics[] = $analytic;

        //classes this Month
        $classTimings = ClassTiming::with('ahamClass.enrollments')
                                        ->where('teacher_id',$teacher->id)
                                        ->whereHas('ahamClass', function($query)
                                        {
                                            $query
                                            ->where('status', '<>', 'cancelled');
                                        })
                                        ->whereBetween('date',
                                         [
                                            $date->startOfMonth(),
                                            $currentDate
                                         ])
                                        ->get();

        // return $classTimings->pluck('class_id','id')->toArray();

        $analytic = [];
        $analytic['id'] = "classes_this_month";
        $analytic['name'] = 'Classes This Month';
        $analytic['value'] = $classTimings->count();
        $analytics[] = $analytic;


       //units till date(from the beginning till today) 
       $classTimings = ClassTiming::with('ahamClass.enrollments')
                            ->where('teacher_id',$teacher->id)
                            ->whereHas('ahamClass', function($query)
                            {
                                $query
                                ->where('status', '<>', 'cancelled');
                            })
                            ->whereDate('date','<=',$currentDate)
                            ->get();

        $analytic = [];
        $analytic['id'] = "units_till_date";
        $analytic['name'] = 'Units Till Date';
        $analytic['value'] = $this->getTotalUnits($classTimings);
        $analytics[] = $analytic;


        //classes till date(from the beginning till today)
        $classTimings = ClassTiming::with('ahamClass.enrollments')
                            ->where('teacher_id',$teacher->id)
                            ->whereHas('ahamClass', function($query)
                            {
                                $query
                                ->where('status', '<>', 'cancelled');
                            })
                           // ->whereBetween('date',
                           // [
                           // 1800-01-01,
                           // $currentDate
                           // ])
                            ->whereDate('date','<=',$currentDate)
                            ->get();


        //return [$classTimings->count(),$teacher->id];

        $analytic = [];
        $analytic['id'] = "classes_till_date";
        $analytic['name'] = 'Classes Till Date';
        $analytic['value'] = $classTimings->count();
        $analytics[] = $analytic;

      return $analytics;
    }

    public function getTotalUnits($classTimings)
    {
        $totalUnits = 0;

        foreach($classTimings as $classTiming)
        {
            // if($classTiming->ahamClass->status != 'cancelled')
            // {
            if(!$classTiming->ahamClass->free){
                $totalUnits += $classTiming->ahamClass->enrollments->count()*$classTiming->ahamClass->charge_multiply;
            }
            // }
        }

        return $totalUnits;
    }

    public function classes($id)
    {
        $teacher = Teacher::find($id);

        $from_date = Carbon::createFromTimestamp(strtotime(Input::get('from_date')));
        $to_date = Carbon::createFromTimestamp(strtotime(Input::get('to_date')));

        // dd(Input::get('from_date'));
        $cancelledClasses = AhamClass::where('status','cancelled')->pluck('id')->toArray();

        $timings = ClassTiming::with('ahamClass','classUnit','classroom')
                    ->where('teacher_id',$id)
                    ->whereBetween('date',[$from_date,$to_date])
                    ->whereNotIn('status',['cancelled'])
                    ->whereNotIn('class_id',$cancelledClasses)
                    ->orderBy('date','asc')
                    ->orderBy('start_time','asc')
                    ->get();

        return $this->response->collection($timings, new ClassTimingTransformer);


    }

    public function get(){
        // teacher with user_id == user.id(relationShip where(relation)) then get the data.
        return Teacher::with('user')->get();
    }

    public function classesForCalendar($id)
    {
        $teacher = Teacher::find($id);

        $from_date = Carbon::createFromTimestamp(strtotime(Input::get('start')));
        $to_date = Carbon::createFromTimestamp(strtotime(Input::get('end')));

        // dd(Input::get('from_date'));

        $cancelledClasses = AhamClass::where('status','cancelled')->pluck('id')->toArray();

        $timings = ClassTiming::with('ahamClass','classUnit','classroom')
                    ->where('teacher_id',$id)
                    ->whereBetween('date',[$from_date,$to_date])
                    ->whereNotIn('status',['cancelled'])
                    ->whereNotIn('class_id',$cancelledClasses)
                    ->orderBy('date','asc')
                    ->orderBy('start_time','asc')
                    ->get();

        $events = [];

        foreach($timings as $timing)
        {
            $event = [];

            $event['title'] = $timing->classUnit->name.' '.$timing->ahamClass->topic_name;
            $event['class_id'] = $timing->ahamClass->id;


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
