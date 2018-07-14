<?php

namespace Aham\Http\Controllers\Dashboard\Student;

use Aham\Models\SQL\Coupon;
use Aham\Models\SQL\StudentEnrollment;
use Validator;
use Input;
use Razorpay\Api\Api;
use Aham\Managers\CreditsManager;
use Aham\Managers\CouponManager;
use Illuminate\Support\Collection;
use Aham\CreditsEngine\Add;

class CreditsController extends StudentDashboardBaseController
{
    public function __construct()
    {
        parent::__construct();
    }

    public function index()
    {
        $ahamCredits = $this->student->ahamCredits;

        $student = $this->student;

        $ahamCredits = $student->ahamCredits;

        $enrollments = $student->enrollments;

        $enrollments = StudentEnrollment::where('student_id', $student->id)->get();

        $seriesEnrollments = $student->user->seriesEnrollments;

        $statement = new Collection();

        foreach ($ahamCredits as $ahamCredit) {
            $remarks = $ahamCredit->remarks;

            if ($ahamCredit->method == 'coupon') {
                $remarks = 'Credits from coupon: ' . $ahamCredit->coupon->coupon . ' (' . $ahamCredit->coupon->additional_type . ' - ' . ' ' . $ahamCredit->coupon->additional_value . ')';
            }

            $statement->push([
                'type' => 'credit',
                'date' => $ahamCredit->created_at,
                'credits' => $ahamCredit->credits,
                'method' => $ahamCredit->method,
                'remarks' => $remarks
            ]);
        }

        foreach ($enrollments as $enrollment) {
            $statement->push([
                'type' => 'debit',
                'date' => $enrollment->created_at,
                'credits' => $enrollment->credits,
                'method' => 'enrollment',
                'remarks' => 'Enrollment for class: ' . $enrollment->ahamClass->code . ', Topic:' . $enrollment->ahamClass->topic->name
            ]);
        }

        foreach ($seriesEnrollments as $enrollment) {
            $statement->push([
                'type' => 'debit',
                'date' => $enrollment->created_at,
                'credits' => $enrollment->credits,
                'method' => 'enrollment',
                'remarks' => 'Enrollment for workshop: ' . $enrollment->level->name
            ]);
        }

        // dd($enrollments);

        $statement = $statement->sortBy('date');

        return view('dashboard.student.credits.index', compact('ahamCredits', 'statement'));
    }

    public function add()
    {
        $couponsModel = Coupon::active()->valid()
                        ->applicable($this->student->user);

        if ($this->student->lifetimeOffer) {
            $couponsModel = $couponsModel->where('coupon', '<>', $this->student->lifetimeOffer->coupon->coupon);
        }

        $coupons = $couponsModel->pluck('coupon', 'coupon')->toArray();

        return view('dashboard.student.credits.add', compact('coupons'));
    }

    public function pay()
    {
        $rules = [
            'coupon' => 'nullable|exists:coupons,coupon',
            'credits' => 'required',
        ];

        if (!Input::has('coupon')) {
            $rules['credits'] = 'required|numeric|min:10';
        }

        $messages = [
            'valid_till.required_unless' => 'This field is required'
        ];

        $v = Validator::make(Input::all(), $rules, $messages);

        if ($v->fails()) {
            flash()->error('There were errors, Please check.');
            return redirect()->back()->withErrors($v->errors())->withInput();
        }

        if (Input::has('coupon') && !is_null(Input::get('coupon'))) {
            $result = CouponManager::isValid(Input::get('coupon'), $this->student->user, Input::get('credits'));

            if (!$result) {
                flash()->error('Invalid Coupon');
                return redirect()->back();
            }
        }

        if (Input::get('credits') == 0) {

            // CreditsManager::addCouponCredits(Input::get('coupon'), $this->student, Input::get('credits'));

            flash()->success('You need to buy more than 0 credits');
            return redirect()->route('student::credits.index');

        } else {
            $credits = Input::get('credits');
            $coupon = Input::get('coupon', null);

            $appliedCoupon = null;

            if (!is_null($coupon)) {
                $appliedCoupon = Coupon::where('coupon', $coupon)->first();
            }

            $amount = $credits * 1100;
            $user = $this->student->user;

            return view('dashboard.student.credits.pay', compact('credits', 'amount', 'user', 'coupon', 'appliedCoupon'));
        }
    }

    public function paymentSuccess()
    {
        $student = $this->student;

        $hub_id = $student->hubs->first()->id;

        $learner = $student;

        $coupon = Input::get('coupon', null);

        if (Input::has('coupon') && !is_null($coupon) && $coupon != '') {
            $result = CouponManager::isValid(Input::get('coupon'), $this->student->user, Input::get('credits'));

            if (!$result) {
                flash()->error('Payment cancelled as the applied coupon is no more valid! If amount is deducted it will be refunded back!');
                return redirect()->route('student::credits.index');
            }
        }

        if (!is_null($coupon) && $coupon != '') {

        }else {
            if ($student->lifetimeOffer) {
                $coupon = $student->lifetimeOffer->coupon->coupon;
            }
        }

        // dd($coupon);

        $api = new Api(env('RAZOR_KEY'), env('RAZOR_SECRET'));

        $id = Input::get('razorpay_payment_id');

        $payment = $api->payment->fetch($id);

        $creditsAddEngine = new Add(
            $learner->user_id,
            'INR',
            $hub_id
        );

        $purchasedCredit = $creditsAddEngine->purchased(
            Input::get('credits'),
            $payment->amount/100,
            'online_payment',
            'online_payment',
            $coupon
        );

        $payment = $payment->capture(array('amount'=>$payment->amount));

        $purchasedCredit->payment_details = json_encode($payment->toArray());
        $purchasedCredit->save();

        // $payment = $payment->capture(array('amount'=>$payment->amount));

        // CreditsManager::addCredits($student, Input::get('credits'), $payment, $coupon);

        return redirect()->route('student::credits.index');
    }

    public function statement()
    {
        $student = $this->student;

        $user = $student->user;

        $ahamCredits = $student->ahamCredits;

        $enrollments = $student->enrollments;

        $seriesEnrollments = $student->user->seriesEnrollments;

        $statement = new Collection();

        foreach ($ahamCredits as $ahamCredit) {
            $remarks = $ahamCredit->remarks;

            if ($ahamCredit->method == 'coupon') {
                $remarks = 'Credits from coupon: ' . $ahamCredit->coupon->coupon . ' (' . $ahamCredit->coupon->additional_type . ' - ' . ' ' . $ahamCredit->coupon->additional_value . ')';
            }

            $statement->push([
                'type' => 'credit',
                'date' => $ahamCredit->created_at,
                'credits' => $ahamCredit->credits,
                'method' => $ahamCredit->method,
                'remarks' => $remarks
            ]);
        }

        foreach ($enrollments as $enrollment) {
            $statement->push([
                'type' => 'debit',
                'date' => $enrollment->created_at,
                'credits' => $enrollment->credits,
                'method' => 'enrollment',
                'remarks' => 'Enrollment for class: ' . $enrollment->ahamClass->code . ', Topic:' . $enrollment->ahamClass->topic->name
            ]);
        }

        foreach ($seriesEnrollments as $enrollment) {
            $statement->push([
                'type' => 'debit',
                'date' => $enrollment->created_at,
                'credits' => $enrollment->credits,
                'method' => 'enrollment',
                'remarks' => 'Enrollment for workshop: ' . $enrollment->level->name
            ]);
        }

        // dd($enrollments);

        $statement = $statement->sortBy('date');

        return view('dashboard.student.credits.statement', compact('coupons'));
    }
}
