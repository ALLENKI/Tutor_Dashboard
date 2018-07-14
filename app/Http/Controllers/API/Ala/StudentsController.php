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

use Input;
use Validator;
use Carbon;
use DB;

use Aham\Models\SQL\Location;
use Aham\Models\SQL\Student;
use Aham\Models\SQL\ClassTiming;
use Aham\Models\SQL\AhamClass;
use Aham\Models\SQL\StudentEnrollment;

class StudentsController extends BaseController
{
    public function __construct()
    {
        parent::__construct();
    }

    public function studentsByDate()
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

        // dd($q);

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
                    ->orderBy($column,$sort)
                    ->get();

        $records = array();
        $records["data"] = array(); 

        foreach($enrollments as $enrollment)
        {
            $row = [];

            $image = cloudinary_url($enrollment->student->user->present()->picture,['secure' => true]);

            $row['enrollments']['student'] = "<a href='#/location/".$location->slug."/"."students/".$enrollment->student->id."'>".$enrollment->student->user->name." (".$enrollment->student->user->email.")"."</a>";
            $row['enrollments']['class_code'] = $enrollment->ahamClass->code;
            $row['enrollments']['class_name'] = $enrollment->ahamClass->topic->name;
            $row['enrollments']['id'] = $enrollment->id;
            $row['enrollments']['avatar'] = "<img src='$image' class='img-responsive' style='height:35px;' />";
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

        $model = Student::with('user')
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
            if(Input::get('active') === 'true') {
                $model = $model->where('active', true);
            } else {
                $model = $model->where('active', false);
            }
        }


        $iTotalRecords = $model->count();

        $iDisplayLength = intval(Input::get('length',10));
        $iDisplayLength = $iDisplayLength < 0 ? $iTotalRecords : $iDisplayLength; 
        $iDisplayStart = intval(Input::get('start',0));
        $sEcho = intval(Input::get('draw',1));


        $students = $model
                    ->skip($iDisplayStart)
                    ->take($iDisplayLength)
                    ->orderBy($column,$sort)
                    ->get();

        $records = array();
        $records["data"] = array(); 


        //$row['students']['active'] = $student->active ? 'yes' : 'no';
        foreach($students as $student)
        {
            $row = [];
            $id = $student->id;

            $row['students']['code'] = "<a href='#/location/$location/students/$id'>".$student->code."</a>";
            $row['students']['credits'] = $student->credits;
            $row['students']['name'] = $student->user->name;
            $row['students']['email'] = $student->user->email;
            $records["data"][] = $row;
        }

        $records["draw"] = $sEcho;
        $records["recordsTotal"] = $iTotalRecords;
        $records["recordsFiltered"] = $iTotalRecords;

        //dd('records',$records);

