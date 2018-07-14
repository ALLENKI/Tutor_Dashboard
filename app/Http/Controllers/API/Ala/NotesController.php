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
use Aham\Transformers\NoteTransformer;

use League\Fractal\Pagination\IlluminatePaginatorAdapter;

use Input;
use Validator;
use Carbon;

use Aham\Models\SQL\Location;
use Aham\Models\SQL\AhamClass;
use Aham\Models\SQL\ClassTiming;
use Aham\Models\SQL\ClassUnit;
use Aham\Models\SQL\Topic;
use Aham\Models\SQL\Note;
use Aham\Models\SQL\SchedulingRule;
use Aham\Models\SQL\Student;
use Aham\Models\SQL\Teacher;

use Aham\Helpers\TeacherHelper;
use Aham\Helpers\StudentHelper;
use Aham\Helpers\ClassStatusHelper;

class NotesController extends BaseController
{
    use Helpers;

    public function __construct()
    {
        parent::__construct();
    }

    public function getNotesForTeacher(Request $request,$id)
    {
        $teacher = Teacher::find($id);

        return $this->response()->collection($teacher->notes,new NoteTransformer);

    }

    public function addNoteToTeacher(Request $request,$id)
    {
        $rules = [
            'note' => 'required'
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

        $teacher = Teacher::find($id);

        $note = $request->only('note');
        $note['user_id'] = $user->id;
        $note['of_id'] = $teacher->id;
        $note['of_type'] = get_class($teacher);

        Note::create($note);

        return $this->response()->collection($teacher->notes,new NoteTransformer);


    }


    public function getNotesForStudent(Request $request,$id)
    {
        $student = Student::find($id);

        return $this->response()->collection($student->notes,new NoteTransformer);

    }

    public function addNoteToStudent(Request $request,$id)
    {
        $rules = [
            'note' => 'required'
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

        $student = Student::find($id);

        $note = $request->only('note');
        $note['user_id'] = $user->id;
        $note['of_id'] = $student->id;
        $note['of_type'] = get_class($student);

        Note::create($note);

        return $this->response()->collection($student->notes,new NoteTransformer);


    }

    public function getNotesForLocation(Request $request,$id)
    {
        $location = Location::find($id);
        $text = Note::select('note')->get()->pluck('note')->toArray();
        $query = Input::get('query');

        if(Input::has('query')){
            return $this->response()->collection($location->notes()->where('note','LIKE' , '%'. $query .' %')->get(),new NoteTransformer);
        }

        return $this->response()->collection($location->notes,new NoteTransformer);
    }

    public function addNoteToLocation(Request $request,$id)
    {
        $rules = [
            'note' => 'required'
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

        $location = Location::find($id);

        $note = $request->only('note');
        $note['user_id'] = $user->id;
        $note['of_id'] = $location->id;
        $note['of_type'] = get_class($location);

        Note::create($note);

        return $this->response()->collection($location->notes,new NoteTransformer);


    }

    public function getNotesForClass(Request $request,$id)
    {
        $ahamClass = AhamClass::find($id);

        return $this->response()->collection($ahamClass->notes,new NoteTransformer);

    }

    public function AddNoteToClass(Request $request,$id)
    {
        $rules = [
            'note' => 'required'
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

        $ahamClass = AhamClass::find($id);

        $note = $request->only('note');
        $note['user_id'] = $user->id;
        $note['of_id'] = $ahamClass->id;
        $note['of_type'] = get_class($ahamClass);

        Note::create($note);

        return $this->response()->collection($ahamClass->notes,new NoteTransformer);


    }

}
