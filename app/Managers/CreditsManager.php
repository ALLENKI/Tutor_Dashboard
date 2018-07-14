<?php namespace Aham\Managers;

use Input;
use File;

use Aham\Models\SQL\StudentCredits;
use Aham\Models\SQL\StudentOffer;
use Aham\CreditsEngine\Add;
use Razorpay\Api\Api;

class CreditsManager
{
    public static function addPromotionalCredits($user, $location, $coupon)
    {
        $creditsAddEngine = new Add($user->id, 'INR', $location->id);
        $creditsAddEngine->promotional(0, $coupon);
        return true;
    }

    public static function addCreditsByAdmin($student, $credits, $payment, $coupon = null)
    {
        \DB::beginTransaction();

        // Create a student credits record

        $creditModel = new StudentCredits(['student_id' => $student->id]);

        // $creditModel->payment = json_encode($payment->toArray());

        $creditModel->method = $payment['method'];
        $creditModel->mode = $payment['mode'];
        $creditModel->credits = $credits;
        $creditModel->amount_paid = $payment['amount'];
        $creditModel->remarks = $payment['remarks'];

        $creditModel->save();

        // Find how many credits student has, add these credits to his balance

        $student->credits = $student->credits + $creditModel->credits;
        $student->save();

        \DB::commit();

        if (!is_null($coupon) && $coupon != '') {
            // If there is a coupon, add the coupon

            static::addCouponCredits($coupon, $student, $credits, $creditModel);
        } else {
            // If he is subscribed to any offer, do that!

            
            
            if ($student->lifetimeOffer) {
                // If method refund or bonus, don't apply coupon
                
                if ($payment['method'] == 'refund' || $payment['method'] == 'manual_refund' || $payment['method'] == 'bonus') {
                } else {
                    // If there is no coupon and if there is a lifetime offer

                    static::addStudentCredits($student->lifetimeOffer->coupon, $student, $credits, $creditModel);
                }
            }
        }

        $creditModel->payment = '';

        $creditModel->save();

        // Send event here: Aham\Events\Student\AddedCredits
        event(new \Aham\Events\Student\AddedCredits($creditModel));


        return true;
    }

    public static function addCredits($student, $credits, $payment, $coupon = null)
    {
        \DB::beginTransaction();

        // Create a student credits record

        $creditModel = new StudentCredits(['student_id' => $student->id]);

        // $creditModel->payment = json_encode($payment->toArray());

        $creditModel->method = 'online_payment';
        $creditModel->credits = Input::get('credits');
        $creditModel->amount_paid = $payment->amount/100;

        $creditModel->save();

        // Find how many credits student has, add these credits to his balance

        $student->credits = $student->credits + $creditModel->credits;
        $student->save();

        \DB::commit();

        if (!is_null($coupon) && $coupon != '') {
            // If there is a coupon, add the coupon

            static::addCouponCredits($coupon, $student, Input::get('credits'), $creditModel);
        } else {
            // If he is subscribed to any offer, do that!

            if ($student->lifetimeOffer) {
                // If there is no coupon and if there is a lifetime offer

                static::addStudentCredits($student->lifetimeOffer->coupon, $student, $credits, $creditModel);
            }
        }

        $api = new Api(env('RAZOR_KEY'), env('RAZOR_SECRET'));

        $id = Input::get('razorpay_payment_id');

        $payment = $api->payment->fetch($id);

        $payment = $payment->capture(array('amount'=>$payment->amount));

        $creditModel->payment = json_encode($payment->toArray());

        $creditModel->save();

        // Send event here: Aham\Events\Student\AddedCredits

        event(new \Aham\Events\Student\AddedCredits($creditModel));

        return true;
    }

