<?php

namespace Aham\Http\Controllers\V2\HubDB;

use Aham\Http\Controllers\Controller;
use Tymon\JWTAuth\Facades\JWTAuth;
use Cartalyst\Sentinel\Laravel\Facades\Sentinel;
use Aham\Repositories\ClassRepository;
use League\Fractal;
use Aham\Managers\EnrollmentManager;

use Aham\Transformers\AhamClassTransformer;
use League\Fractal\Pagination\IlluminatePaginatorAdapter;

use League\Fractal\Manager;
use League\Fractal\Serializer\DataArraySerializer;
use League\Fractal\Serializer\ArraySerializer;


use \Illuminate\Support\Collection;

use Aham\Models\SQL\AhamClass;
use Aham\Models\SQL\ClassTiming;
use Aham\Models\SQL\Location;
use Aham\Models\SQL\Course;
use Carbon;
use Input;

class ClassesController extends BaseController
{
    private $ahamClass;

    public function __construct(ClassRepository $ahamClass)
    {
        $this->ahamClass = $ahamClass;
    }

    public function repeatClasses($id)
    {
       return $this->response->collection($this->ahamClass->allRepeatClasses($id),new AhamClassTransformer);  
    }

    public function repeatClassDetails($id)
    {
        return AhamClass::find($id);
    }
    
    public function course($id)
    {

        return $this->response->collection($this->ahamClass->classCourseDetail($id), new AhamClassTransformer);
    }

    public function classeCourseDetail($id)
    {
        return $this->ahamClass->showClassWithCourse($id);
    }

