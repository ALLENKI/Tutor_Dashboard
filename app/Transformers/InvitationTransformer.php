<?php namespace Aham\Transformers;

use Aham\Models\SQL\ClassInvitation;
use League\Fractal;

use Tymon\JWTAuth\Facades\JWTAuth;

use League\Fractal\ParamBag;

class InvitationTransformer extends Fractal\TransformerAbstract
{
	protected $availableIncludes = [
        'ahamClass'
    ];

    protected $defaultIncludes = [
        'ahamClass'
    ];


	public function transform(ClassInvitation $invitation)
	{
		$data = [];

		$data['id'] = $invitation->id;
		$data['status'] = $invitation->status;
		$data['declination_reason'] = $invitation->declination_reason;
		$data['declination_remarks'] = $invitation->declination_remarks;
		$data['status'] = $invitation->status;
		$data['created_at'] = $invitation->created_at->format('jS M Y');

	    return $data;
	}

	public function includeAhamClass(ClassInvitation $invitation)
	{
        return $this->item($invitation->ahamClass, new AhamClassTransformer);
	}

}