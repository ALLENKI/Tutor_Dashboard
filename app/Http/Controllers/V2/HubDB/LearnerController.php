<?php

namespace Aham\Http\Controllers\V2\HubDB;

use Aham\Models\SQL\StudentEnrollment;
use Aham\Models\SQL\Student;
use Aham\Models\SQL\Teacher;
use Aham\Models\SQL\AhamClass;
use Aham\Models\SQL\ClassTiming;


use Aham\Transformers\ClassTimingTransformer;
use Aham\Transformers\UserTransformer;
use Aham\Transformers\StudentTransformer;
use Aham\Models\SQL\Location;

use Validator;
use Input;
use Carbon;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Redirect;

class LearnerController extends BaseController
{
    public function classes($id)
    {
        $student = Student::find($id);

        $from_date = Carbon::createFromTimestamp(strtotime(Input::get('from_date')));
        $to_date = Carbon::createFromTimestamp(strtotime(Input::get('to_date')));

        $enrollments = $student->enrollments->pluck('class_id')->toArray();

        $classes = AhamClass::whereIn('status',['in_session','scheduled','open_for_enrollment','completed','get_feedback','got_feedback'])
                                    ->whereIn('id',$enrollments)
                                    ->orderBy('start_date','asc')
                                    ->pluck('id')
                                    ->toArray();

        // dd(Input::get('from_date'));

        //Find enrolled classes

        $timings = ClassTiming::with('ahamClass','classUnit','classroom')
                    ->whereIn('class_id',$classes)
                    ->whereBetween('date',[$from_date,$to_date])
                    ->whereNotIn('status',['cancelled'])
                    ->orderBy('date','asc')
                    ->orderBy('start_time','asc')
                    ->get();

        return $this->response->collection($timings, new ClassTimingTransformer);
    }

    public function classesForCalendar($id)
    {
        $student = Student::find($id);

        $from_date = Carbon::createFromTimestamp(strtotime(Input::get('start')));
        $to_date = Carbon::createFromTimestamp(strtotime(Input::get('end')));

        // dd(Input::get('from_date'));

        $enrollments = $student->enrollments->pluck('class_id')->toArray();

        $classes = AhamClass::whereIn('status',['in_session','scheduled','open_for_enrollment','completed','get_feedback','got_feedback'])
                                    ->whereIn('id',$enrollments)
                                    ->orderBy('start_date','asc')
                                    ->pluck('id')
                                    ->toArray();
                                    
        $timings = ClassTiming::with('ahamClass','classUnit','classroom')
                    ->whereIn('class_id',$classes)
                    ->whereBetween('date',[$from_date,$to_date])
                    ->whereNotIn('status',['cancelled'])
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
    
    public function AllLearners()
    {
        $locationSlug = Input::get('location');

        // dd($q);

        $model = Student::with('user')->whereHas('hubs', function ($query) use ($locationSlug) {
                        $query->where('slug',$locationSlug);
                    });

        if(Input::has('active')) {
            if(Input::get('active') === 'true') {
                $model = $model->where('active', true);
            } else {
                $model = $model->where('active', false);
            }
        }


        $iTotalRecords = $model->count();
        
        $students = $model->get();

        $records = array();
        $records["data"] = array(); 


        //$row['students']['active'] = $student->active ? 'yes' : 'no';
        foreach($students as $student)
        {
            $row = [];
            $id = $student->id;

            $row['code'] = "<a href='#/hub/$locationSlug/learner-details/$id'>".$student->code."</a>";
            $row['credits'] = $this->totalCredits($student->user);
            $row['name'] = $student->user->name;
            $row['email'] = "<a href='#/hub/$locationSlug/learner-details/$id'>".$student->user->email."</a>";
            $records["data"][] = $row;
        }

        $records["recordsTotal"] = $iTotalRecords;

        return $records;
    }

    public function totalCredits($user)
	{

		// total credits
		$credits = $user->creditBuckets()->orderBy('created_at', 'asc')->get();
		$total_remaining = 0;

        foreach ($credits as $credit) {

            $total_remaining += ($credit->purchased_remaining + $credit->hub_only_remaining + $credit->promotional_remaining);
			
		}

		return $total_remaining;
	}

    public function learnerByDate()
    {
        $location = Location::where('slug',Input::get('location'))->first();

        $classTimings = ClassTiming::with('ahamClass.enrollments')
                                    ->whereHas('ahamClass', function($query)
                                    {
                                        $query
                                        ->where('status', '<>', 'cancelled');
                                    })
                                    ->where('location_id',$location->id)
                                    ->whereDate('date', '=', Carbon::createFromTimestamp(strtotime(Input::get('date'))))
                                    ->pluck('class_id')
                                    ->toArray();

        // Find enrollments                            

        $output_mode = Input::get('o','json');

        $q = Input::get('search')['value'];

        $column = Input::get('order')[0]['column'];

        $sort = Input::get('order')[0]['dir'];

        $column = Input::get('columns')[$column]['name'];

        $model = StudentEnrollment::with('student.user','ahamClass.topic')
                        ->whereIn('class_id',$classTimings)
                        ->where('cancelled',false)
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
                            ->orWhereHas('student.user', function($query) use ($q)
                            {
                                $query->where(function ($query) use ($q)
                                {
                                    $query->where('name', 'LIKE', '%'.$q.'%')
                                        ->orWhere('email', 'LIKE', '%'.$q.'%');
                                });
                                
                            });

                        });

