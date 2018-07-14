<?php

namespace Aham\CreditsEngine;

use Aham\Models\SQL\CreditsPurchased;
use Aham\Models\SQL\CreditsBucket;
use Aham\Models\SQL\CreditsPromotional;
use Aham\Models\SQL\CreditsHubOnly;
use Aham\Models\SQL\CreditsUsed;
use Aham\Models\SQL\CreditsRefund;
use Aham\Models\SQL\Student;
use Aham\Models\SQL\User;
use Aham\Models\SQL\StudentCredits;
use Aham\Models\SQL\StudentEnrollment;
use Aham\Models\SQL\UserEnrollment;

class Sync
{

    public function fillZeroPurchaseIds()
    {
        $zeroPromotionals = CreditsPromotional::where('purchased_id',0)->get();

        foreach($zeroPromotionals as $zeroPromotional)
        {
            $bucket_id = $zeroPromotional->bucket_id;
            $findPurchased = CreditsPurchased::where('bucket_id',$bucket_id)->first();

            if(!is_null($findPurchased))
            {
                $zeroPromotional->purchased_id = $findPurchased->id;
                $zeroPromotional->save();
            }
            else
            {
                $zeroPromotional->purchased_id = null;
                $zeroPromotional->save();
            }
        }

    }

    public function findEffectiveBucketPrice()
    {
        // Find bucket
        // If there is purchased, find purchased price and calculate effective price
        // If there is only promotional, dig deep into promotional table, find purchased and calculate price
        // If there is hub_only, find purchased price and calculate price

        $buckets = CreditsBucket::get();

        foreach($buckets as $bucket)
        {
            if($bucket->purchased_total > 0)
            {

                $bucket->total_price = $bucket->purchased->price;
                $bucket->price_per_credit = $bucket->total_price/$bucket->total_credits;
                $bucket->save();

            } else if($bucket->hub_only_total > 0) {

                $bucket->total_price = $bucket->hubOnly->price;
                $bucket->price_per_credit = $bucket->total_price/$bucket->total_credits;
                $bucket->save();

            } else {

                $findPromotional = CreditsPromotional::where('bucket_id',$bucket->id)->first();

                if(!is_null($findPromotional->purchasedItem))
                {
                    var_dump("Here: ".$bucket->id);

                    $purchasedItem = $findPromotional->purchasedItem;
                    $purchasedItemBucket = $purchasedItem->bucket;

                    $bucket->total_price = $purchasedItem->price;
                    $bucket->price_per_credit = $bucket->total_price/($bucket->total_credits+$purchasedItemBucket->total_credits);
                    $bucket->save();

                    $purchasedItemBucket->price_per_credit = $bucket->total_price/($bucket->total_credits+$purchasedItemBucket->total_credits);
                    $purchasedItemBucket->save();

                }else {

                    $bucket->total_price = $bucket->total_credits*1100;
                    $bucket->price_per_credit = 1100;
                    $bucket->save();

                }


            }

        }

    }

    public function resetBuckets($id)
    {
        $learner = Student::find($id);
        $user = $learner->user;

        CreditsUsed::where('user_id', $user->id)->delete();
        CreditsRefund::where('user_id', $user->id)->delete();

        $this->resetBucketCounters($learner);
        // $this->useCredits($learner);
        $this->useAndRefundCredits($learner);
        // $this->refundCredits($learner);
    }

    public function resetBucketCounters($learner)
    {
        $user = $learner->user;

        $buckets = CreditsBucket::where('user_id', $user->id)->get();

        foreach($buckets as $bucket)
        {
            $bucket->hub_only_remaining = $bucket->hub_only_total;
            $bucket->total_remaining = $bucket->total_credits;
            $bucket->promotional_remaining = $bucket->promotional_total;
            $bucket->purchased_remaining = $bucket->purchased_total;
            $bucket->save();
        }
    }

    public function learner($id)
    {
        $learner = Student::find($id);
        $user = $learner->user;
        CreditsBucket::where('user_id', $user->id)->delete();
        CreditsPurchased::where('user_id', $user->id)->delete();
        CreditsPromotional::where('user_id', $user->id)->delete();
        CreditsHubOnly::where('user_id', $user->id)->delete();
        CreditsUsed::where('user_id', $user->id)->delete();
        CreditsRefund::where('user_id', $user->id)->delete();
        $this->addCredits($learner);
        $this->useCredits($learner);
        $this->refundCredits($learner);
    }

