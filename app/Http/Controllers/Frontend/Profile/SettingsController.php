<?php

namespace Aham\Http\Controllers\Frontend\Profile;
use Aham\Services\Storage\CDNInterface;
use Aham\TDGateways\UserGatewayInterface;
use View;
use Sentinel;
use Input;
use Validator;

use Aham\Models\SQL\Locality;
use Aham\Models\SQL\City;

use Aham\Http\Controllers\Frontend\BaseController;
use Aham\Models\SQL\CloudinaryImage;

class SettingsController extends BaseController
{
    public function __construct(CDNInterface $cdn, UserGatewayInterface $userGateway)
    {
        parent::__construct();

        $this->cdn = $cdn;
        $this->userGateway = $userGateway;
    }

    public function getSettings()
    {
        $cities = City::pluck('name','id');
        $localities = Locality::pluck('name','id');

        return view('frontend.settings.profile',compact('cities','localities'));
    }

    public function postSettings()
    {
        $rules = [
            'name' => 'required',
            'interested_in' => 'required|in:user,student,teacher,student_teacher'
        ];

        $v = Validator::make(Input::all(), $rules);

        if ($v->fails()) {
            flash()->error('Please check for errors in red.');
            return redirect()->back()->withErrors($v->errors())->withInput();
        }

        $this->userGateway->updateUser(Sentinel::getUser(), Input::only('name','interested_in', 'locality_id', 'city_id'));

        $user = Sentinel::getUser();

        if($student = $user->student)
        {
            $student->fill(Input::only('grade','curriculum'));
            $student->save();
        }

        if($teacher = $user->teacher)
        {
            $teacher->fill(Input::only('about_me','description'));
            $teacher->save();
        }

        flash()->success('Successfully updated.');
        return redirect()->back();
    }

    public function uploadAvatar()
    {
        $user = Sentinel::getUser();

        $filename = time().'.jpg';

        $imagePath = storage_path('uploads/'.$filename);

        $parts = explode(',', Input::get('image-data'));

        $image = base64_decode($parts[1]);

        \File::put($imagePath,$image);

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

        flash()->overlay('Successfully changed','Success');

        return redirect()->back();
    }


    public function getPassword()
    {
        return view('frontend.settings.password');
    }

    public function updatePassword()
    {
        $rules = [
            'old_password' => 'required',
            'password' => 'required|confirmed',
        ];

        $v = Validator::make(Input::all(), $rules);

        if ($v->fails()) {
            flash()->error('Please check for errors in red.');
            return redirect()->back()->withErrors($v->errors())->withInput();
        }

        $user = Sentinel::getUser();

        $credentials = [
            'email'    => $user->email,
            'password' => Input::get('old_password'),
        ];

        $validate = Sentinel::validateCredentials($user, $credentials);

        if(!$validate)
        {
            flash()->error('Old password does not match');
            return redirect()->back();
        }

        $credentials = [
            'password' => Input::get('password'),
        ];

        $user = Sentinel::update($user, $credentials);

        flash()->overlay('Successfully changed','Success');

        return redirect()->back();
    }
}
