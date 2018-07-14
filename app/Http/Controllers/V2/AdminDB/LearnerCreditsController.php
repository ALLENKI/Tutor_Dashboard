<?php

namespace Aham\Http\Controllers\V2\AdminDB;

use Aham\Http\Controllers\Controller;
use Aham\Repositories\LearnerRepository;
use Aham\CreditsEngine\Sync;
use Validator;
use Input;
use Aham\Models\SQL\Location;
use Aham\Models\SQL\Student;
use Aham\Models\SQL\Coupon;
use Aham\Models\SQL\StudentEnrollment;
use Aham\CreditsEngine\Add;

use \Illuminate\Database\Eloquent\Collection;

use League\Fractal;
use League\Fractal\Manager;
use League\Fractal\Serializer\ArraySerializer;
use League\Fractal\Pagination\IlluminatePaginatorAdapter;
use Aham\TransformersV2\LearnerTransformer;
use Aham\Managers\CouponManager;
use Aham\Http\Controllers\API\Learner\CreditsHistoryController;


class LearnerCreditsController extends BaseController
{
    private $learner;

    public function __construct(LearnerRepository $learner)
    {
        $this->learner = $learner;
    }

    public function credits($id)
    {
        $learner = $this->learner->find($id);;
        $user = $learner->user;
        $creditsUsage = [];
        
        $credits = $user->purchasedCredits()->orderBy('created_at', 'asc')->get();

        foreach ($credits as $credit) {
            $creditsUsage['purchased'][] = [
               'id' => $credit->id,
               'credits' => $credit->credits,
               'price' => $credit->price,
               'added_on' => $credit->added_on->format('jS M Y'),
               'remarks' => $credit->remarks,
               'method' => $credit->method,
               'currency_type' => $credit->currency_type,
               'hub' => $credit->of->name,
            ];
        }

        $credits = $user->promotionalCredits()->orderBy('created_at', 'asc')->get();

        foreach ($credits as $credit) {
            $creditsUsage['promotional'][] = [
               'id' => $credit->id,
               'credits' => $credit->credits,
               'added_on' => $credit->added_on->format('jS M Y'),
               'remarks' => $credit->remarks,
               'coupon' => $credit->coupon,
               'hub' => $credit->of->name,
               'purchased_item' => $credit->purchasedItem,
               'currency_type' => $credit->currency_type,
            ];
        }

        $credits = $user->hubOnlyCredits()->orderBy('created_at', 'asc')->get();

        foreach ($credits as $credit) {
            $creditsUsage['hub_only'][] = [
               'id' => $credit->id,
               'credits' => $credit->credits,
               'hub' => $credit->of->name,
               'price' => $credit->price,
               'added_on' => $credit->added_on->format('jS M Y'),
               'remarks' => $credit->remarks,
               'method' => $credit->method,
               'currency_type' => $credit->currency_type,
            ];
        }

        $credits = $user->usedCredits()->orderBy('created_at', 'asc')->get();

        $used = 0;
        $refunded = 0;

        foreach ($credits as $credit) {

            $used += $credit->credits;
            $refunded += ($credit->credits - $credit->refund_remaining);

            $creditsUsage['used'][] = [
               'id' => $credit->id,
               'credits' => $credit->credits,
               'bucket_id' => $credit->bucket_id,
               'credits_type' => $credit->credits_type,
               'refunded' => $credit->credits - $credit->refund_remaining,
               'refund_remaining' => $credit->refund_remaining,
               'class' => $credit->of->code,
            ];

        }

        $creditsUsage['analytics']['used'] = $used;
        $creditsUsage['analytics']['refunded'] = $refunded;

        $credits = $user->creditBuckets()->orderBy('created_at', 'asc')->get();
        $total_remaining = 0;

        foreach ($credits as $credit) {

            $total_remaining += ($credit->purchased_remaining + $credit->hub_only_remaining + $credit->promotional_remaining);

            $creditsUsage['buckets'][] = [
               'id' => $credit->id,
               'purchased_total' => $credit->purchased_total,
               'promotional_total' => $credit->promotional_total,
               'hub_only_total' => $credit->hub_only_total,
               'total_credits' => $credit->total_credits,
               'purchased_remaining' => $credit->purchased_remaining,
               'hub_only_remaining' => $credit->hub_only_remaining,
               'promotional_remaining' => $credit->promotional_remaining,
               'total_remaining' => $credit->total_remaining,
               'hub_credits_type' => $credit->hub_credits_type,
               'hub_credits_type' => $credit->hub_credits_type,
               'currency_type' => $credit->currency_type,
               'hub' => $credit->hub->name,
            ];
        }

        $creditsUsage['analytics']['total_remaining'] = $total_remaining;

        $creditsUsage['learner'] = $learner;
        $creditsUsage['user'] = $user;
        $creditsUsage['locations'] = Location::get();
        $creditsUsage['lifetime'] = $learner->lifetimeOffer;

        return $creditsUsage;
    }