    public function addCredits($learner)
    {
        $studentCredits = StudentCredits::with('student.user', 'coupon')
                                        ->where('student_id', $learner->id)
                                        ->orderBy('created_at', 'asc')
                                        ->get();


        // var_dump($studentCredits); 

        foreach ($studentCredits as $studentCredit) {
            // First add all purchased credits
            if (in_array($studentCredit->method, ['cheque','Cheque','cash','online_payment','online_transfer'])) {
                // What if this purchased credit has an associated promotional credit?
                $creditsAddEngine = new Add($studentCredit->student->user_id, 'INR', 2, $studentCredit->created_at);
                
                $purchasedCredit = $creditsAddEngine->purchased($studentCredit->credits, $studentCredit->amount_paid, $studentCredit->remarks, $studentCredit->method, null, $studentCredit->invoice_no, $studentCredit->payment);

                $promotionalCredit = StudentCredits::where('parent_id', $studentCredit->id)->first();

                if (!is_null($promotionalCredit)) {
                    $creditsAddEngine->promotional($purchasedCredit->credits, $promotionalCredit->remarks, $promotionalCredit->coupon->coupon, 0, $purchasedCredit->id);
                }
            }

            if (in_array($studentCredit->method, ['coupon'])) {
                if (is_null($studentCredit->parent_id)) {
                    $creditsAddEngine = new Add($studentCredit->student->user_id, 'INR', 2, $studentCredit->created_at);
                    $creditsAddEngine->promotional(0, $studentCredit->remarks, $studentCredit->coupon->coupon, 0, 0);
                }
            }

            if (in_array($studentCredit->method, ['bonus'])) {
                $creditsAddEngine = new Add($studentCredit->student->user_id, 'INR', 2, $studentCredit->created_at);
                $creditsAddEngine->promotional($studentCredit->credits, 'Bonus credits');
            }
        }
    }

    public function useCredits($learner)
    {
        $user = $learner->user;

        $studentEnrollments = StudentEnrollment::orderBy('id', 'asc')
                                        ->where('student_id', $learner->id)
                                        ->get();

        foreach ($studentEnrollments as $studentEnrollment) {
            $creditsUsedEngine = new Used($studentEnrollment->created_at);
            $creditsUsedEngine->chargeUser($studentEnrollment->ahamClass, $studentEnrollment->student->user, $studentEnrollment->credits);
        }

        $userEnrollments = UserEnrollment::where('credits', '>', 0)
                                        ->where('user_id', $user->id)
                                        ->orderBy('id', 'asc')
                                        ->get();

        foreach ($userEnrollments as $userEnrollment) {
            $creditsUsedEngine = new Used($userEnrollment->created_at);
            $buckets = $creditsUsedEngine->findBuckets($userEnrollment->user, $userEnrollment->credits, 2);
            if (count($buckets)) {
                $creditsUsedEngine->useBuckets($buckets, $userEnrollment->user, 'INR', $userEnrollment->level);
            }
        }
    }


    public function refundCredits($learner)
    {
        $studentEnrollments = StudentEnrollment::where('cancelled', true)
                                        ->where('student_id', $learner->id)
                                        ->orderBy('id', 'asc')
                                        ->get();

        foreach ($studentEnrollments as $studentEnrollment) {
            $creditsRefundEngine = new Refund($studentEnrollment->updated_at);
            $creditsRefundEngine->refund($studentEnrollment, $studentEnrollment->student->user);
        }
    }

    public function useAndRefundCredits($learner)
    {
        $user = $learner->user;

        $studentEnrollments = StudentEnrollment::orderBy('id', 'asc')
                                        ->where('student_id', $learner->id)
                                        ->get();

        foreach ($studentEnrollments as $studentEnrollment) {
            $creditsUsedEngine = new Used($studentEnrollment->created_at);
            $creditsUsedEngine->chargeUser($studentEnrollment->ahamClass, $studentEnrollment->student->user, $studentEnrollment->credits);

            if($studentEnrollment->cancelled)
            {
                $creditsRefundEngine = new Refund($studentEnrollment->updated_at);
                $creditsRefundEngine->refund($studentEnrollment, $studentEnrollment->student->user);
            }
        }

        $userEnrollments = UserEnrollment::where('credits', '>', 0)
                                        ->where('user_id', $user->id)
                                        ->orderBy('id', 'asc')
                                        ->get();

        foreach ($userEnrollments as $userEnrollment) {
            $creditsUsedEngine = new Used($userEnrollment->created_at);
            $buckets = $creditsUsedEngine->findBuckets($userEnrollment->user, $userEnrollment->credits, 2);
            if (count($buckets)) {
                $creditsUsedEngine->useBuckets($buckets, $userEnrollment->user, 'INR', $userEnrollment->level);
            }
        }
    }
}
