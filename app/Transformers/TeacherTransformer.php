<?php namespace Aham\Transformers;

use Aham\Models\SQL\Teacher;
use League\Fractal;

use Tymon\JWTAuth\Facades\JWTAuth;

use League\Fractal\ParamBag;

class TeacherTransformer extends Fractal\TransformerAbstract
{
	protected $availableIncludes = [
        'user'
    ];

    protected $defaultIncludes = [
        'user'
    ];

	public function transform(Teacher $teacher)
	{
		$user = $teacher->user;

		$token = JWTAuth::fromUser($user);

		$data['teacher_id'] = $user->teacher->id;
		$data['email'] = $user->email;
		$data['name'] = $user->name;
		$data['mobile'] = $user->mobile;
		$data['token'] = $token;
		$data['code'] = $teacher->code;
		$data['id'] = $teacher->id;
		$data['joined_on'] = $teacher->created_at->format('jS M Y');
		$data['avatar'] = cloudinary_url($user->present()->picture, array('secure' => true));
		$data['interested_in'] = $teacher->interested_in;
		$data['about_me'] = $teacher->about_me;
		$data['ignore_calendar'] = $teacher->ignore_calendar;

		$data['avatar_set'] = false;
		$data['avatar_cloudinary'] = null;

		if($user->picture)
		{		
			$picture['public_id'] = $user->picture->public_id;
			$picture['format'] = $user->picture->format;
			$data['avatar_cloudinary'] = $picture;
			$data['avatar_set'] = true;
		}

	    return $data;
	}

	public function includeUser(Teacher $teacher)
	{
        return $this->item($teacher->user, new UserTransformer);
	}
}