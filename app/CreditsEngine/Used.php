<?php

namespace Aham\CreditsEngine;

use Aham\Http\Controllers\Controller;
use Aham\Models\SQL\CreditsUsed;
use Aham\Models\SQL\CreditsBucket;
use Aham\Models\SQL\Location;

use Carbon;

class Used extends Controller
{
    public $usedOn = null;

    public function __construct($usedOn = null)
    {

        if (is_null($this->usedOn)) {
            $this->usedOn = Carbon::now();
        } 

    }

    public function chargeUser($class, $user, $overrideCredits = null)
    {
        $credits = $class->classUnits->count()*$class->charge_multiply;

        if(!is_null($overrideCredits))
        {
            $credits = $overrideCredits;
        }

        $hub = $class->location;
        $currencyType = $hub->currency_type;

        $buckets = $this->findBuckets($user, $credits, $class->location_id);

        if (count($buckets)) {
            $this->useBuckets($buckets, $user, $currencyType, $class);
        } else {
            return 'No enough credits';
        }
    }

    public function useBuckets($buckets, $user, $currencyType = 'INR', $class)
    {
        foreach ($buckets as $bucket) {
            CreditsUsed::create([
                'bucket_id' => $bucket['bucket_id'],
                'user_id' => $user->id,
                'credits' => $bucket['credits'],
                'refund_remaining' => $bucket['credits'],
                'credits_type' => $bucket['credits_type'],
                'of_id' => $class->id,
                'of_type' => get_class($class),
                'currency_type' => $currencyType,
                'used_on' => $this->usedOn,
            ]);

            $credit_type_of_bucket = $bucket['credits_type'] . '_remaining';
            $dbBucket = CreditsBucket::find($bucket['bucket_id']);
            $dbBucket->$credit_type_of_bucket = $dbBucket->$credit_type_of_bucket - $bucket['credits'];
            $dbBucket->total_remaining = $dbBucket->total_remaining - $bucket['credits'];
            $dbBucket->save();
        }

        return true;
    }

    public function findBuckets($user, $credits, $hubId)
    {
        /*
        In locations table we have:

            1. currency_type
            2. credits_type
        */

        $hub = Location::find($hubId);

        $currencyType = $hub->currency_type;
        $creditsType = $hub->credits_type; // hub_only, global (purchased and promotional)
        $creditsNeeded = $credits; // 4.75
        $creditsGot = 0;
        $finalizedBuckets = [];
        
        if ($creditsType == 'hub_only') {
            $buckets = CreditsBucket::where('total_remaining', '>', 0)
            ->where('hub_id', $hubId)
            ->where('hub_credits_type', 'hub_only')
            ->where('user_id', $user->id)
            ->where('currency_type', $currencyType)
            ->orderBy('priority', 'desc')
            ->orderBy('id', 'asc')
            ->get();
        } else {
            $buckets = CreditsBucket::where('total_remaining', '>', 0)
            ->where('hub_credits_type', 'global')
            ->where('user_id', $user->id)
            ->where('currency_type', $currencyType)
            ->orderBy('priority', 'desc')
            ->orderBy('id', 'asc')
            ->get();
        }

        foreach ($buckets as $bucket) {
            if ($bucket->purchased_remaining > 0) {
                if ($bucket->purchased_remaining < $creditsNeeded) {
                    $finalizedBuckets[] = [
                        'bucket_id' => $bucket->id,
                        'credits' => $bucket->purchased_remaining,
                        'credits_type' => 'purchased'
                    ];
                    $creditsNeeded = $creditsNeeded - $bucket->purchased_remaining;
                } else {
                    $finalizedBuckets[] = [
                        'bucket_id' => $bucket->id,
                        'credits' => $creditsNeeded,
                        'credits_type' => 'purchased'
                    ];
                    $creditsNeeded = 0;
                    break;
                }
            }


            if ($bucket->hub_only_remaining > 0) {
                if ($bucket->hub_only_remaining < $creditsNeeded) {
                    $finalizedBuckets[] = [
                        'bucket_id' => $bucket->id,
                        'credits' => $bucket->hub_only_remaining,
                        'credits_type' => 'hub_only'
                    ];
                    $creditsNeeded = $creditsNeeded - $bucket->hub_only_remaining;
                } else {
                    $finalizedBuckets[] = [
                        'bucket_id' => $bucket->id,
                        'credits' => $creditsNeeded,
                        'credits_type' => 'hub_only'
                    ];
                    $creditsNeeded = 0;
                    break;
                }
            }


            if ($bucket->promotional_remaining > 0) {
                if ($bucket->promotional_remaining < $creditsNeeded) {
                    if ($bucket->global || $bucket->hub_id == $hubId) {
                        $finalizedBuckets[] = [
                            'bucket_id' => $bucket->id,
                            'credits' => $bucket->promotional_remaining,
                            'credits_type' => 'promotional'
                        ];
                        $creditsNeeded = $creditsNeeded - $bucket->promotional_remaining;
                    }
                } else {
                    if ($bucket->global || $bucket->hub_id == $hubId) {
                        $finalizedBuckets[] = [
                            'bucket_id' => $bucket->id,
                            'credits' => $creditsNeeded,
                            'credits_type' => 'promotional'
                        ];
                        $creditsNeeded = 0;
                        break;
                    }
                }
            }
        }

        if ($creditsNeeded > 0) {
            return [];
        }

        return $finalizedBuckets;
    }
}
