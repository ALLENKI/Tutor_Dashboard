<?php

namespace Aham\Http\Controllers\Frontend\GuestSeries;

use View;
use Sentinel;
use Activation;
use Reminder;
use Validator;
use Input;
use Mail;
use Carbon;
use DB;

use Aham\Models\SQL\AhamClass;
use Aham\Models\SQL\GuestSeries;
use Aham\Models\SQL\StudentEnrollment;
use Aham\Models\SQL\GuestSeriesEpisode;
use Aham\Models\SQL\GuestSeriesLevel;
use Aham\Models\SQL\UserEnrollment;

use Aham\Http\Controllers\Frontend\BaseController;

use Razorpay\Api\Api;


class EnrollToLevelController extends BaseController
{
    public function __construct()
    {
        parent::__construct();
    }

    public function showEnrollPage($slug, $level)
    {

        $guestSeries = GuestSeries::where('slug',$slug)->first(); 
        $guestSeriesLevel = GuestSeriesLevel::where('slug',$level)->first();

        $isStudent = false;

        $user = Sentinel::getUser();

        if($user->student)
        {
            if($user->student->active)
            {
                $isStudent = true;
            } 
        }

        $enrolled = false;

        $foundEnrolled = UserEnrollment::where([
                                'user_id' => $user->id,
                                'episode_id' => $guestSeriesLevel->id,
                                'type' => 'level'
                            ])->first();

        if($foundEnrolled)
        {
            $enrolled = true;
        }

        return view('frontend.guest_series.enroll',compact('guestSeries','guestSeriesLevel','isStudent','enrolled'));
    }


    public function freeEnroll($slug, $level)
    {
        $guestSeries = GuestSeries::where('slug',$slug)->first(); 
        $guestSeriesLevel = GuestSeriesLevel::where('slug',$level)->first();

        $user = Sentinel::getUser();
        
        $data = [
            'user_id' => $user->id,
            'episode_id' => $guestSeriesLevel->id, // Means level though named episode,
            'type' => 'level',
            'method' => 'payment',
            'amount_paid' => 0
        ];

        $enrollment = UserEnrollment::firstOrCreate($data);
 
        $this->dispatch(new \Aham\Jobs\SendLevelWorkshopConfirmMail($enrollment));

        flash()->success('You have successfully enrolled to this Batch!');

        return redirect()->back();
    }

    public function studentEnroll($slug, $level)
    {
        $guestSeries = GuestSeries::where('slug',$slug)->first(); 
        $guestSeriesLevel = GuestSeriesLevel::where('slug',$level)->first();

        $user = Sentinel::getUser();

        $number_of_credits = 0;

        $number_of_credits = ($guestSeries->cost_per_episode/1100)*$guestSeriesLevel->episodes->count();

        $data = [
            'user_id' => $user->id,
            'episode_id' => $guestSeriesLevel->id, // Means level though named episode,
            'type' => 'level',
            'method' => 'credits',
            'credits' => $number_of_credits
        ];

        $enrollment = UserEnrollment::firstOrCreate($data);

        $student = $user->student;

        $student->credits -= $number_of_credits;

        $student->save();

        $this->dispatch(new \Aham\Jobs\SendLevelWorkshopConfirmMail($enrollment));

        flash()->success('You have successfully enrolled to this Batch!');

        return redirect()->back();
    }

    public function userEnroll($slug, $level)
    {
        // dd(Input::all());

        flash()->success('You have successfully enrolled to this Batch!');

        $guestSeries = GuestSeries::where('slug',$slug)->first(); 
        $guestSeriesLevel = GuestSeriesLevel::where('slug',$level)->first();

        $user = Sentinel::getUser();

        $api = new Api(env('RAZOR_KEY'), env('RAZOR_SECRET'));

        $id = Input::get('razorpay_payment_id');

        $payment = $api->payment->fetch($id);

        $payment = $payment->capture(array('amount'=>$payment->amount));

        $data = [
            'user_id' => $user->id,
            'episode_id' => $guestSeriesLevel->id, // Means level though named episode,
            'type' => 'level',
            'method' => 'payment',
            'amount_paid' => $payment->amount/100
        ];

        $enrollment = UserEnrollment::firstOrCreate($data);

        $this->dispatch(new \Aham\Jobs\SendLevelWorkshopConfirmMail($enrollment));

        flash()->success('You have successfully enrolled to this Batch!');

        return redirect()->route('series::enroll-to-level',[$guestSeries->slug, $guestSeriesLevel->slug]);
    }

}
