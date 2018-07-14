<?php

namespace Aham\Http\Controllers\V2\AdminDB;

use Aham\Http\Controllers\Backend\BaseController;
use Aham\Models\SQL\TutorPayments;
use Illuminate\Support\Carbon;
use Input;
use Hamcrest\NullDescription;

class TutorPaymentsController extends BaseController
{
    public function index($tutorId,$hubId)
    {
        $tutorPayment = TutorPayments::where('tutor_id',$tutorId)
                                        ->where('hub_id',$hubId)
                                        ->get()
                                        ->first();

        if ($tutorPayment !== NULL) {
            // return $tutorPayment->timings;
            $tutorPayment->timings = json_decode($tutorPayment->timings,true);

            return $tutorPayment;
        }

        return [];
    }

    public function createUpdate()
    {
        $timings = json_encode(Input::get('timings'));

        $payments['tutor_id'] = Input::get('tutorId');
        $payments['hub_id'] = Input::get('hubId');
        $payments['timings'] = $timings;

        $tutorPayments =  TutorPayments::firstOrCreate(array_only($payments,['tutor_id','hub_id']));
        $tutorPayments->timings = $payments['timings'];
        $tutorPayments->save();

        return $tutorPayments;
    }

    public function destroy($tutorId,$hubId,$day,$timingsId)
    {

        $tutorPaymentSet = TutorPayments::where('tutor_id',$tutorId)
                                            ->where('hub_id',$hubId)
                                            ->first();

        // return $tutorPaymentSet;

        if ($tutorPaymentSet !== NULL) {
            // return $tutorPaymentSet->timings;
            $timings = json_decode($tutorPaymentSet->timings,true);

            foreach ($timings as $index => $value) {

                if ($value['day'] ==  $day) {

                    foreach ($value['timings'] as $key => $timing) {
                         if ($timing['id'] == $timingsId) {
                               unset($value['timings'][$key]);
                            //    return $a;
                         }
                    }

                    $timings[$index]['timings'] =  $value['timings'];
                    // $timing['timings'] =  ;
                }

            }

        }

        $tutorPaymentSet->timings = json_encode($timings);
        $tutorPaymentSet->save();
    
        return 'successfull';
    }

}