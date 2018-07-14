<?php namespace Aham\Transformers;

use Carbon;
use Aham\Models\SQL\AhamClass;
use Aham\Models\SQL\ClassTiming;
use Aham\Models\SQL\ClassUnit;
use League\Fractal;

use Tymon\JWTAuth\Facades\JWTAuth;

use League\Fractal\ParamBag;

class ClassTimingTransformer extends Fractal\TransformerAbstract
{

	protected $availableIncludes = [
        
    ];

    protected $defaultIncludes = [
        
    ];

	public function transform(ClassTiming $classTiming)
	{
		
	
		$classTiming->slot_id = Carbon::parse( $classTiming->date )->format('Y-m-d');
		

	    return $classTiming;
	}

}