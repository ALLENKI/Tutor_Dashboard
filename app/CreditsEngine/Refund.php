<?php

namespace Aham\CreditsEngine;

use Aham\Http\Controllers\Controller;
use Aham\Models\SQL\CreditsUsed;
use Aham\Models\SQL\CreditsBucket;
use Aham\Models\SQL\CreditsRefund;
use Carbon;

class Refund extends Controller
{
    public $refundedOn = null;

    public function __construct($refundedOn = null)
    {
        if (is_null($this->refundedOn)) {
            $this->refundedOn = Carbon::now();
        }
    }

    public function addCreditsBack($class, $creditsToRefund, $user)
    {

        $usedBuckets = CreditsUsed::where('of_id', $class->id)
                            ->where('user_id', $user->id)
                            ->where('of_type', get_class($class))
                            ->where('refund_remaining', '>', 0)
                            ->orderBy('id', 'desc')
                            ->get();

        foreach ($usedBuckets as $usedBucket) {
            if ($usedBucket->refund_remaining >= $creditsToRefund) {
                $bucket = CreditsBucket::find($usedBucket->bucket_id);
                $credits_type = $usedBucket->credits_type . '_remaining';
                $bucket->$credits_type = $bucket->$credits_type + $creditsToRefund;
                $bucket->total_remaining = $bucket->total_remaining + $creditsToRefund;
                $bucket->save();

                CreditsRefund::create([
                    'user_id' => $user->id,
                    'bucket_id' => $bucket->id,
                    'credits_used_id' => $usedBucket->id,
                    'credits' => $creditsToRefund,
                    'of_id' => $class->id,
                    'of_type' => get_class($class),
                    'refunded_on' => $this->refundedOn
                ]);

                $usedBucket->refund_remaining = $usedBucket->refund_remaining - $creditsToRefund;
                $usedBucket->save();

                break;
            } else {
                $bucket = CreditsBucket::find($usedBucket->bucket_id);
                $credits_type = $usedBucket->credits_type . '_remaining';
                $bucket->$credits_type = $bucket->$credits_type + $usedBucket->refund_remaining;
                $bucket->total_remaining = $bucket->total_remaining + $usedBucket->refund_remaining;
                $bucket->save();

                CreditsRefund::create([
                    'user_id' => $user->id,
                    'bucket_id' => $bucket->id,
                    'credits_used_id' => $usedBucket->id,
                    'credits' => $usedBucket->refund_remaining,
                    'of_id' => $class->id,
                    'of_type' => get_class($class),
                    'refunded_on' => $this->refundedOn
                ]);

                $usedBucket->refund_remaining = 0;
                $usedBucket->save();

                $creditsToRefund = $creditsToRefund - $usedBucket->refund_remaining;
            }
        }

        return true;
    }

    public function refund($studentEnrollment, $user)
    {
        $class = $studentEnrollment->ahamClass;
        $creditsToRefund = $studentEnrollment->credits;

        $this->addCreditsBack($class, $creditsToRefund, $user);
    }
}
