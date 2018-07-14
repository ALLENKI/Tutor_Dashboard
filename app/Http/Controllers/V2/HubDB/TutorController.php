<?php

namespace Aham\Http\Controllers\V2\HubDB;

use Aham\Models\SQL\StudentEnrollment;
use Aham\Models\SQL\Student;
use Aham\Models\SQL\Teacher;
use Aham\Models\SQL\AhamClass;
use Aham\Models\SQL\ClassTiming;

use Aham\Transformers\ClassTimingTransformer;
use Aham\Transformers\UserTransformer;

use Input;
use Carbon;

class TutorController extends BaseController
{

    public function AllTutors()
    {
        $location = Input::get('location');

        $q = Input::get('search')['value'];

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

        $teachers = $model->get();

        $records = array();
        $records["data"] = array(); 

        //$row['teachers']['active'] = $teacher->active ? 'yes' : 'no';
        foreach($teachers as $teacher)
        {
            $row = [];
            $id = $teacher->id;

            $row['code'] = "<a href='#/hub/$location/tutor-details/$id'>".$teacher->code."</a>";
            $row['name'] = $teacher->user->name;
            $row['email'] = $teacher->user->email;
            $row['status'] = $teacher->active;
            $records["data"][] = $row;
        }

        $records["recordsTotal"] = $iTotalRecords;

        return $records;
    }

    public function tutorByDate()
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
                    // ->orderBy($column,$sort)
                    ->get();

        $records = array();
        $records["data"] = array(); 

        foreach($timings as $timing)
        {
            $row = [];

            $image = cloudinary_url($timing->teacher->user->present()->picture,['secure' => true]);

            $row['teacher'] = "<a href='#/hub/".$location->slug."/"."tutor-details/".$timing->teacher->id."'>".$timing->teacher->user->name." (".$timing->teacher->user->email.")"."</a>";
            $row['class_unit'] = $timing->classUnit->name;
            $row['class_code'] = $timing->ahamClass->code;
            $row['class_name'] = $timing->ahamClass->topic->name;
            $row['id'] = $timing->id;
            $row['avatar'] = "<img src='$image' class='img-responsive' style='height:35px;' />";
            $records["data"][] = $row;
        }

        $records["draw"] = $sEcho;
        $records["recordsTotal"] = $iTotalRecords;
        $records["recordsFiltered"] = $iTotalRecords;

        return $records;
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

    public function classesForCalendar($id)
    {
        $teacher = Teacher::find($id);

        $from_date = Carbon::createFromTimestamp(strtotime(Input::get('start')));
        $to_date = Carbon::createFromTimestamp(strtotime(Input::get('end')));

        $cancelledClasses = AhamClass::where('status','cancelled')->pluck('id')->toArray();

        $timings = ClassTiming::with('ahamClass','classUnit','classroom','location')
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

            $event['color'] = $timing->location->color;

            $events[] = $event;
        }
        return $events;
    }

    public function tutorProfile($id)
    {
        $tutor =  Teacher::find($id);
        $user = $tutor->user;
        return $this->response->item($user,new UserTransformer);
    }
    

}