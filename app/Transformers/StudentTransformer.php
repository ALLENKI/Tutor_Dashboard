<?php namespace Aham\Transformers;

use Aham\Models\SQL\Student;
use Aham\Models\SQL\Topic;
use League\Fractal;
use Aham\Models\SQL\StudentCredits;

use Tymon\JWTAuth\Facades\JWTAuth;

use League\Fractal\ParamBag;

class StudentTransformer extends Fractal\TransformerAbstract
{
	protected $availableIncludes = [
        'user'
    ];

    protected $defaultIncludes = [
        'user'
    ];

	public function transform(Student $student)
	{
		$user = $student->user;

		$token = JWTAuth::fromUser($user);

		$data['email'] = $user->email;
		$data['name'] = $user->name;
		$data['mobile'] = $user->mobile;
		$data['token'] = $token;
		$data['code'] = $student->code;
		$data['curriculum'] = $student->curriculum;
		$data['school'] = $student->school;
		$data['community'] = $student->communities;
	    $data['credits'] = $student->credits;
		$data['id'] = $student->id;
		$data['grade'] = $student->user->grade;
		$data['joined_on'] = $student->created_at->format('jS M Y');
		$data['avatar'] = cloudinary_url($user->present()->picture, array('secure' => true));
		$data['avatar_set'] = false;
		$data['avatar_cloudinary'] = null;

		if($user->picture)
		{		
			$picture['public_id'] = $user->picture->public_id;
			$picture['format'] = $user->picture->format;
			$data['avatar_cloudinary'] = $picture;
			$data['avatar_set'] = true;
		}

		$data['selected_days_of_week'] = [];

		if($student->selected_days_of_week != '')
		{
			$data['selected_days_of_week'] = explode(',', $student->selected_days_of_week);
		}

		$data['selected_times_of_day'] = [
            'sunday' =>  [],
            'monday' =>  [],
            'tuesday' =>  [],
            'wednesday' =>  [],
            'thursday' =>  [],
            'friday' =>  [],
            'saturday' =>  [],
		];

		// dd(unserialize($student->selected_times_of_day));

		if($student->selected_times_text != '')
		{
			try{
				$data['selected_times_of_day'] = unserialize($student->selected_times_text);

				if(!count($data['selected_times_of_day']))
				{
					$data['selected_times_of_day'] = [
			            'sunday' =>  [],
			            'monday' =>  [],
			            'tuesday' =>  [],
			            'wednesday' =>  [],
			            'thursday' =>  [],
			            'friday' =>  [],
			            'saturday' =>  [],
					];
				}

			}
			catch(\Exception $e)
			{
				$data['selected_times_of_day'] = [
		            'sunday' =>  [],
		            'monday' =>  [],
		            'tuesday' =>  [],
		            'wednesday' =>  [],
		            'thursday' =>  [],
		            'friday' =>  [],
		            'saturday' =>  [],
				];
			}
		}

		$data['selected_subjects'] = Topic::whereIn('id',explode(',', $student->selected_subjects))
					                            ->orderBy('name','asc')
					                            ->select(['topics.id','topics.slug','topics.name','topics.minimum_enrollment','topics.maximum_enrollment','topics.type'])
					                            ->get();

	    return $data;
	}

	public function includeUser(Student $student)
	{
        return $this->item($student->user, new UserTransformer);
	}

}
