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
use Aham\Managers\ClassStatusManager;

use League\Fractal\Pagination\IlluminatePaginatorAdapter;

use Input;
use Validator;
use Carbon;

use Aham\Models\SQL\Location;
use Aham\Models\SQL\AhamClass;
use Aham\Models\SQL\ClassTiming;
use Aham\Models\SQL\ClassUnit;
use Aham\Models\SQL\Topic;
use Aham\Models\SQL\SchedulingRule;
use Aham\Models\SQL\StudentAssessment;

use Aham\Helpers\TeacherHelper;
use Aham\Helpers\StudentHelper;
use Aham\Helpers\ClassStatusHelper;

class ClassesController extends BaseController
{
    public function __construct()
    {
        parent::__construct();
    }

    public function index(Request $request)
    {
        $location = Location::where('slug',$request->get('location'))->first();

        $model = AhamClass::with('topic','location','timings.teacher.user')
                            ->where('location_id',$location->id);


        if(Input::has('query'))
        {
            $q = Input::get('query');

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

        

        if(Input::has('selected_states') && !is_null(Input::get('selected_states')))
        {
            $states = explode(',', Input::get('selected_states'));

            $model = $model->whereIn('status',$states);
        }

        if(Input::has('topic_id')){
            $model = $model->where('topic_id',Input::get('topic_id'));
        }

        if(Input::has('teacher_id')){
            $teacher_id = Input::get('teacher_id');
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
        $paginator->appends($request->only('location','selected_states','sort','query'));
        $resource->setPaginator(new IlluminatePaginatorAdapter($paginator));

        $manager = new Manager();
        $manager->setSerializer(new ArraySerializer());
        $classes = $manager->createData($resource)->toArray();

        return $classes;

    }

    public function store(Request $request)
    {

        $rules = [
            'location_id' => 'required|exists:locations,id',
            'topic_id' => 'required|exists:topics,id',
            'minimum_enrollment' => 'required|numeric',
            'maximum_enrollment' => 'required|numeric',
        ];

        $v = Validator::make($request->all(), $rules);

        if ($v->fails()) {

            return $this->response->withArray([
                    'result'=>'error',
                    'code' => 'tmerr002',
                    'messages' => $v->getMessageBag()
                ])->setStatusCode(422);

        }

        $class = AhamClass::create($request->only('location_id','topic_id','minimum_enrollment','maximum_enrollment','charge_multiply'));

        $user = $this->auth->user();

        $class->creator_id = $user->id;

        $class->status = 'initiated';

        if($request->get('free_class'))
        {
            $class->free = true;
        }

        if(!$request->get('pay_tutor'))
        {
            $class->no_tutor_comp = true;
        }

        if(!$request->get('auto_cancel'))
        {
            $class->auto_cancel = false;
        }

        if($request->get('auto_cancel'))
        {
            $class->auto_cancel = true;
        }


        $class->save();

        $topic = Topic::find($request->get('topic_id'));

        $class->topic_name = $topic->name; 
        $class->topic_description = $topic->description; 
        $class->save();

        foreach($topic->units as $unit)
        {
            ClassUnit::create([

                'name' => $unit->name,
                'description' => $unit->description,
                'order' => $unit->order,
                'topic_id' => $topic->id,
                'class_id' => $class->id,
                'original_unit_id' => $unit->id

            ]);
        }

        return $this->response->item($class, new AhamClassTransformer);
       
    }

    public function show($id)
    {
        $class = AhamClass::find($id);

        return $this->response->item($class, new AhamClassTransformer);
    }

    public function update(Request $request,$id)
    {
        $rules = [
            'location_id' => 'required|exists:locations,id',
            'topic_id' => 'required|exists:topics,id',
            'minimum_enrollment' => 'required|numeric',
            'maximum_enrollment' => 'required|numeric',
        ];

        $v = Validator::make($request->all(), $rules);

        if ($v->fails()) {

            return $this->response->withArray([
                    'result'=>'error',
                    'code' => 'tmerr002',
                    'messages' => $v->getMessageBag()
                ])->setStatusCode(422);

        }

        $user = $this->auth->user();

        $newSchedule = false;

        $data = $request->only('location_id','topic_id','minimum_enrollment','maximum_enrollment','charge_multiply');

        $class = AhamClass::find($id);

        $newTopic = Topic::find($data['topic_id']);

        if($class->topic->units->count() != $newTopic->units->count())
        {
            $newSchedule = true;

            // Find class units or replace units
        }
        else
        {
            $topic = Topic::find($data['topic_id']);

            $class->topic_name = $topic->name; 
            $class->topic_description = $topic->description; 
            $class->save();

            $topicUnits = $topic->units;

            foreach($class->classUnits as $classUnit)
            {
                $topicUnit = $topicUnits->pop();

                $classUnitData = [];
                $classUnitData['name'] = $topicUnit->name;
                $classUnitData['description'] = $topicUnit->description;
                $classUnitData['order'] = $topicUnit->order;
                $classUnitData['topic_id'] = $topic->id;
                $classUnitData['original_unit_id'] = $topicUnit->id;

                $classUnit->fill($classUnitData);
                $classUnit->save();

                $timing = ClassTiming::where([
                    'class_id' => $class->id,
                    'class_unit_id' => $classUnit->id,
                    'of_id' => $class->id,
                    'of_type' => get_class($class)
                ])->first();

                $timing->unit_id = $topicUnit->id;
                $timing->save();
            }

        }


        $class->creator_id = $user->id;

        if($request->get('free_class'))
        {
            $class->free = true;
        }

        if(!$request->get('pay_tutor'))
        {
            $class->no_tutor_comp = true;
        }

        if($request->get('pay_tutor'))
        {
            $class->no_tutor_comp = false;
        }

        if(!$request->get('auto_cancel'))
        {
            $class->auto_cancel = false;
        }

        if($request->get('auto_cancel'))
        {
            $class->auto_cancel = true;
        }
        
        $class->fill($data);

        $class->save();

        if($newSchedule)
        {
            $topic = Topic::find($data['topic_id']);

            $class->topic_name = $topic->name; 
            $class->topic_description = $topic->description; 
            $class->save();

            $class->classTimings()->delete();
            $class->classUnits()->delete();

            foreach($topic->units as $unit)
            {
                ClassUnit::create([

                    'name' => $unit->name,
                    'description' => $unit->description,
                    'order' => $unit->order,
                    'topic_id' => $topic->id,
                    'class_id' => $class->id,
                    'original_unit_id' => $unit->id

                ]);
            }





        }

        return $this->response->item($class, new AhamClassTransformer);
    }

    public function scheduleClass(Request $request,$id)
    {
        $ahamClass = AhamClass::find($id);

        $schedulingRule = SchedulingRule::where([
                                'no_of_units' => $ahamClass->classUnits->count(),
                                'division' => $request->get('rule_id')
                            ])->first();

        $units = $request->get('units');

        $conflict = false;
        $cTiming = null;

        foreach ($units as $key => $unit) {
            
            $date = Carbon::createFromTimestamp(strtotime($unit['day_name']));
            $start_time = Carbon::createFromTimestamp(strtotime($unit['time_start_format']));
            $end_time = Carbon::createFromTimestamp(strtotime($unit['time_end_format']));

            $timing = ClassTiming::where([
                            'date' => $date,
                            'classroom_id' => $unit['classroom']
                        ])
                        ->where(function($query) use ($start_time,$end_time){
                            $query->where(function($query) use ($start_time,$end_time) {
                                    $query
                                    ->where('start_time','<=',$start_time)
                                    ->where('end_time','>=',$start_time);
                                  })
                                ->orWhere(function($query) use ($start_time,$end_time) {
                                    $query
                                    ->where('start_time','<=',$end_time)
                                    ->where('end_time','>=',$end_time);
                                });
                        })
                        ->whereNotIn('status',['cancelled'])
                        ->where('class_id','<>',$ahamClass->id)
                        ->first();

            if(!is_null($timing))
            {
                $conflict = true;
                $cTiming = $timing;
            }

        }

        if($conflict)
        {
            return response()->json([
                    'success' => false,
                    'cTiming' => $cTiming
                ],400);
        }

        // dd("go ahead");

        \DB::beginTransaction();

        $alreadyScheduled = false;

        foreach ($units as $key => $unit) 
        {
            $exists = ClassTiming::where([
                            'class_id' => $ahamClass->id,
                            'unit_id' => $unit['unit_id'],
                        ])->first();

            if($exists)
            {
                $alreadyScheduled = true;

                $exists->delete();
            }

        }

        foreach ($units as $key => $unit) 
        {
            $date = Carbon::createFromTimestamp(strtotime($unit['day_name']));
            $start_time = Carbon::createFromTimestamp(strtotime($unit['time_start_format']));
            $end_time = Carbon::createFromTimestamp(strtotime($unit['time_end_format']));

            $classUnit = ClassUnit::where([
                'class_id' => $ahamClass->id, 
                'original_unit_id' => $unit['unit_id']
                ])
                ->first();

            $timing = ClassTiming::firstOrCreate([
                'class_id' => $ahamClass->id,
                'unit_id' => $unit['unit_id'],
                'class_unit_id' => $classUnit->id,
                'of_id' => $ahamClass->id,
                'of_type' => get_class($ahamClass)
            ]);

            $start_date = $date->format('d-m-Y').' '.$unit['time_start_format'];

            $start_date = Carbon::createFromTimestamp(strtotime($start_date));

            if($ahamClass->teacher)
            {
                $timing->teacher_id = $ahamClass->teacher->id;
            }

            $timing->start_time = $start_time;
            $timing->end_time = $end_time;
            $timing->date = $date;
            $timing->classroom_id = $unit['classroom'];
            $timing->location_id = $ahamClass->location->id;

            $timing->save();

            if($key == 0)
            {
                $ahamClass->start_date = $start_date;
                $ahamClass->schedule_cutoff = (clone $start_date)->addMinutes(30);
                $ahamClass->save();
            }

        }

        $ahamClass->maximum_days = $request->get('max_days');
        $ahamClass->scheduling_rule_id = $schedulingRule->id;

        if(!$alreadyScheduled)
        {
            $ahamClass->status = 'open_for_enrollment';
        }

        $ahamClass->save();

        \DB::commit();

        return response()->json([
                'success' => true
            ],200);

    }

    public function changeTeacher($timing)
    {
        $timing = ClassTiming::find($timing);

        $timing->teacher_id = Input::get('teacher.id');
        $timing->save();

        return response()->json([
                'success' => true
            ],200);
    }

    public function markAsDone($timing)
    {

        $timing = ClassTiming::find($timing);

        $ahamClass = $timing->ahamClass;

        \DB::beginTransaction();

        $timing->status = 'done';
        $timing->remarks = Input::get('remarks','');
        $timing->save();

        if($ahamClass->topic->units->count() == $ahamClass->timings()->where('status','done')->count())
        {
            $ahamClass->status = 'get_feedback';
            $ahamClass->save();
        }

        \DB::commit();

        // $ahamClass = $ahamClass->refresh();

        if($ahamClass->status == 'get_feedback')
        {
            event(new \Aham\Events\Teacher\GetFeedback($ahamClass));
        }

        return response()->json([
                'success' => true
            ],200);
    }


    public function cancelClass(Request $request,$id)
    {
        $ahamClass = AhamClass::find($id);

        $ahamClass->status = 'cancelled';
        $ahamClass->cancelled_at = Carbon::now();
        $ahamClass->cancellation_reason = Input::get('remarks','');
        $ahamClass->save();

        foreach($ahamClass->timings as $timing)
        {
            $timing->status = 'cancelled';
            $timing->save();
        }

        ClassStatusManager::giveBackCredits($ahamClass);

        event(new \Aham\Events\AdminCancelledClass($ahamClass));

        return \Response::json(array(
            'success' => true,
            'errors' => [['Class successfully cancelled']]
        ), 200);

    }

    public function enableChat(Request $request,$id)
    {
        $ahamClass = AhamClass::find($id);

        if(Input::get('enable') == 'enable')
        {
            $ahamClass->chat_enable = true;
        }


        if(Input::get('enable') == 'disable')
        {
            $ahamClass->chat_enable = false;
        }

        $ahamClass->save();

        return \Response::json(array(
            'success' => true,
            'errors' => [['Class Chat enable status successfully saved']]
        ), 200);
        
    }

    public function completeClass(Request $request,$id)
    {
        $ahamClass = AhamClass::with('topic','enrollments.student')->find($id);

        $enrollmentCount = $ahamClass->enrollments->count();
        $enrollmentCount = $enrollmentCount >  4 ? $enrollmentCount : 4;

        if($ahamClass->free)
        {
            $enrollmentCount = 4;
        }

        $totalWorth = $enrollmentCount*$ahamClass->classUnits->count()*1000;

        $amount = ($ahamClass->commission/100)*$totalWorth;

        if($ahamClass->no_tutor_comp)
        {
            $amount = 0;
        }

        $teacher = $ahamClass->teacher;

        \DB::beginTransaction();

        $teacher->earnings = $teacher->earnings + $amount;
        $teacher->save();

        $ahamClass->status = 'completed';
        $ahamClass->completed_at = Carbon::now();
        $ahamClass->teacher_amount = $amount;
        $ahamClass->save();

        \DB::commit();

        foreach($ahamClass->enrollments as $enrollment)
        {
            if( $enrollment->feedback != 'ghost' && $enrollment->feedback != 'absent')
            {
               $assessment = StudentAssessment::firstOrCreate([
                    'student_id' => $enrollment->student->id,
                    'topic_id' => $ahamClass->topic->id
                ]);

                $assessment->mode = 'aham_class';
                $assessment->save();
            }

        }

        event(new \Aham\Events\ClassCompleted($ahamClass));

        return \Response::json(array(
            'success' => true,
            'errors' => [['Class successfully completed']]
        ), 200);

    }
}
