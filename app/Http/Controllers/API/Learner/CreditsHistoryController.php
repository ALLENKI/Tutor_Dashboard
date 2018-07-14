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

use Input;
use Validator;
use Carbon;

use Aham\Managers\CreditsManager;
use Aham\Managers\CouponManager;

use Aham\Transformers\StudentTransformer;

use Aham\Models\SQL\StudentCredits;
use Aham\Models\SQL\Coupon;
use Aham\Models\SQL\StudentEnrollment;


use Illuminate\Support\Collection;

class CreditsHistoryController extends BaseController
{
    public function __construct()
    {
        parent::__construct();

    }

    public function index2()
    {
        $user = $this->auth->user();

        $student = $user->student;

        $credits = [];

        // credit : type, date, credits, method, remarks

        $statement = new Collection();

        $credits = $user->purchasedCredits()->orderBy('created_at', 'asc')->get();

        foreach($credits as $credit)
        {
            $objectC = new \StdClass;
            $objectC->type = 'credit';
            $objectC->date = $credit->added_on;
            $objectC->credits = $credit->credits;
            $objectC->method = $credit->method;
            $objectC->remarks = $credit->remarks;
            $statement->push($objectC);
        }

        $credits = $user->promotionalCredits()->orderBy('created_at', 'asc')->get();

        foreach($credits as $credit)
        {
            $objectC = new \StdClass;
            $objectC->type = 'credit';
            $objectC->date = $credit->added_on;
            $objectC->credits = $credit->credits;
            $objectC->method = $credit->method;
            $objectC->remarks = 'Credits via coupon: '.$credit->coupon;
            $statement->push($objectC);
        }

        $credits = $user->hubOnlyCredits()->orderBy('created_at', 'asc')->get();

        foreach ($credits as $credit) {
            $objectC = new \StdClass;
            $objectC->type = 'credit';
            $objectC->date = $credit->added_on;
            $objectC->credits = $credit->credits;
            $objectC->method = $credit->method;
            $objectC->remarks = $credit->remarks;
            $statement->push($objectC);
        }

        $credits = $user->usedCredits()->orderBy('created_at', 'asc')->get();

        foreach ($credits as $credit) {
            $objectC = new \StdClass;
            $objectC->type = 'debit';
            $objectC->date = $credit->used_on;
            $objectC->credits = $credit->credits;
            $objectC->method = $credit->credits_type;
            $objectC->remarks = 'Debit for class:'.$credit->of->code;
            $statement->push($objectC);
        }

        $credits = $user->refundedCredits()->orderBy('created_at', 'asc')->get();

        foreach ($credits as $credit) {
            $objectC = new \StdClass;
            $objectC->type = 'credit';
            $objectC->date = $credit->refunded_on;
            $objectC->credits = $credit->credits;
            $objectC->method = 'Refund';
            $objectC->remarks = 'Refund for Class:'.$credit->of->code;
            $statement->push($objectC);
        }

        $statements = $statement->sortByDesc('date');

        $credits = [];

        foreach($statements as $statement)
        {
            $credits[] = [

                'type' => $statement->type,
                'date' => $statement->date,
                'credits' => $statement->credits,
                'method' => $statement->method,
                'remarks' => $statement->remarks,

            ];
        }

        $summary = [
            'total_credits' => $user->creditBuckets()->sum('total_credits'),
            'total_debits' => $user->creditBuckets()->sum('total_credits')-$user->creditBuckets()->sum('total_remaining'),
            'balance' => $user->creditBuckets()->sum('total_remaining'),
        ];

        return [
            'summary' => $summary,
            'data' => $credits,
        ];
    }

    public function index()
    {    
        $user = $this->auth->user();

        $student = $user->student;

        $ahamCredits = $student->ahamCredits;

        $enrollments = StudentEnrollment::where('student_id',$student->id)->get();

        $seriesEnrollments = $student->user->seriesEnrollments;

        $statement = new Collection();

        foreach($ahamCredits as $ahamCredit)
        {
            $remarks = $ahamCredit->remarks;

            if($ahamCredit->method == 'coupon')
            {
                $remarks = 'Credits from coupon: '.$ahamCredit->coupon->coupon.' ('.$ahamCredit->coupon->additional_type.' - '.' '.$ahamCredit->coupon->additional_value.')';
            }

            $objectC = new \StdClass;
            $objectC->type = 'credit';
            $objectC->date = $ahamCredit->created_at;
            $objectC->credits = $ahamCredit->credits;
            $objectC->method = $ahamCredit->method;
            $objectC->remarks = $remarks;


            $statement->push($objectC);
        }


        foreach($enrollments as $enrollment)
        {


            $objectC = new \StdClass;
            $objectC->type = 'debit';
            $objectC->date = $enrollment->ahamClass->start_date;
            $objectC->credits = $enrollment->credits;
            $objectC->method = 'method';
            $objectC->remarks = 'Enrollment for class: '.$enrollment->ahamClass->code.', Topic:'.$enrollment->ahamClass->topic->name;


            $statement->push($objectC);

        }


        foreach($seriesEnrollments as $enrollment)
        {


            $objectC = new \StdClass;
            $objectC->type = 'debit';
            $objectC->date = $enrollment->created_at;
            $objectC->credits = $enrollment->credits;
            $objectC->method = 'enrollment';
            $objectC->remarks = 'Enrollment for workshop: '.$enrollment->level->name;


            $statement->push($objectC);
        }


        $statements = $statement->sortBy('date');

        foreach($statements as $statement)
        {
            $credits[] = [

                'type' => $statement->type,
                'date' => $statement->date,
                'credits' => $statement->credits,
                'method' => $statement->method,
                'remarks' => $statement->remarks,

            ];
        }


        $total_credits = 0;
        $total_debits = 0;
        

        foreach($statements as $stm)
        {


            if ( $stm->type == 'credit' )
             
            {

                $total_credits = $total_credits + $stm->credits;    

            }

            if ( $stm->type == 'debit' )
             
            {
                $total_debits = $total_debits + $stm->credits;    

            }
            
        }

        $balance = $total_credits - $total_debits;

            
        $summary = [

            'total_credits' => $total_credits,
            'total_debits' => $total_debits,
            'balance' => $balance,
        ];

        return ['summary' => $summary,

                'data' => $credits,

                ];
    }

   

}