    public static function addStudentCredits($coupon, $student, $credits, $parent = null)
    {
        $creditModel = new StudentCredits(['student_id' => $student->id]);

        $creditModel->method = 'coupon';

        if ($coupon->additional_type == 'additional_units') {
            $totalCredits = $coupon->additional_value;
        }

        if ($coupon->additional_type == 'additional_percent') {
            $totalCredits = ($coupon->additional_value/100)*$credits;
        }

        if ($parent) {
            $creditModel->parent_id = $parent->id;
        }

        $creditModel->coupon_id = $coupon->id;

        $creditModel->credits = round($totalCredits);

        $creditModel->save();

        $student->credits = $student->credits + $creditModel->credits;
        $student->save();

        return true;
    }

    public static function compareCoupons($coupon1, $coupon2, $credits)
    {
        // Always pass coupon2 the student offer

        if ($coupon1->additional_type == 'additional_units') {
            $coupon1Credits = $coupon1->additional_value;
        }

        if ($coupon1->additional_type == 'additional_percent') {
            $coupon1Credits = ($coupon1->additional_value/100)*$credits;
        }

        if ($coupon2->additional_type == 'additional_units') {
            $coupon2Credits = $coupon2->additional_value;
        }

        if ($coupon2->additional_type == 'additional_percent') {
            $coupon2Credits = ($coupon2->additional_value/100)*$credits;
        }

        if ($coupon1Credits > $coupon2Credits) {
            return $coupon1;
        }

        if ($coupon2Credits > $coupon1Credits) {
            return $coupon2;
        }

        if ($coupon1Credits == $coupon2Credits) {
            return $coupon2;
        }
    }

    public static function addCouponCredits($coupon, $student, $credits, $parent = null)
    {
        // \DB::beginTransaction();

        if ($coupon = CouponManager::isValid($coupon, $student->user, $credits)) {
            // If it's a lifetime coupon, subscribe him to offer and proceed.

            if ($coupon->type == 'lifetime') {
                StudentOffer::create(['student_id' => $student->id, 'coupon_id' => $coupon->id]);
                static::addStudentCredits($coupon, $student, $credits, $parent);
                return true;
            }

            if ($student->lifetimeOffer) {
                $resultCoupon = static::compareCoupons($coupon, $student->lifetimeOffer->coupon, $credits);
                static::addStudentCredits($resultCoupon, $student, $credits, $parent);
            } else {
                static::addStudentCredits($coupon, $student, $credits, $parent);
            }
        } else {
            dd('Invalid Coupon');
        }

        // \DB::commit();

        return true;
    }

    public static function addEnrollmentCancelledCredits($enrollment)
    {
        $student = $enrollment->student;

        // Create a student credits record

        $creditModel = new StudentCredits(['student_id' => $student->id]);

        // $creditModel->payment = json_encode($payment->toArray());

        $creditModel->method = 'refund';
        $creditModel->credits = $enrollment->credits;
        $creditModel->amount_paid = 0;
        $creditModel->remarks = 'Cancellation of enrollment for class:'.$enrollment->ahamClass->code;

        $creditModel->save();

        // Find how many credits student has, add these credits to his balance

        $student->credits = $student->credits + $creditModel->credits;
        $student->save();

        \Log::info('addEnrollmentCancelledCredits:'.$enrollment->id);

        event(new \Aham\Events\Student\RefundedCredits($creditModel));


        return true;
    }

    public static function addClassCancelledCredits($enrollment)
    {
        $student = $enrollment->student;

        // Create a student credits record

        $creditModel = new StudentCredits(['student_id' => $student->id]);

        // $creditModel->payment = json_encode($payment->toArray());

        $creditModel->method = 'refund';
        $creditModel->credits = $enrollment->credits;
        $creditModel->amount_paid = 0;
        $creditModel->remarks = 'Cancellation of class by admin:'.$enrollment->ahamClass->code.' Reason:'.$enrollment->ahamClass->cancellation_reason;

        $creditModel->save();

        // Find how many credits student has, add these credits to his balance

        $student->credits = $student->credits + $creditModel->credits;
        $student->save();

        event(new \Aham\Events\Student\RefundedCredits($creditModel));

        return true;
    }
}
