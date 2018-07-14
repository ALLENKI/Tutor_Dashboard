<?php

namespace Aham\Http\Controllers\API\Learner;

use Aham\Http\Controllers\Controller;
use Aham\Http\Requests;
use Illuminate\Http\Request;

use Aham\Helpers\StudentHelper;

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
use Aham\Transformers\AhamEnrollmentUnitTransformer;

use Input;
use Validator;
use Carbon;

use Aham\Managers\CreditsManager;
use Aham\Managers\CouponManager;

use Aham\Transformers\StudentTransformer;

use Aham\Models\SQL\StudentCredits;
use Aham\Models\SQL\Coupon;
use Aham\Models\SQL\StudentEnrollment;
use Aham\Managers\StudentClassesManager;

use Illuminate\Support\Collection;

class DashboardController extends BaseController
{
    public function __construct()
    {
        parent::__construct();

    }

    public function index()
    {    
        $user = $this->auth->user();
    
        $student = $user->student;

        $credits = $student->credits;

        $studentClassesManager = new StudentClassesManager($student);
     
        $upcomingTimings = $studentClassesManager->getUpcomingTimings();
        $ongoingTimings = $studentClassesManager->getOngoingTimings(5);
        $paginator = $ongoingTimings;

        $classEnrollmentUnits = $paginator->getCollection();
        $resource = new Fractal\Resource\Collection($classEnrollmentUnits, new AhamEnrollmentUnitTransformer);
        $resource->setPaginator(new IlluminatePaginatorAdapter($paginator));
        $manager = new Manager();
        $manager->setSerializer(new ArraySerializer());
        $classEnrollmentUnits = $manager->createData($resource)->toArray();
         
        return [
            'total_credits'  =>  $user->creditBuckets()->sum('total_remaining'),
            'ongoing_count'  =>  $ongoingTimings->count(),
            'upcoming_count' =>  $upcomingTimings->count(),
            'ongoing_classes'=>  $classEnrollmentUnits
        ];
      
    }
}