    public function index()
    {
        // return request()->all();

        $location = Location::where('slug',Input::get('hub'))->first();
        $model = AhamClass::with('topic','location','timings.teacher.user')
                            ->where('location_id',$location->id)
                            ->whereIn('type',['single_class','single_group_class']);

        if(Input::has('input'))
        {
            $q = Input::get('input');

            $model = $model->where(function ($query) use ($q) {
                                $query
                                ->where('code', 'LIKE', '%'.$q.'%')
                                ->orWhereHas('topic', function($query) use ($q)
                                {
                                    $query
                                    ->where('name', 'LIKE', '%'.$q.'%');
                                })
                                ->orWhereHas('timings.teacher.user', function($query) use ($q)
                                {
                                    $query
                                    ->where('name', 'LIKE', '%'.$q.'%');
                                })
                                ->orWhereHas('location', function($query) use ($q)
                                {
                                    $query
                                    ->where('name', 'LIKE', '%'.$q.'%');
                                });
                            });
        }

        if(count(Input::get('status',[])) == 1)
        {
          $model = $this->middleState(Input::get('status',[]),$model);
            
        } else {
            $states = Input::get('status');

            $model = $model->whereIn('status',$states);
        }

        if(Input::has('topic')){
            $model = $model->where('topic_id',Input::get('topic'));
        }

        if(Input::has('tutor')){
            $teacher_id = Input::get('tutor');
            $model = $model->whereHas('timings',function($query) use ($teacher_id){
                $query->where('teacher_id',$teacher_id);
            });
        }

        switch (Input::get('sort','created_at')) {
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

        $paginator = $model->paginate(Input::get('per_page',12));

        $classes = $paginator->getCollection();

        $resource = new Fractal\Resource\Collection($classes, new AhamClassTransformer);
        $paginator->appends(Input::only('hub','status','sort','input'));
        $resource->setPaginator(new IlluminatePaginatorAdapter($paginator));

        $manager = new Manager();
        $manager->setSerializer(new ArraySerializer());
        $classes = $manager->createData($resource)->toArray();

        return $classes;
    }

    public function store()
    {

        if(Input::get('type') == "single_class") {

            $ahamClass = $this->ahamClass->createClassWithTopic(request()->only(
                'location_id', 'topic_id', 'minimum_enrollment', 'maximum_enrollment', 'charge_multiply', 'free_class', 'pay_tutor', 'auto_cancel', 'type', 'unit_duration','name'), $this->auth->user());

        } else {

            $ahamClass =  $this->ahamClass->createClassWithCourse(request()->only(
                        'location_id', 'course_id', 'minimum_enrollment', 'maximum_enrollment', 'charge_multiply','unit_duration', 'free_class', 'pay_tutor', 'auto_cancel', 'type','name'), $this->auth->user());

        }
        
        return $ahamClass;
    }


    public function update($classId)
    {
        $ahamClass = $this->ahamClass->updateClass($classId,request()->only('name','location_id', 'topic_id', 'minimum_enrollment', 'maximum_enrollment', 'charge_multiply', 'free_class', 'pay_tutor', 'auto_cancel', 'type', 'unit_duration'), $this->auth->user());

        return $ahamClass;
    }

    public function calendar($id)
    {
        $ahamClass = $this->ahamClass->find($id);
        $classrooms = $ahamClass->location->classrooms()->where('active',true)->pluck('id')->toArray();

        $from_date = Carbon::createFromTimestamp(strtotime(Input::get('start')));
        $to_date = Carbon::createFromTimestamp(strtotime(Input::get('end')));

        $classTimings = ClassTiming::whereHas('classroom')
                                    ->whereIn('classroom_id',$classrooms)
                                    ->whereBetween('date',[$from_date,$to_date])
                                    ->whereNotIn('status',['cancelled'])
                                    ->orderBy('date','asc')
                                    ->orderBy('start_time','asc')
                                    ->get();

        $events = [];

        foreach($classTimings as $timing)
        {
            $event = [];
            $event['title'] = $timing->classUnit->name;
            $dt = Carbon::parse($timing->date->format('Y-m-d').' '.$timing->start_time);
            $event['start'] = $dt->toIso8601String();
            $dt = Carbon::parse($timing->date->format('Y-m-d').' '.$timing->end_time);
            $event['end'] = $dt->toIso8601String();
            $event['resourceId'] = $timing->classroom_id;
            $event['rendering'] = 'background';
            $event['overlap'] = false;

            if($timing->class_id != $ahamClass->id)
            {
                $events[] = $event;
            }
            
        }

        return $events;

    }

    public function schedule()
    {
        $ahamClass = $this->ahamClass->find(Input::get('class_id'));
        $timings = Input::get('timings');

        foreach($timings as $key => $timing)
        {
            $date = Carbon::createFromTimestamp(strtotime($timing['date']));
            $start_time = Carbon::createFromTimestamp(strtotime($timing['start_time']));
            $end_time = Carbon::createFromTimestamp(strtotime($timing['end_time']));

            $classTiming = ClassTiming::firstOrCreate([
                'class_id' => $ahamClass->id,
                'class_unit_id' => $timing['class_unit_id'],
                'of_id' => $ahamClass->id,
                'of_type' => get_class($ahamClass)
            ]);

            if($ahamClass->teacher)
            {
                $classTiming->teacher_id = $ahamClass->teacher->id;
            }

            $classTiming->start_time = $start_time;
            $classTiming->end_time = $end_time;
            $classTiming->date = $date;
            $classTiming->classroom_id = $timing['classroom_id'];
            $classTiming->location_id = $ahamClass->location->id;
            $classTiming->unit_id = $classTiming->classUnit->original_unit_id;

            $classTiming->save();

        }


        $start_date = $ahamClass->timings->first()['date']->format('Y-m-d').' '.$ahamClass->timings->first()['start_time'];
        $ahamClass->start_date = $start_date;
        $ahamClass->schedule_cutoff = (clone $ahamClass->start_date)->addMinutes(30);
        $ahamClass->status = 'open_for_enrollment';
        $ahamClass->scheduling_rule = Input::get('scheduling_rule');
        $ahamClass->save();

        foreach($ahamClass->allEnrollments as $enrollment)
        {
            $manager = new EnrollmentManager($ahamClass, $enrollment->student);

            $manager->syncEnrollmentUnits($enrollment);
        }

        return $ahamClass;
    }

    public function show($id)
    {
        $ahamClass = $this->ahamClass->find($id);

        $class = [];
        $class['id'] = $ahamClass->id;
        $class['code'] = $ahamClass->code;
        $class['unit_duration'] = $ahamClass->unit_duration;
        $class['topic'] = $ahamClass->topic_name;
        $class['units'] = $ahamClass->classUnits;

        
        $class['timings'] = [];

        foreach($ahamClass->classTimings as $classTiming)
        {
            $classTiming->start_time_date = $classTiming->date->format('Y-m-d').' '.$classTiming->start_time;
            $classTiming->end_time_date = $classTiming->date->format('Y-m-d').' '.$classTiming->end_time;
            $class['timings'][] = $classTiming;
        }

        $schedulingRules = [
            4 => ['4','3-1','1-3','2-2','2-1-1','1-2-1','1-1-2','1-1-1-1'],
            3 => ['3','2-1','1-2','1-1-1'],
            2 => ['2','1-1'],
            1 => ['1']
        ];

        if($ahamClass->classUnits->count() <= 4)
        {
            $class['scheduling_rules'] = $schedulingRules[$ahamClass->classUnits->count()];
        }
        else
        {
            $class['scheduling_rules'] = [implode('-',array_fill(0,$ahamClass->classUnits->count(),1))];
        }

        $class['scheduling_rule'] = $class['scheduling_rules'][count($class['scheduling_rules'])-1];

        if($ahamClass->schedulingRule)
        {
            $class['scheduling_rule'] = $ahamClass->schedulingRule->division;
        }

        if(!is_null($ahamClass->scheduling_rule))
        {
            $class['scheduling_rule'] = $ahamClass->scheduling_rule;
        }

        $classrooms = $ahamClass->location->classrooms()->get();;

        foreach($classrooms as $classroom)
        {
           $class['classrooms'][] = [
            'id' => $classroom->id,
            'title' => $classroom->name,
           ]; 
        }

        return $class;
    }

    public function classesForCalendar($slug)
    {
        $location = Location::where('slug', $slug)->first();

        $classes = AhamClass::whereNotIn('status', ['cancelled'])
                                    ->where('location_id', $location->id)
                                    ->orderBy('start_date', 'asc')
                                    ->pluck('id')
                                    ->toArray();

        $from_date = Carbon::createFromTimestamp(strtotime(Input::get('start')));
        $to_date = Carbon::createFromTimestamp(strtotime(Input::get('end')));

        $timings = ClassTiming::with('ahamClass', 'classUnit', 'classroom')
                    ->whereIn('class_id', $classes)
                    ->whereBetween('date', [$from_date, $to_date])
                    ->whereNotIn('status', ['cancelled'])
                    ->orderBy('date', 'asc')
                    ->orderBy('start_time', 'asc')
                    ->get();

        $events = [];

        foreach ($timings as $timing) {
            $event = [];

            $event['title'] = $timing->classUnit->name . ' ' . $timing->ahamClass->topic_name;
            $event['class_id'] = $timing->ahamClass->id;

            $dt = Carbon::parse($timing->date->format('Y-m-d') . ' ' . $timing->start_time);

            $event['start'] = $dt->toIso8601String();

            $dt = Carbon::parse($timing->date->format('Y-m-d') . ' ' . $timing->end_time);

            $event['end'] = $dt->toIso8601String();

            if ($timing->status == 'done') {
                $event['color'] = '#39c529';
            }

            if ($timing->status != 'done' && $timing->date->isPast()) {
                $event['color'] = '#d66868';
            }

            $events[] = $event;
        }
        return $events;
    }
    
    public function middleState($states,$model)
    {
       
            switch ($states[0]) {
                case "no_invitations":
                
                        $model = $model->doesntHave('invitations')
                                        ->where('status','<>','cancelled'); 
                    break;
    
                case "has_invitations_no_teacher":
                        $model = $model->whereIn('status',['initiated']);
                    break;
    
                case "min-enrollment-not-met":
                        $model = $model->whereColumn('minimum_enrollment','>','enrolled')
                                       ->orderBy('status','desc');
                    break;
    
                case "get-feedback":
                        $model = $model->whereIn('status',['get_feedback']);
                    break;

                case "initiated":
                        $model = $model->whereIn('status',['initiated']);
                    break;

                case "created":
                        $model = $model->whereIn('status',['created']);
                    break;

                case "invited":
                        $model = $model->whereIn('status',['invited']);
                    break;

                case "accepted":
                        $model = $model->whereIn('status',['accepted']);
                    break;

                case "open_for_enrollment":
                        $model = $model->whereIn('status',['open_for_enrollment']);
                    break;

                case "scheduled":
                        $model = $model->whereIn('status',['scheduled']);
                    break;

                case "in_session":
                        $model = $model->whereIn('status',['in_session']);
                    break;

                case "got_feedback":
                        $model = $model->whereIn('status',['got_feedback']);
                    break;

                case "completed":
                        $model = $model->whereIn('status',['completed']);
                    break;

                case "cancelled":
                        $model = $model->whereIn('status',['cancelled']);
                    break;

                case "closed":
                        $model = $model->whereIn('status',['closed']);
                    break;

                default:
                    break;
            }

        return $model;
    }

}