    public function getCredits($id)
    {
        $learner = $this->learner->find($id);

        $user = $learner->user;

        $credits = [];

        // credit : type, date, credits, method, remarks

        $statement = new Collection();

        $credits = $user->purchasedCredits()->orderBy('created_at', 'asc')->get();

        foreach($credits as $credit)
        {
            $objectC = new \StdClass;
            $objectC->type = 'credit';

            if(!is_null($credit->added_on)) {
                $objectC->date = $credit->added_on->format('d-M-Y');
            } else {
                $objectC->date = null;
            }

            $objectC->credits = $credit->credits;
            $objectC->method = $credit->method;
            $objectC->remarks = $credit->remarks;
            $objectC->invoice = "<a href='$credit->invoice_url' target='_blank'>".$credit->invoice_no;
            $statement->push($objectC);
        }

        $credits = $user->promotionalCredits()->orderBy('created_at', 'asc')->get();

        foreach($credits as $credit)
        {
            $objectC = new \StdClass;
            $objectC->type = 'credit';

            if(!is_null($credit->added_on)) {
                $objectC->date = $credit->added_on->format('d-M-Y');
            } else {
                $objectC->date = null;
            }

            $objectC->credits = $credit->credits;
            $objectC->method = $credit->method;
            $objectC->remarks = 'Credits via coupon: '.$credit->coupon;
            $objectC->invoice = '';
            $statement->push($objectC);
        }

        $credits = $user->hubOnlyCredits()->orderBy('created_at', 'asc')->get();

        foreach ($credits as $credit) {
            $objectC = new \StdClass;
            $objectC->type = 'credit';

            if(!is_null($credit->added_on)) {
                 $objectC->date = $credit->added_on->format('d-M-Y');
            } else {
                $objectC->date = null;
            }

            $objectC->credits = $credit->credits;
            $objectC->method = $credit->method;
            $objectC->remarks = $credit->remarks;
            $objectC->invoice = "<a href='$credit->invoice_url' target='_blank'>".$credit->invoice_no;
            $statement->push($objectC);
        }

        $credits = $user->usedCredits()->orderBy('created_at', 'asc')->get();
        foreach ($credits as $credit) {
            $objectC = new \StdClass;
            $objectC->type = 'debit';

            if(!is_null($credit->used_on)) {
                $objectC->date = $credit->used_on->format('d-M-Y');
            } else {
                $objectC->date = null;
            }

            $objectC->credits = $credit->credits;
            $objectC->method = 'enrolled';
            if($credit->of->location == NULL) {
                $objectC->remarks = 'Debit for class: '.$credit->of->name;                      
            } else {
                $objectC->remarks = 'Debit for class: '.
                '<a href="/hub-db/#/hub/'
                                .$credit->of.
                                '/view-class/'.
                                $credit->of->id.'">'.
                                $credit->of->code.
                '</a>';
            }
            $objectC->invoice = '';
            $statement->push($objectC);
        }

        $credits = $user->refundedCredits()->orderBy('created_at', 'asc')->get();

        foreach ($credits as $credit) {
            $objectC = new \StdClass;
            $objectC->type = 'credit';

            if(!is_null($credit->refunded_on)) {
                $objectC->date = $credit->refunded_on->format('d-M-Y');
            } else {
                $objectC->date = null;
            }

            $objectC->credits = $credit->credits;
            $objectC->method = 'Refund';
            $objectC->remarks = 'Refund for Class: '.
            '<a href="/hub-db/#/hub/'
                    .$credit->of->location->slug.
                    '/view-class/'.
                    $credit->of->id.'">'.
                    $credit->of->code.
            '</a>';
            $objectC->invoice = '';
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
                'invoice' => $statement->invoice,

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

    public function syncCredits($id)
    {
        $sync = new Sync();
        $sync->learner($id);

        return $id;
    }

    public function resetBuckets($id)
    {
        $sync = new Sync();
        $sync->resetBuckets($id);

        return $id;
    }

    public function addPromotionalCredits($id)
    {
        $rules = [
            'coupon' => 'nullable|exists:coupons,coupon',
            'credits' => 'nullable|numeric',
            'method' => 'required',
            'remarks' => 'required',
            'hub_id' => 'required'
        ];

        $v = Validator::make(request()->all(), $rules);

        if ($v->fails()) {
            return $this->response->withArray([
                    'result'=>'error',
                    'code' => 'tmerr002',
                    'messages' => $v->getMessageBag()
                ])->setStatusCode(422);
        }


        $learner = $this->learner->find($id);

        if (Input::has('coupon') && !is_null(Input::get('coupon')) && Input::get('coupon') != '') {
            $result = CouponManager::isValid(Input::get('coupon'), $learner->user, Input::get('credits'));

            if (!$result) {
                return $this->response->withArray([
                    'result'=>'error',
                    'code' => 'tmerr002',
                    'messages' => [['Invalid Coupon']]
                ])->setStatusCode(422);
            }
        }

        if (Input::get('method') == 'coupon' && Input::has('coupon') && !is_null(Input::get('coupon')) && Input::get('coupon') != '') {
            $result = CouponManager::isValid(Input::get('coupon'), $learner->user, 0);

            if (!$result) {
                return $this->response->withArray([
                    'result'=>'error',
                    'code' => 'tmerr002',
                    'messages' => [['Invalid Coupon']]
                ])->setStatusCode(422);
            }

            $creditsAddEngine = new Add(
                $learner->user_id,
                'INR',
                request()->get('hub_id')
            );

            return $creditsAddEngine->promotional(0, Input::get('remarks'), Input::get('coupon'), 0, 0);
        }


        if (Input::get('method') == 'credits' && Input::get('credits') > 0) {
            $creditsAddEngine = new Add(
                $learner->user_id,
                'INR',
                request()->get('hub_id')
            );

            return $creditsAddEngine->promotional(Input::get('credits'), Input::get('remarks'));
        }

        return $this->response->withArray([
            'result'=>'error',
            'code' => 'tmerr002',
            'messages' => [['Please fill all fields']]
        ])->setStatusCode(422);
    }

    public function addPurchasedCredits($id)
    {
        $rules = [
            'coupon' => 'nullable|exists:coupons,coupon',
            'credits' => 'required|numeric|min:1',
            'method' => 'required',
            'remarks' => 'required',
            'hub_id' => 'required',
            'amount' => 'required',
            'credits_type' => 'required',
        ];

        $v = Validator::make(request()->all(), $rules);

        if ($v->fails()) {
            return $this->response->withArray([
                    'result'=>'error',
                    'code' => 'tmerr002',
                    'messages' => $v->getMessageBag()
                ])->setStatusCode(422);
        }

        $learner = $this->learner->find($id);

        $creditsAddEngine = new Add(
            $learner->user_id,
            'INR',
            request()->get('hub_id')
        );

        if (request()->get('credits_type') == 'global') {
            $purchasedCredit = $creditsAddEngine->purchased(
                request()->get('credits'),
                request()->get('amount'),
                request()->get('remarks'),
                request()->get('method'),
                request()->get('coupon')
            );
        }


        if (request()->get('credits_type') == 'hub_only') {
            $purchasedCredit = $creditsAddEngine->hubOnly(
                request()->get('credits'),
                request()->get('amount'),
                request()->get('remarks'),
                request()->get('method')
            );
        }

        return $purchasedCredit;
    }
}
