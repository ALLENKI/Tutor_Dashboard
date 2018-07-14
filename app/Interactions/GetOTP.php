<?php namespace Aham\Interactions;

use Aham\Models\SQL\MobileOtp;

use Aham\Helpers\SMSHelper;

use Carbon;

class GetOTP {

    public static function make($mobile)
    {
        //Fix this - If already an otp exists - Don't send

        $exists = MobileOtp::where(['mobile' => $mobile])->first();

        if($exists)
        {
            return true;
        }

        $mobile_otp = MobileOtp::firstOrCreate(['mobile' => $mobile]);

        $mobile_otp->otp = rand(100000, 999999);

        $mobile_otp->expires_on = Carbon::now()->addMinutes(11)->toDateTimeString();

        $mobile_otp->save();

        $smsHelper = new SMSHelper();

        $smsHelper->sendMessage($mobile,$mobile_otp->otp.' is your Aham OTP for verification.');

        return true;
    }

}