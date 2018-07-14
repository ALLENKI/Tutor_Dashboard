<?php

namespace Aham\CreditsEngine;

use Aham\Models\SQL\CreditsPurchased;
use Aham\Models\SQL\CreditsBucket;
use Aham\Models\SQL\CreditsPromotional;
use Aham\Models\SQL\CreditsHubOnly;
use Aham\Models\SQL\Location;
use Aham\Models\SQL\Coupon;
use Carbon;

class Add
{
    public $bucket;
    public $location;
    public $addedOn = null;

    public function __construct($userId, $currencyType, $locationId, $addedOn = null)
    {
        $this->location = Location::find($locationId);

        $this->bucket = CreditsBucket::create([
                            'user_id' => $userId,
                            'currency_type' => $currencyType,
                            'hub_id' => $this->location->id,
                            'hub_credits_type' => 'global',//$this->location->credits_type
                        ]);

        $this->addedOn = $addedOn;
    }

    public function purchased($credits, $price, $remarks, $method, $coupon = null, $invoiceNo = null, $paymentDetails = null)
    {

        $credits = trim($credits);

        $purchased = CreditsPurchased::create([
                        'user_id' => $this->bucket->user_id,
                        'credits' => $credits,
                        'price' => $price,
                        'currency_type' => $this->bucket->currency_type,
                        'of_id' => $this->location->id,
                        'of_type' => 'Aham\Models\SQL\Location',
                        'bucket_id' => $this->bucket->id,
                        'remarks' => $remarks,
                        'method' => $method,
                        'payment_details' => $paymentDetails,
                    ]);

        if (!is_null($invoiceNo)) {
            $purchased->invoice_no = $invoiceNo;
            $purchased->save();
        }

        if (!is_null($this->addedOn)) {
            $purchased->added_on = $this->addedOn;
            $purchased->save();
        } else {
            $purchased->added_on = Carbon::now();
            $purchased->save();
        }

        $this->updateBucket($credits, 'purchased');

        if (!is_null($coupon) && $coupon != '') {
            $this->promotional($credits, $remarks, $coupon, 0, 0, $purchased->id);
        }

        return $purchased;
    }

    public function promotional($credits, $remarks, $coupon = null, $priority = 0, $purchasedId = 0)
    {
        if (is_null($coupon)) {
            $promotional = CreditsPromotional::create([
                'user_id' => $this->bucket->user_id,
                'currency_type' => $this->bucket->currency_type,
                'credits' => $credits,
                'of_id' => $this->location->id,
                'of_type' => 'Aham\Models\SQL\Location',
                'bucket_id' => $this->bucket->id,
                'remarks' => $remarks
            ]);

            if (!is_null($this->addedOn)) {
                $promotional->added_on = $this->addedOn;
                $promotional->save();
            } else {
                $promotional->added_on = Carbon::now();
                $promotional->save();
            }

            $this->updateBucket($credits, 'promotional', $priority);

            return $promotional;
        }

        $coupon = Coupon::where('coupon', $coupon)->first();

        // Get will give a collection

        if (is_null($coupon)) {
            return 'Coupon Not Found';
        }

        if ($coupon->additional_type == 'additional_percent') {
            $credits = ($coupon->additional_value / 100) * $credits;
        } elseif ($coupon->additional_type == 'additional_units') {
            $credits = $coupon->additional_value;
        }

        if($credits > 0)
        {

            $promotional = CreditsPromotional::create([
                            'user_id' => $this->bucket->user_id,
                            'currency_type' => $this->bucket->currency_type,
                            'credits' => $credits,
                            'coupon' => $coupon->coupon,
                            'of_id' => $this->location->id,
                            'of_type' => 'Aham\Models\SQL\Location',
                            'bucket_id' => $this->bucket->id,
                            'purchased_id' => $purchasedId,
                            'remarks' => $remarks
                        ]);

            if (!is_null($this->addedOn)) {
                $promotional->added_on = $this->addedOn;
                $promotional->save();
            } else {
                $promotional->added_on = Carbon::now();
                $promotional->save();
            }

            $this->updateBucket($credits, 'promotional', $priority);

            return $promotional;

        }

        return true;
        
    }

    public function hubOnly($credits, $price, $remarks, $method)
    {
        $hubOnlyCredit = CreditsHubOnly::create([
                    'user_id' => $this->bucket->user_id,
                    'currency_type' => $this->bucket->currency_type,
                    'credits' => $credits,
                    'price' => $price,
                    'of_id' => $this->location->id,
                    'of_type' => 'Aham\Models\SQL\Location',
                    'bucket_id' => $this->bucket->id,
                    'remarks' => $remarks,
                    'method' => $method,
                ]);

        if (!is_null($this->addedOn)) {
            $hubOnlyCredit->added_on = $this->addedOn;
            $hubOnlyCredit->save();
        } else {
            $hubOnlyCredit->added_on = Carbon::now();
            $hubOnlyCredit->save();
        }

        $this->updateBucket($credits, 'hub_only');

        return $hubOnlyCredit;
    }

    public function updateBucket($credits, $type, $priority = NULL)
    {
        $this->bucket->priority = $priority;

        switch ($type) {
            case 'purchased':

                    $this->bucket->purchased_total = $credits;
                    $this->bucket->total_credits += $credits;
                    $this->bucket->purchased_remaining = $credits;
                    $this->bucket->total_remaining += $credits;
                    $this->bucket->save();

                break;

            case 'promotional':

                    $this->bucket->promotional_total = $credits;
                    $this->bucket->total_credits += $credits;
                    $this->bucket->promotional_remaining = $credits;
                    $this->bucket->total_remaining += $credits;
                    $this->bucket->save();

                break;

            case 'hub_only':

                    $this->bucket->hub_only_total = $credits;
                    $this->bucket->total_credits += $credits;
                    $this->bucket->hub_only_remaining = $credits;
                    $this->bucket->total_remaining += $credits;
                    $this->bucket->hub_credits_type = 'hub_only';
                    $this->bucket->save();

                break;

            default:

                break;
        }
    }
}
