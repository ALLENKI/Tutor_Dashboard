<?php

namespace Aham\Http\Controllers\API\Tutor;
use Aham\Models\SQL\City;
use Aham\Models\SQL\User;
use Aham\Models\SQL\Teacher;
use Aham\Models\SQL\CloudinaryImage;

use Aham\Http\Controllers\Controller;
use Aham\Http\Requests;
use Illuminate\Http\Request;

use Aham\Helpers\TeacherHelper;

use League\Fractal;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;
use Dingo\Api\Routing\Helpers;

use League\Fractal\Manager;
use League\Fractal\Serializer\DataArraySerializer;
use League\Fractal\Serializer\ArraySerializer;

use League\Fractal\Pagination\IlluminatePaginatorAdapter;
use Aham\Transformers\AhamClassTransformer;
use Aham\Transformers\AhamClassUnitTransformer;

use Input;
use Validator;
use Carbon;

use Sentinel;

use Aham\Transformers\TeacherTransformer;

use Aham\Managers\TeacherClassesManager;

use Illuminate\Support\Collection;


use Aham\Services\Storage\CDNInterface;


class DashboardController extends BaseController
{
    public function __construct()
    {
        parent::__construct();

    }

    public function index()
    {    
        $user = $this->auth->user();
    
        $teacher = $user->teacher;


        $teacherClassesManager = new TeacherClassesManager($teacher);
     
        $upcomingTimings = $teacherClassesManager->getUpComingClassesTimings();
          
        $ongoingTimings = $teacherClassesManager->getOnGoingClassesTimings(5);

       
        $paginator = $ongoingTimings;
        $classTimings = $paginator->getCollection();
        $resource = new Fractal\Resource\Collection($classTimings, new AhamClassUnitTransformer);
        $resource->setPaginator(new IlluminatePaginatorAdapter($paginator));
        $manager = new Manager();
        $manager->setSerializer(new ArraySerializer());
        $classTimings = $manager->createData($resource)->toArray();

      
         
        return [
            'ongoing_count'  =>  $ongoingTimings->count(),
            'upcoming_count' =>  $upcomingTimings->count(),
            'ongoing_classes'=>  $classTimings
        ];
      
    }



     public function getProfile()
    {
        $user = Sentinel::getUser();
        $user->interested_subjects = $user->interested_subjects;
        $user->why_teacher=$user->why_teacher;
        $user->linkedin=$user->linkedin;
        $user->current_profession=$user->current_profession;
        $user->name=$user->name;
        $user->resume_file=$user->resume_file;
        $user->pan_card=$user->pan_card;
        $user->form_16=$user->form_16;
        $user->Aadhar_card=$user->Aadhar_card;
        $avatar_image = cloudinary_url($user->present()->picture);

        $city= City::find($user->city_id);

        
       $data = ['name' => $user->name,'city_id' => $city,'why_teacher' => $user->why_teacher,'current_profession' =>$user->current_profession,
        'interested_subjects' => $user->interested_subjects,'linkedin'=>$user->linkedin,
       'resume_file'=>$user->resume_file,'pan_card'=>$user->pan_card,'form_16'=>$user->form_16,'cheque'=>$user->cheque,'Aadhar_card'=>
       $user->Aadhar_card,'avatar'=>cloudinary_url($user->present()->picture)];
        
        return $data;
    }

    public function updateProfile()
    {
        
        $rules = [
            'name' => 'required',
            'resume' => 'max:2000|mimes:pdf,doc,docx',
            'aadhar_card' => 'max:2000|mimes:jpeg,pdf,doc,docx',
            'pan_card' => 'max:2000|mimes:jpeg,pdf,doc,docx',
            'Form_16' => 'max:2000|mimes:jpeg,pdf,doc,docx',
            'cheque' => 'max:2000|mimes:jpeg,png,pdf',
            'current_profession' => 'in:Teaching,Research,Technology,Business,Other',
        ];

        $messages = [
        ];

        // $v = Validator::make(Input::all(), $rules, $messages);

        // if ($v->fails()) {
        //     flash()->error('Please check for errors in red.');
        //     return redirect()->back()->withErrors($v->errors())->withInput();
        // }

        $user = Sentinel::getUser();
        $data = Input::only('name','current_profession','why_teacher','linkedin','city_id');
        $data['interested_subjects'] = implode(',',Input::get('interested_subjects'));
        $user->fill($data);
        $user->save();

    }


    public function uploadResume(CDNInterface $cdn)
    {   
        if(Input::file('file') != null){
        $name=input::get('filename');
        //dd($name);
        $user = Sentinel::getUser();
        $formFile = Input::file('file');
        $extension = $formFile->getClientOriginalExtension();
        $filename = $user->username.'-'.time().'.'.$extension;
        $upload_success = $formFile->move(storage_path('uploads'), $filename);
        $data['key'] = 'tutor/profile/'.$filename;
        $data['source'] = storage_path('uploads/'.$filename);
        $result = $cdn->upload($data);
        $url = $result['url'];
        $user->$name= $url;
        $user->save();
       }
     }
    public function updatePassword()
    {
    
     $rules = [
            'old_password' => 'required',
        ];

        $v = Validator::make(Input::all(), $rules);

        if ($v->fails()) {

            return \Response::json(array(
                'success' => false,
                'errors' => $v->getMessageBag()->toArray()

            ), 400);}

        $user = Sentinel::getUser();

        $credentials = [
            //'password'  => $user->password,
            'password' => Input::get('old_password'),
        ];

        $validate = Sentinel::validateCredentials($user, $credentials);

        if(!$validate)
        {
            return \Response::json(array(
                'success' => false,
                'errors' => $v->getMessageBag()->toArray()

            ), 400);

        }

        $credentials = [
            'password' => Input::get('password'),
        ];

        $user = Sentinel::update($user, $credentials);

        return \Response::json(array(
                'success' => true,
                ));
}

public function uploadAvatar()
    {
         $user = Sentinel::getUser();
          if(Input::file('avatar') != null) {
        $filename = time().'.jpg';
        $imagePath = storage_path('uploads/'.$filename);
    
        $formFile = Input::file('avatar');
       $upload_success= $formFile->move(storage_path('uploads'),$filename);
        $result = \Cloudinary\Uploader::upload($imagePath);
        \File::delete($imagePath);
        if($picture = $user->picture)
        {
            $api = new \Cloudinary\Api();
            $api->delete_resources(array($picture->public_id));
            $picture->fill(array_only($result,['public_id','format']));
            $picture->save();
        }
        else
        {
            $picture = new CloudinaryImage(array_only($result,['public_id','format']));
            $picture->type = 'picture';
            $user->picture()->save($picture);
        }
        return response()->json([
                                'success' => true
                            ],200);
        
    }
}

public function getMobile()
{
    $user=Sentinel::getUser();
    $mobile=$user->mobile;
    return $mobile;
}

}






