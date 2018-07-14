<?php

namespace Aham\Http\Controllers\Dashboard\Student;

use Validator;
use Input;
use Assets;
use Carbon;

use Aham\Models\SQL\AhamClass;
use Aham\Models\SQL\StudentEnrollment;
use Aham\Models\SQL\ClassAttachment;
use Aham\Models\SQL\File;

use Aham\Models\SQL\FbChat;
use Aham\Models\SQL\FbUser;
use Aham\Models\SQL\FbParticipant;

use Aham\Helpers\FBHelper;



class ClassController extends StudentDashboardBaseController
{
    public function __construct()
    {
        parent::__construct();
    }

    public function show($code)
    {
        Assets::add('js/plugins/forms/jquery-comments/css/jquery-comments.css');
        Assets::add('js/plugins/forms/jquery-comments/js/jquery-comments.js');

        Assets::add('js/plugins/star-rating/star-rating.min.css');
        Assets::add('js/plugins/star-rating/star-rating.min.js');

        Assets::add('https://cdnjs.cloudflare.com/ajax/libs/lodash.js/4.15.0/lodash.min.js');
        Assets::add('https://cdnjs.cloudflare.com/ajax/libs/underscore.js/1.8.3/underscore-min.js');
        Assets::add('https://cdnjs.cloudflare.com/ajax/libs/mustache.js/2.2.1/mustache.min.js');
        
        Assets::add('https://ajax.googleapis.com/ajax/libs/jqueryui/1.8.9/themes/base/jquery-ui.css');
        Assets::add('https://ajax.googleapis.com/ajax/libs/jqueryui/1.10.2/jquery-ui.min.js');
        Assets::add('js/plugins/forms/plupload/jquery.ui.plupload/css/jquery.ui.plupload.css');
        Assets::add('js/plugins/forms/plupload/plupload.full.min.js');
        Assets::add('js/plugins/forms/plupload/jquery.ui.plupload/jquery.ui.plupload.js');
        Assets::add('js/plugins/md5.min.js');

        $bucket = 'ahamattachments';
        $region = "us-east-1" ;
        $accessKeyId = 'AKIAJB4S34DZN75SHUNA';
        $secret = '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';

        $policy = base64_encode(json_encode(array(
            'expiration' => date('Y-m-d\TH:i:s.000\Z', strtotime('+1 day')),  
            'conditions' => array(
                array('bucket' => $bucket),
                array('acl' => 'public-read'),
                array('starts-with', '$key', ''),
                array('starts-with', '$Content-Type', ''),
                array('starts-with', '$name', ''),  
                array('starts-with', '$Filename', ''), 
            )
        )));

        $signature = base64_encode(hash_hmac('sha1', $policy, $secret, true));

        view()->share('bucket',$bucket);
        view()->share('accessKeyId',$accessKeyId);
        view()->share('policy',$policy);
        view()->share('signature',$signature);

        $class = AhamClass::where('code',$code)->first();

        $enrollments = $class->enrollments->pluck('student_id')->toArray();

        if(!in_array($this->student->id, $enrollments))
        {
            abort(404);
        }

        $enrollment = StudentEnrollment::where([
                'class_id' => $class->id,
                'student_id' => $this->student->id
            ])->first();


        $topicFiles = File::where('of_id',$class->topic->id)->get();

        // dd("Casaa");

        // Is FbUser created for this user?

        $fbUser = (new FBHelper())->createUser($this->student->user);

        // Is there a FbChat created?

        $fbChat = (new FBHelper())->createChatForClass($class);
        
        // Is FbParticipant created for chat and user

        (new FBHelper())->addParticipant($fbChat,$this->student->user);

        // dd(Carbon::createFromTimestamp(1492529974));


        return view('dashboard.student.class.show',compact('enrollments','class','enrollment','fbUser','fbChat','topicFiles'));
    }

