<?php  namespace Aham\Http\Controllers\Backend\TopicTree;

use Aham\Http\Controllers\Backend\BaseController;

use Validator;
use Input;
use Assets;
use Response;
use DB;

use Aham\Models\SQL\Topic;
use Aham\Models\SQL\CloudinaryImage;

class TopicPictureController extends BaseController {

    public function __construct()
    {
        parent::__construct();
    }

    public function uploadPicture($id)
    {
        $rules = [
            'picture' => 'required|image',
        ];

        $v = Validator::make(Input::all(), $rules);

        if ($v->fails()) {
            flash()->error('There were errors, Please check.');
            return redirect()->back()->withErrors($v->errors())->withInput();
        }

        $topic = Topic::find($id);

        $formFile = Input::file('picture');

        $extension = $formFile->getClientOriginalExtension();

        $filename = $topic->slug.'-'.time().'.'.$extension;

        $upload_success = $formFile->move(storage_path().'/uploads', $filename);

        $result = \Cloudinary\Uploader::upload(storage_path().'/uploads/'.$filename);

        \File::delete(storage_path().'/uploads/'.$filename);

        if($picture = $topic->picture)
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

            $topic->picture()->save($picture);
        }

        return redirect()->back();
    }


    public function uploadCoverPicture($id)
    {
        $rules = [
            'cover_picture' => 'required|image',
        ];

        $v = Validator::make(Input::all(), $rules);

        if ($v->fails()) {
            flash()->error('There were errors, Please check.');
            return redirect()->back()->withErrors($v->errors())->withInput();
        }

        $topic = Topic::find($id);

        $formFile = Input::file('cover_picture');

        $extension = $formFile->getClientOriginalExtension();

        $filename = $topic->slug.'-'.time().'.'.$extension;

        $upload_success = $formFile->move(storage_path().'/uploads', $filename);

        $result = \Cloudinary\Uploader::upload(storage_path().'/uploads/'.$filename);

        \File::delete(storage_path().'/uploads/'.$filename);

        if($picture = $topic->coverPicture)
        {
            $api = new \Cloudinary\Api();

            $api->delete_resources(array($picture->public_id));

            $picture->fill(array_only($result,['public_id','format']));

            $picture->save();
        }
        else
        {
            $picture = new CloudinaryImage(array_only($result,['public_id','format']));

            $picture->type = 'cover';

            $topic->coverPicture()->save($picture);
        }

        return redirect()->back();
    }

}

