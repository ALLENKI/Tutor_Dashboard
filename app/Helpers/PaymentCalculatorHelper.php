<?php namespace Aham\Helpers;

use Aham\Models\SQL\TutorPayments;
use Carbon;

class PaymentCalculatorHelper {

	public static function calculateForClass($ahamClass)
	{
		foreach($ahamClass->timings as $timing)
		{
			static::calculateForTiming($timing);
		}

	}

	public static function calculateForTiming($classTiming)
	{
		$totalAmount = 0;

		$tutorPaymentProfile = TutorPayments::where([
			'hub_id' => $classTiming->location_id,
			'tutor_id' => $classTiming->teacher_id
		])->first();
		

		if(!is_null($tutorPaymentProfile))
		{	
			$dayOfWeek = strtolower($classTiming->date->format('l'));
			$startTime = $classTiming->start_time;
			$endTime = $classTiming->end_time;

			$carbonStartTime = Carbon::createFromTimestamp(strtotime($startTime));
			$carbonEndTime = Carbon::createFromTimestamp(strtotime($endTime));

			$diffInHours = $carbonEndTime->diffInHours($carbonStartTime);

			$allTimings = json_decode($tutorPaymentProfile->timings,true);

			$dayTimings = collect($allTimings)->where('day',$dayOfWeek)->first();

			$dayTimings = collect($dayTimings['timings']);

			$relevantPaymentProfile = $dayTimings->where('from','<',$startTime)->where('to','>',$startTime)->first();

			if(!is_null($relevantPaymentProfile))
			{
				$commissionType = $relevantPaymentProfile['commission_type'];
				$settlementType = $relevantPaymentProfile['settlement_type'];
				$commissionValue = $relevantPaymentProfile['commission_value'];
				$minEnrollment = $relevantPaymentProfile['min_enrollment'];
				$maxEnrollment = $relevantPaymentProfile['max_enrollment'];
				$enrollments = $classTiming->enrolledLearners->count();				
				$totalAmount = 0;

				$creditsPerUnit =  $classTiming->ahamClass->charge_multiply; // Credit is 1100
				$creditsPerHour = $creditsPerUnit/$diffInHours;
				$amountPerHour = $creditsPerHour*1100;

				$enrollmentCount = 0;
				
				if($maxEnrollment != 0)
				{
					$enrollmentCount = $enrollments > $maxEnrollment ? $maxEnrollment : $enrollments;
				}
				
				if($minEnrollment != 0)
				{
					$enrollmentCount = $enrollmentCount < $minEnrollment ? $minEnrollment : $enrollmentCount;
				}

				if($enrollmentCount == 0)
				{
					$enrollmentCount = $enrollments;
				}
				
				switch ($commissionType) {
					case 'percentage':
						// Formula = ((percentage/100)*amountperhour)*enr
						$effectivePricePerHour = ($commissionValue/100)*$amountPerHour;
						$totalAmount = $effectivePricePerHour*$diffInHours*$enrollmentCount;
						break;
					
					case 'amount':
						
						if($settlementType == 'fixed')
						{
							$effectivePricePerHour = $commissionValue;
							$totalAmount = $effectivePricePerHour*$diffInHours;
						}

						if($settlementType == 'per_enrollment')
						{
							$effectivePricePerHour = $commissionValue;
							$totalAmount = $effectivePricePerHour*$diffInHours*$enrollmentCount;
						}

						break;
				}

			}
		}

		$classTiming->tutor_payment = $totalAmount;
		$classTiming->tutor_payment_calculator = 'system';
		$classTiming->save();

		return true;
	}
}