    public function courseDetails($code)
    {
        $class = AhamClass::where('code',$code)->first();

        $enrollments = $class->enrollments->pluck('student_id')->toArray();

        if(!in_array($this->student->id, $enrollments))
        {
            abort(404);
        }

        $topic = $class->topic;

        return view('dashboard.student.class.course_details',compact('enrollments','class','topic'));
    }

    public function feedback($code)
    {
        $rules = [
            'teacher_rating' => 'required|integer|min:1',
            'overall_rating' => 'required|integer|min:1',
        ];

        $messages = [
            'teacher_rating.min' => 'Please give a rating for the teacher',
            'overall_rating.min' => 'Please rate how was your overall experience',
        ];

        $v = Validator::make(Input::all(), $rules, $messages);

        if ($v->fails()) {

            if(request()->ajax())
            {
                return response([
                    'messages' => $v->errors()
                ],400);
            }

            flash()->error('Please check for errors in red.');
            return redirect()->back()->withErrors($v->errors())->withInput();

        }

        $class = AhamClass::where('code',$code)->first();

        $enrollment = StudentEnrollment::where([
                'class_id' => $class->id,
                'student_id' => $this->student->id
            ])->first();

        $enrollment->teacher_rating = Input::get('teacher_rating');
        $enrollment->overall_rating = Input::get('overall_rating');
        $enrollment->remarks = Input::get('remarks');
        $enrollment->rating_given = true;
        $enrollment->save();

        if(request()->ajax())
        {
            return response('Success',200);
        }

        flash()->success('Thank you. Feedback successfully saved.');
        return redirect()->back();

    }


    public function goals($code)
    {
        $class = AhamClass::where('code',$code)->first();

        $topic = $class->topic;

        $enrollments = $class->enrollments->pluck('student_id')->toArray();

        if(!in_array($this->student->id, $enrollments))
        {
            abort(404);
        }

        return view('dashboard.student.class.goals',compact('enrollments','class','topic'));
    }


    public function prerequisites($code)
    {

        $class = AhamClass::where('code',$code)->first();

        Assets::add('js/plugins/visualization/vis/vis.min.css');     
        Assets::add('js/plugins/visualization/vis/vis.min.js');     

        $topic = $class->topic;  

        $nodes = [];
        $edges = [];

        $node = [];
        $node['id'] = $topic->id;
        $node['label'] = $topic->name;
        $node['title'] = $topic->name;
        $node['mass'] = 10;
        $nodes[] = $node;

        foreach($topic->prerequisites as $prerequisite)
        {
            $assessed = \Aham\Helpers\StudentHelper::isAssessed($prerequisite, $this->student);

            $node = [];
            $node['id'] = $prerequisite->id;
            $node['label'] = $prerequisite->name;
            $node['title'] = $prerequisite->name;
            $node['mass'] = 5;
            $node['color'] = $assessed ? 'green' : 'orange';
            $nodes[] = $node;

            $edge = [];
            $edge['from'] = $topic->id;
            $edge['to'] = $prerequisite->id;
            $edge['arrows'] = 'from';

            $edges[] = $edge;
        }

        $enrollments = $class->enrollments->pluck('student_id')->toArray();

        if(!in_array($this->student->id, $enrollments))
        {
            abort(404);
        }

        return view('dashboard.student.class.prerequisites',compact('topic','nodes','edges','class','enrollments'));
    }

    public function attachments($code)
    {
        $student = $this->student;
        $user = $student->user;

        $class = AhamClass::where('code',$code)->first();

        $class->attachments()->delete();

        foreach(Input::get('attachments',[]) as $identifier => $attachment)
        {
            $attachment = new ClassAttachment([
                    'description' => $attachment['description'],
                    'identifier' => $identifier,
                    'file_path' => $attachment['file'],
                    'file_name' => $attachment['original'],
                    'uploader_id' => $attachment['uploader_id'],
                    'uploader_role' => $attachment['uploader_role']
                ]);

            $class->attachments()->save($attachment);
        }

        return redirect()->back();
    }

}