        return $records;
    }

    public function getStudentTimeDate(Request $request)
    {

        $location = Input::get('location');

        if(Input::has('day')) {
            $days_of_week = [Input::get('day')];
        } else {
                $days_of_week = [
                    'sunday',
                    'monday',
                    'tuesday',
                    'wednesday',
                    'thursday',
                    'friday',
                    'saturday',
                ];
        }


       /*\
        *  PAST DATA
       \*/
        $week1 = date('Y-m-d',strtotime("last ".Input::get('day')));
        $week2 = date('Y-m-d',strtotime("last ".Input::get('day')."-7 days"));
        //['2017-07-14','2017-07-11'] for testing
        //[$week1,$week2]

        $studentsEnrollments = DB::table('student_enrollments')
                                ->join('class_timings','class_timings.class_id','=','student_enrollments.class_id')
                                ->join('students','students.id','=','student_enrollments.student_id')
                                ->join('users','users.id','=','students.user_id')
                                ->whereIn('class_timings.date',[$week1,$week2])
                                ->where('class_timings.deleted_at','=',NULL)
                                ->orderBy('class_timings.date','asc')
                                ->orderBy('class_timings.start_time','asc')
                                ->select('users.name','students.id as user_id','users.grade','class_timings.start_time','class_timings.date','students.curriculum')
                                ->get();

        $pastData = [];

        foreach($studentsEnrollments as $studentEnrollment) {

                if($studentEnrollment->start_time >= '05:30:00' && $studentEnrollment->start_time <= '07:30:00'){
                    $studentEnrollment->timesOfDay = 'early_morning';
                    $pastData['early_morning'][] = json_decode(json_encode($studentEnrollment), True);
                }

                if($studentEnrollment->start_time > '07:30:00' && $studentEnrollment->start_time <= '12:00:00'){
                    $studentEnrollment->timesOfDay = 'morning';
                    $pastData['morning'][] = json_decode(json_encode($studentEnrollment), True);
                }

                if($studentEnrollment->start_time > '12:00:00' && $studentEnrollment->start_time <= '16:00:00'){
                    $studentEnrollment->timesOfDay = 'afternoon';
                    $pastData['afternoon'][] = json_decode(json_encode($studentEnrollment), True);
                }

                if($studentEnrollment->start_time > '16:00:00' && $studentEnrollment->start_time <= '18:30:00'){
                    $studentEnrollment->timesOfDay = 'evening';
                    $pastData['evening'][] = json_decode(json_encode($studentEnrollment), True);
                }

                if($studentEnrollment->start_time > '18:00:00' && $studentEnrollment->start_time <= '22:30:00'){
                    $studentEnrollment->timesOfDay = 'late_evening';
                    $pastData['late_evening'][] = json_decode(json_encode($studentEnrollment), True);
                }

        }

        foreach($studentsEnrollments as $studentEnrollment){
            if(isset($pastData['early_morning'])){
                $pastData['early_morning'] = unique_multidim_array($pastData['early_morning'],'user_id');
            }
            if(isset($pastData['morning'])){
            $pastData['morning'] = unique_multidim_array($pastData['morning'],'user_id');
            }
            if(isset($pastData['afternoon'])){
            $pastData['afternoon'] = unique_multidim_array($pastData['afternoon'],'user_id');
            }
            if(isset($pastData['evening'])){
            $pastData['evening'] = unique_multidim_array($pastData['evening'],'user_id');
            }
            if(isset($pastData['late_evening'])){
            $pastData['late_evening'] = unique_multidim_array($pastData['late_evening'],'user_id');
            }
        }

        /*\
         *  PRESENT DATA
        \*/
         $students = Student::join('users','users.id','=','students.user_id')
                    ->select('curriculum','users.name','users.grade','students.id','selected_times_text','selected_times_of_day','selected_days_of_week')
                    ->get();

        // return $students;

        foreach($students as $student) {
             //var_dump($student->id);
            try{

                $values = unserialize($student->selected_times_text);
                $student->selected_times_text = $values;
            }
            catch(\Exception $e)
            {
                $student->selected_times_text = [];
            }
        }

        $arrofTimings = $students->toArray();

         //dd($arrofTimings);
        $result = [];


        foreach($days_of_week as $day_of_week)
        {
            $item = [];
        
            $item['day'] = $day_of_week;
            $item['early_morning'] = $this->getFilteredStudents($arrofTimings,$day_of_week,'early_morning',$location);
            $item['morning'] = $this->getFilteredStudents($arrofTimings,$day_of_week,'morning',$location);
            $item['afternoon'] = $this->getFilteredStudents($arrofTimings,$day_of_week,'afternoon',$location);
            $item['evening'] = $this->getFilteredStudents($arrofTimings,$day_of_week,'evening',$location);
            $item['late_evening'] = $this->getFilteredStudents($arrofTimings,$day_of_week,'late_evening',$location);

            $result[] = $item;
        }

        return ['present' => $result,'past' => $pastData];
    }

    public function getFilteredStudents($students, $selected_day,$selected_time,$location)
    {
        $filteredStudents = array_filter($students, function($timing) use ($selected_day, $selected_time,$location){

            if(isset($timing['selected_times_text'][$selected_day]))
            {
                $timingsForTheDay = $timing['selected_times_text'][$selected_day];

                if(in_array($selected_time, $timingsForTheDay))
                {
                    return true;
                }
            }

            return false;

        });

        return $filteredStudents;
    }


    public function show($id)
    {
        $student = Student::find($id);

        return $this->response->item($student, new StudentTransformer);
    }

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

    public function postInterest($id)
    {
        // return Input::get('weekly_times_of_day');

        // return Input::all();

        $student = Student::find($id);

        $data = [];
        $data['selected_days_of_week'] = implode(',', Input::get('selected_days_of_week'));
        $data['selected_subjects'] = implode(',', Input::get('selected_subjects')); 
        $data['selected_times_text'] = serialize(Input::get('weekly_times_of_day',[])); 
        $data['curriculum'] = Input::get('curriculum','');
        $data['school'] = Input::get('school','');

        $student->fill($data);

        $student->save();

        $user = $student->user;
        $user->name = Input::get('username');
        $user->mobile = Input::get('mobilenumber');
        $user->grade = Input::get('selected_grade');
        $user->save();

        return $this->response->item($student, new StudentTransformer);

    }

}