        $iTotalRecords = $model->count();
        $iDisplayLength = intval(Input::get('length',10));
        $iDisplayLength = $iDisplayLength < 0 ? $iTotalRecords : $iDisplayLength; 
        $iDisplayStart = intval(Input::get('start',0));
        $sEcho = intval(Input::get('draw',1));


        $enrollments = $model
                    ->skip($iDisplayStart)
                    ->take($iDisplayLength)
                    // ->orderBy($column,$sort)
                    ->get();

        $records = array();
        $records["data"] = array(); 

        foreach($enrollments as $enrollment)
        {
            // print_r($enrollment->id);
            
            $row = [];

            $row['id'] = $enrollment->id;

            $image = cloudinary_url($enrollment->student->user->present()->picture,['secure' => true]);
            $row['avatar'] = "<img src='$image' class='img-responsive' style='height:35px;' />";

            $row['student'] = "<a href='#/hub/".$location->slug."/"."learner-details/".$enrollment->student->id."'>".$enrollment->student->user->name." (".$enrollment->student->user->email.")"."</a>";
            $row['class_code'] = $enrollment->ahamClass->code;
            $row['class_name'] = $enrollment->ahamClass->topic->name;
            
            $records["data"][] = $row;
        }

        $records["draw"] = $sEcho;
        $records["recordsTotal"] = $iTotalRecords;
        $records["recordsFiltered"] = $iTotalRecords;

        return $records;
    }

    public function userProfile($id)
    {
        $learner =  Student::find($id);
        $user = $learner->user;

        return $this->response->item($user, new UserTransformer);
    }


    public function learnerProfile($id)
    {
        $student =  Student::find($id);
        // $user = $learner->user;

        return $this->response->item($student, new StudentTransformer);
    }

    public function saveLearnerProfile(Request $request,$id)

    {
        $learner = Student::find($id);

        $user = $learner->user;

        $inputs =  $request->get('payload');

        $user->email = $inputs['Email'];
        $user->name = $inputs['Name'];
        $learner->grade = $inputs['Grade'];
        $user->mobile = $inputs['Mobile'];
        $learner->curriculum = $inputs['Curriculum'];
        $learner->communities = $inputs['Community'];
        $learner->school = $inputs['School'];

        $learner->selected_days_of_week = '';

        $learner->save();
        $user->save();
        
        if(array_key_exists('Day_of_Week',$inputs)) {
            
            $days = '';

            foreach($inputs['Day_of_Week'] as $dayOfWeek) 
            {
                $days = $dayOfWeek.','.$days;
            }

            $learner->selected_days_of_week = $days;

        }

        if(array_key_exists('Time_of_Day',$inputs)) {

            $learner->selected_times_of_day = serialize($inputs['Time_of_Day']);
            $learner->selected_times_text = serialize($inputs['Time_of_Day']);

        }

        $learner->save();
        
    }

    public function getFeedback($id)
    {
        $enrollments = StudentEnrollment::where('student_id',$id)
                                        ->orderBy('created_at','desc')
                                        ->paginate(5);

        // if($enrollments->isEmpty()) {
        //     return;
        // }

        $records = array();
        $records["data"] = array(); 

        foreach($enrollments as $enrollment)
        {

            if(!is_null($enrollment->only('feedback')['feedback'])) {

                $row = [];

                $row['feedback'] = $enrollment->only('feedback')['feedback'];

                if( !is_null( $enrollment->only('remarks')) ) {
                     $row['remakrs'] = $enrollment->only('remarks')['remarks'];
                }
                
                if(!is_null($enrollment->ahamClass->timings->first()->teacher->user->only('name')) ) {
                  $row['tutorName'] = $enrollment->ahamClass->timings->first()->teacher->user->only('name')['name'];
                } else {
                    $row['tutorName'] = '';
                }
                
                $row['code'] = '<a class="classCode" href="/hub-db/#/hub/aham-learning-hub-at-manikonda/view-class/'.$enrollment->ahamClass->only('id')['id'].'">'.$enrollment->ahamClass->only('code')['code'].'</a>';
        

                $row['topicName'] = $enrollment->ahamClass->topic->only('name')['name'];

                $row['date']  = $enrollment->ahamClass->only('start_date')['start_date']->format('d, M, Y');

                $records["data"][] = $row;

            }
            
        }

        return $records;
    }

}