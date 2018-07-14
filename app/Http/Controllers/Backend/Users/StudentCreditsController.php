<?php

namespace Aham\Http\Controllers\Backend\Users;

use Aham\Http\Controllers\Backend\BaseController;
use Aham\Models\SQL\User;
use Aham\Models\SQL\Student;
use Aham\Models\SQL\Topic;
use Aham\Models\SQL\Coupon;
use Aham\Models\SQL\StudentEnrollment;
use Input;
use Validator;
use Aham\Managers\CreditsManager;
use Aham\Managers\CouponManager;
use Illuminate\Support\Collection;

class StudentCreditsController extends BaseController
{
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function getCredits($id)
    {
        $student = Student::with('assessments.topic')->find($id);

        $user = $student->user;

        $couponsModel = Coupon::active()->valid()
                        ->applicable($user);

        if ($student->lifetimeOffer) {
            $couponsModel = $couponsModel->where('coupon', '<>', $student->lifetimeOffer->coupon->coupon);
        }

        $coupons = $couponsModel->pluck('coupon', 'coupon')->toArray();

        $ahamCredits = $student->ahamCredits;

        $enrollments = $student->enrollments()->get();

        $enrollments = StudentEnrollment::where('student_id', $student->id)->get();

        // dd($enrollments);

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

        return view('backend.users.students.credits', compact('student', 'user', 'ahamCredits', 'coupons', 'statement'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function postCredits($id)
    {
        $rules = [
            'coupon' => 'nullable|exists:coupons,coupon',
            'credits' => 'required',
            'method' => 'required',
            'remarks' => 'required',
        ];

        if (!Input::has('coupon')) {
            $rules['credits'] = 'required|numeric|min:1';
        }

        $messages = [
            'valid_till.required_unless' => 'This field is required'
        ];

        $v = Validator::make(Input::all(), $rules, $messages);

        if ($v->fails()) {
            flash()->error('There were errors, Please check.');
            return redirect()->back()->withErrors($v->errors())->withInput();
        }

        $student = Student::find($id);

        if (Input::has('coupon') && !is_null(Input::get('coupon'))) {
            $result = CouponManager::isValid(Input::get('coupon'), $student->user, Input::get('credits'));

            if (!$result) {
                // flash()->error('Invalid Coupon');
                return redirect()->back();
            }
        }

        // dd(Input::all());

        if (Input::get('credits') == 0) {
            CreditsManager::addCouponCredits(Input::get('coupon'), $student, Input::get('credits'));

            flash()->success('Successfully added credit for 0 rupees');
            return redirect()->back();
        } else {
            $remarks = '';

            if (Input::has('promotional')) {
                $amount = 0;
                $remarks = 'Promotional:';
            } elseif (Input::get('method') == 'bonus' || Input::get('method') == 'refund' || Input::get('method') == 'manual_refund') {
                $amount = 0;
            } else {
                $amount = Input::get('credits') * 1100;
            }

            $coupon = Input::get('coupon', null);
            $remarks = $remarks . Input::get('remarks', '');

            $payment = [
                'amount' => $amount,
                'remarks' => $remarks,
                'mode' => 'admin',
                'method' => Input::get('method')
            ];

            CreditsManager::addCreditsByAdmin($student, Input::get('credits'), $payment, $coupon);
        }

        flash()->success('Successfully added credits');
        return redirect()->back();
    }
}
