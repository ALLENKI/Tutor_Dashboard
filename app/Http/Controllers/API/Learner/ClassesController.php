<?php

namespace Aham\Http\Controllers\API\Learner;

use Aham\Http\Controllers\Controller;
use Aham\Http\Requests;
use Illuminate\Http\Request;
use Aham\Helpers\TeacherHelper;


use League\Fractal\Pagination\IlluminatePaginatorAdapter;

use Input;
use Validator;
use Carbon;

use League\Fractal;
use League\Fractal\Manager;
use League\Fractal\Serializer\DataArraySerializer;
use League\Fractal\Serializer\ArraySerializer;

use Aham\Managers\StudentClassesManager;

use Aham\Transformers\AhamClassTransformer;
use Aham\Transformers\AhamClassUnitTransformer;
use Aham\Transformers\AhamEnrollmentUnitTransformer;

use Aham\Models\SQL\ClassInvitation;
use Aham\Models\SQL\StudentInvitation;

use Aham\Models\SQL\AhamClass;
use Aham\Models\SQL\StudentEnrollment;
use Aham\Models\SQL\ClassAttachment;
use Aham\Models\SQL\User;
use Aham\Models\SQL\ClassTiming;
use Aham\Models\SQL\Topic;
use Aham\Models\SQL\FbChat;
use Aham\Models\SQL\FbUser;
use Aham\Models\SQL\FbParticipant;
use Aham\Models\SQL\StudentEnrollmentUnit;
use Aham\Models\SQL\Student;

use Aham\Managers\EnrollmentManager;

use Aham\Helpers\FBHelper;

class ClassesController extends BaseController
{
    public function __construct()
    {
        parent::__construct();

    }

    public function index()
    {    
    	$filter = Input::get('filter','completed');

        $user = $this->auth->user();

        $student = $user->student;

        $studentClassesManager = new StudentClassesManager($student);

                                
        switch ($filter) {

            case 'upcoming':
                $filteredTimings = $studentClassesManager->getUpcomingTimings();
                break;

            case 'on-going':
                $filteredTimings = $studentClassesManager->getOngoingTimings();
                break;

            case 'completed':
                $filteredTimings = $studentClassesManager->getCompletedTimings();
                break;

            default:
                $filteredTimings = $studentClassesManager->getCompletedTimings();
                break;
        }


        $paginator = $filteredTimings;

        $classEnrollmentUnits = $paginator->getCollection();

        $resource = new Fractal\Resource\Collection($classEnrollmentUnits, new AhamEnrollmentUnitTransformer);
        $resource->setPaginator(new IlluminatePaginatorAdapter($paginator));
        $manager = new Manager();
        $manager->setSerializer(new ArraySerializer());
        $classEnrollmentUnits = $manager->createData($resource)->toArray();

        return $classEnrollmentUnits;
    }

    public function recommended()
    {
        $user = $this->auth->user();

        $student = $user->student;

        $invitations = $student->invitations->pluck('class_id')->toArray();

        $enrollments = $student->enrollments->pluck('class_id')->toArray();

        $model = AhamClass::whereIn('status',['open_for_enrollment','scheduled'])
                            ->whereIn('id',$invitations)
                            ->whereNotIn('id',$enrollments)
                            ->where('start_date','>=',Carbon::now())
                            ->orderBy('start_date','asc');

        $paginator = $model->paginate(Input::get('per_page',10));

        $classes = $paginator->getCollection();

        $resource = new Fractal\Resource\Collection($classes, new AhamClassTransformer);
        // $paginator->appends($request->only('location','selected_states','sort','query'));
        $resource->setPaginator(new IlluminatePaginatorAdapter($paginator));

        $manager = new Manager();
        $manager->setSerializer(new ArraySerializer());
        $classes = $manager->createData($resource)->toArray();

        return $classes;

    }

    public function markAsNotInterested($id)
    {
        $user = $this->auth->user();

        $student = $user->student;

        $invitation = StudentInvitation::where([
            'class_id' => $id,
            'student_id' => $student->id,
        ])->first();

        if(!is_null($invitation))
        {
            $invitation->status = 'not_interested';
            $invitation->save();
        }

        return $this->response->withArray(array(
            'success' => true,
        ), 200); 
    }
    
    public function browse()
    {
        $user = $this->auth->user();

        $student = $user->student;

        $enrollments = $student->enrollments->pluck('class_id')->toArray();

        $q = Input::get('q','');

        $classes = AhamClass::with('topic')
                    ->where(function ($query) use ($q) {

                            $query->whereHas('topic', function($query) use ($q)
                            {
                                $query
                                    ->where('name', 'LIKE', '%'.$q.'%');
                            });
                    })
                    ->where('start_date','>=',Carbon::now()->addHours(6))
                    ->where('location_id',Input::get('location_id',2))
                    ->whereIn('status',['open_for_enrollment','scheduled'])
                    ->whereNotIn('id',$enrollments)
                    ->orderBy('start_date','asc')
                    ->paginate(Input::get('per_page',10));     

        return $this->response->paginator($classes, new AhamClassTransformer);
    }


    public function show($id)
    {    

        $user = $this->auth->user();

        $student = $user->student;

        $model = AhamClass::find($id);

        return $this->response->item($model, new AhamClassTransformer);
    }

    public function chatRequired($class)
    {
        $user = $this->auth->user();

        $class = AhamClass::find($class);

        $fbUser = (new FBHelper())->createUser($user);

        // Is there a FbChat created?

        $fbChat = (new FBHelper())->createChatForClass($class);
        
        (new FBHelper())->syncParticipants($fbChat,$class);

        // Is FbParticipant created for chat and user

        // (new FBHelper())->addParticipant($fbChat,$user);

        return [
            'fbUser' => $fbUser,
            'fbChat' => $fbChat,
            'classId' => $class->id,
            'fb_prefix' => env('FB_PREFIX'),
            'photoUrl' => cloudinary_url($fbUser->user->present()->picture, ['secure' => true]),
            'userId' => $fbUser->user->id,
            'userName' => $fbUser->user->name,
            'altName' => $fbUser->user->name,
            'hideName' => false,
            'locationId' => $class->location->id,
            'messageThread' => env('FB_PREFIX').'/messageThread/'.$fbChat->thread,
            'messageThreadMetadata' => env('FB_PREFIX').'/messageThreadMetadata/'.$fbChat->thread
        ];
    }

    public function studentFeedback($code)
    {

        $rules = [
            'teacher_rating' => 'required|integer|min:1',
            'overall_rating' => 'required|integer|min:1',
        ];


        $v = Validator::make(Input::all(), $rules);

        if ($v->fails()) {
            flash()->error('Please check for errors in red.');
            return redirect()->back()->withErrors($v->errors())->withInput();
        }


        $class = AhamClass::where('code',$code)->first();



        $user = $this->auth->user();

        $student = $user->student;  

        $enrollment = StudentEnrollment::where([
                'class_id' => $class->id,
                'student_id' => $student->id
            ])->first();


        $enrollment->teacher_rating = Input::get('teacher_rating');
        $enrollment->overall_rating = Input::get('overall_rating');
        $enrollment->remarks = Input::get('remarks','');
        $enrollment->rating_given = true;
        $enrollment->save();

        return $this->response->withArray(array(
            'success' => true,
        ), 200); 


    }

}
