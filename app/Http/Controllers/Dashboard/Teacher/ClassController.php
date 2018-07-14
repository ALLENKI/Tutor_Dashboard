<?php

namespace Aham\Http\Controllers\Dashboard\Teacher;

use Validator;
use Input;
use Assets;

use Aham\Models\SQL\AhamClass;
use Aham\Models\SQL\ClassAttachment;
use Aham\Models\SQL\StudentEnrollment;
use Aham\Models\SQL\File;

use Aham\Models\SQL\FbChat;
use Aham\Models\SQL\FbUser;
use Aham\Models\SQL\FbParticipant;

use Aham\Helpers\FBHelper;

class ClassController extends TeacherDashboardBaseController
{
    public function __construct()
    {
        parent::__construct();
    }

    public function show($code)
    {
        Assets::add('js/plugins/forms/jquery-comments/css/jquery-comments.css');
        Assets::add('js/plugins/forms/jquery-comments/js/jquery-comments.js');

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


        $topic = $class->topic;

        $topicFiles = File::where('of_id',$topic->id)->get();
        
        // Check if the current teacher is in classtimings teacher
        if(!in_array($this->teacher->id,$class->classTimings->pluck('teacher_id')->toArray()))
        {
            abort(404);
        }

        // Is FbUser created for this user?

        $fbUser = (new FBHelper())->createUser($this->teacher->user);

        // Is there a FbChat created?

        $fbChat = (new FBHelper())->createChatForClass($class);
        
        // Is FbParticipant created for chat and user

        (new FBHelper())->addParticipant($fbChat,$this->teacher->user);

        // dd(Carbon::createFromTimestamp(1492529974));

        return view('dashboard.teacher.class.show',compact('enrollments','class','fbChat','fbUser','topicFiles'));
    }

    public function learners($code)
    {
        $class = AhamClass::where('code',$code)->first(); 

        $enrollments = $class->enrollments;

        if($this->teacher->id != $class->teacher_id)
        {
            abort(404);
        }

        return view('dashboard.teacher.class.learners',compact('enrollments','class'));

    }


    public function courseDetails($code)
    {
        $class = AhamClass::where('code',$code)->first();

        if($this->teacher->id != $class->teacher_id)
        {
            abort(404);
        }

        $topic = $class->topic;

        return view('dashboard.teacher.class.course_details',compact('enrollments','class','topic'));
    }


    public function goals($code)
    {
        $class = AhamClass::where('code',$code)->first();

        $topic = $class->topic;

        if($this->teacher->id != $class->teacher_id)
        {
            abort(404);
        }

        return view('dashboard.teacher.class.goals',compact('class','topic'));
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
            // $assessed = \Aham\Helpers\StudentHelper::isAssessed($prerequisite, $this->student);

            $node = [];
            $node['id'] = $prerequisite->id;
            $node['label'] = $prerequisite->name;
            $node['title'] = $prerequisite->name;
            $node['mass'] = 5;
            $node['color'] = 'green';
            $nodes[] = $node;

            $edge = [];
            $edge['from'] = $topic->id;
            $edge['to'] = $prerequisite->id;
            $edge['arrows'] = 'from';

            $edges[] = $edge;
        }

        if($this->teacher->id != $class->teacher_id)
        {
            abort(404);
        }

        return view('dashboard.teacher.class.prerequisites',compact('topic','nodes','edges','class'));
    }

    public function feedback($code)
    {
        $class = AhamClass::where('code',$code)->first(); 

        $feedbacks = Input::get('feedback');

        //dd(Input::get('feedback_text'),$feedbacks);

        foreach($feedbacks as $feedback)
        {
            $enrollment = StudentEnrollment::find($feedback['enrollment_id']);

            $enrollment->feedback = $feedback['feedback'];
            $enrollment->remarks = Input::get('feedback_text');
            $enrollment->save();
        }

        $class->status = 'got_feedback';
        $class->save();

        return redirect()->back();
    }

    public function attachments($code)
    {
        $teacher = $this->teacher;
        $user = $teacher->user;

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

    public function calendar(){
        return 'calendar';
    }
    

}
