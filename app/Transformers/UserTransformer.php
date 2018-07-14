<?php namespace Aham\Transformers;

use Aham\Models\SQL\User;
use League\Fractal;

use Tymon\JWTAuth\Facades\JWTAuth;

use League\Fractal\ParamBag;

class UserTransformer extends Fractal\TransformerAbstract
{
	public function transform(User $user)
	{
		$token = JWTAuth::fromUser($user);

    	$payload = JWTAuth::decode(JWTAuth::setToken($token)->getToken());

		$data['id'] = $user->id;
		$data['email'] = $user->email;
		$data['name'] = $user->name;
		$data['mobile'] = $user->mobile;
		$data['token'] = $token;
        $data['expiration'] = date('d M Y h:i', $payload['exp']);
		$data['avatar'] = cloudinary_url($user->present()->picture, array('secure' => true));

		$data['avatar_set'] = false;
		$data['avatar_cloudinary'] = null;

		$data['total_remaining'] = $this->totalCredits($user);

		if($user->picture)
		{		
			$picture['public_id'] = $user->picture->public_id;
			$picture['format'] = $user->picture->format;
			$data['avatar_cloudinary'] = $picture;
			$data['avatar_set'] = true;
		}

	    return $data;
	}

	public function totalCredits($user)
	{

		// total credits
		$credits = $user->creditBuckets()->orderBy('created_at', 'asc')->get();
		$total_remaining = 0;

        foreach ($credits as $credit) {

            $total_remaining += ($credit->purchased_remaining + $credit->hub_only_remaining + $credit->promotional_remaining);
			
		}

		return $total_remaining;
	}

}