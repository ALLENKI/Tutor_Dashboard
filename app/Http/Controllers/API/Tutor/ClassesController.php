<?php

namespace Aham\Http\Controllers\API\Tutor;

use Aham\Http\Controllers\Controller;
use Aham\Http\Requests;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Aham\Helpers\TeacherHelper;

use League\Fractal;
use League\Fractal\Manager;
use League\Fractal\Serializer\DataArraySerializer;
use League\Fractal\Serializer\ArraySerializer;
use League\Fractal\Pagination\IlluminatePaginatorAdapter;

use Input;
use Validator;

use Aham\Managers\TeacherClassesManager;

use Aham\Transformers\AhamClassTransformer;
use Aham\Transformers\AhamClassUnitTransformer;
use Aham\Transformers\EnrollmentTransformer;
use Aham\Models\SQL\ClassInvitation;
use Aham\Models\SQL\ClassTiming;

use Aham\Models\SQL\Note;
use Aham\Transformers\NoteTransformer;


use Aham\Models\SQL\AhamClass;
use Aham\Models\SQL\Student;
use Aham\Models\SQL\StudentEnrollment;
use Aham\Models\SQL\User;

use Aham\Models\SQL\Teacher;

use Aham\Models\SQL\FbChat;
use Aham\Models\SQL\FbUser;
use Aham\Models\SQL\FbParticipant;

use Aham\Helpers\FBHelper;

 use Aham\Models\SQL\Topic;
 use Aham\Models\SQL\TopicPrerequisite;
 use Aham\CourseCatalog\TopicHelper;
 use Aham\Services\Storage\CDNInterface;
 use Aham\Models\SQL\File;
 



class ClassesController extends BaseController
{
    public function __construct()
    {
        parent::__construct();

    }

    public function index()
    {    
    	$filter = Input::get('filter','all');

        $user = $this->auth->user();

        $teacher = $user->teacher;


        // $model = $teacher->classes();
        $teacherClassesManager = new TeacherClassesManager($teacher);
        switch ($filter) {
  
            case 'upcoming':
                 $filteredTimings = $teacherClassesManager->getUpcomingClassesTimings();
                break;

            case 'on-going':
                 $filteredTimings = $teacherClassesManager->getOnGoingClassesTimings();
                break;

            case 'completed':
                $filteredTimings = $teacherClassesManager->getCompletedClassesTimings();
                break;

            default:
                $filteredTimings = $teacherClassesManager->getCompletedClassesTimings();
            
                break;
        }

        $paginator = $filteredTimings;


        $classTimings = $paginator->getCollection();

        $resource = new Fractal\Resource\Collection($classTimings, new AhamClassUnitTransformer);
        $resource->setPaginator(new IlluminatePaginatorAdapter($paginator));

        $manager = new Manager();
        $manager->setSerializer(new ArraySerializer());
        $classTimings = $manager->createData($resource)->toArray();

        return $classTimings;

    }

    public function show($id)
    {    

        $user = $this->auth->user();

        $teacher = $user->teacher;

        $model = AhamClass::find($id);

        // dd($students);

        return $this->response->item($model, new AhamClassTransformer);
    }

    public function chatRequired($class)
    {
        $user = $this->auth->user();

        $class = AhamClass::find($class);

        $fbUser = (new FBHelper())->createUser($user);

        // Is there a FbChat created?

        $fbChat = (new FBHelper())->createChatForClass($class);
        
        // Is FbParticipant created for chat and user

        (new FBHelper())->syncParticipants($fbChat,$class);

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

    public function tutorFeedback($code)
    {

        // dd(Input::get('feedbacks'));

        $user = $this->auth->user();

        $teacher = $user->teacher;

        // dd($feedbacks);

        $class = AhamClass::where('code',$code)->first(); 

        $feedbacks = Input::get('feedbacks');

        foreach($feedbacks as $feedback)
        {
            $enrollment = StudentEnrollment::find($feedback['enrollment_id']);

            $enrollment->feedback = $feedback['feedback'];
            $enrollment->tutor_remarks = $feedback['tutor_remarks'];
            $enrollment->save();
        }

        $class->status = 'got_feedback';
        $class->tutor_feedback = true;
        $class->save();

        return $this->response->withArray([
            'result'=>'success',
            'messages' => 'Added successfully'
        ])->setStatusCode(200);
        
    }

    public function uploadDoc($id,CDNInterface $cdn)
    {
        // dd(Input::file('image'));
        $topic = Topic::find($id);
        if(Input::file('image') != null) {
            //$code =property_exists($topic,'code');
            $formFile = Input::file('image');
            $extension = $formFile->getClientOriginalExtension();
            $filename = time() . '.' . $extension;
            $upload_success = $formFile->move(storage_path('uploads'), $filename);
            $data['key'] = 'classes/file/' . $filename;
            $data['source'] = storage_path('uploads/' . $filename);
            $result = $cdn->upload($data);
            $fileUrl = $result['url'];
            if(Input::get('filename')) {
                $filename = Input::get('filename');

            } 
             dd($filename);
            File::create([
                'user_id' => $this->auth->user()->id,
                'file_name' => $filename,
                'file_url' => $fileUrl,
                'of_id' => $topic->id,
                'of_type' => Topic::class,
                'mime_type' => $extension,
            ]);
            //File::delete(storage_path('uploads/' . $filename));
        }
    }

    public function getNotesForClass($classId)
    {
        $ahamClass = AhamClass::find($classId);

        return $this->response()->collection($ahamClass->notes,new NoteTransformer);
    }

    public function addNotesToClass($classId)
    {
        $rules = [
            'note' => 'required'
        ];

        $v = \Validator::make(Input::all(), $rules);

        if ($v->fails()) {

            return $this->response->withArray([
                    'result'=>'error',
                    'code' => 'tmerr002',
                    'messages' => $v->getMessageBag()
                ])->setStatusCode(422);

        }

        $user = $this->auth->user();

        $ahamClass = AhamClass::find($classId);

        $note = [];
        
        $note['note'] = Input::get('note');
        $note['user_id'] = $user->id;
        $note['of_id'] = $ahamClass->id;
        $note['of_type'] = get_class($ahamClass);

        Note::create($note);

        return $this->response()->collection($ahamClass->notes,new NoteTransformer);
    }
}




