<?php namespace Aham\Managers;

use Input;
use File;
use Carbon;

use Aham\Models\SQL\Coupon;

class CouponManager {

	public static function createFreeClassCoupon($class, $student)
	{
		$coupon = [];

		$coupon['coupon'] = $class->code.'-'.$student->code;
		$coupon['valid_from'] = Carbon::now();
		$coupon['valid_till'] = Carbon::now()->addHours(1);
		$coupon['type'] = 'one-time';
		$coupon['additional_type'] = 'additional_units';
		$coupon['additional_value'] = $class->classUnits->count()*$class->charge_multiply;
		$coupon['max_usage_limit_per_user'] = 1;
		$coupon['max_users_limit'] = 1;
		$coupon['min_units_to_purchase'] = 0;
		$coupon['user_id'] = $student->user->id;
		$coupon['active'] = true;

		$exists = Coupon::where('coupon',$coupon['coupon'])->first();

		if(!is_null($exists))
		{
			$exists->fill($coupon);
			$exists->save();

			return $exists;
		}

		return Coupon::create($coupon);
	}

	public static function isValid($coupon, $user, $credits = 0)
	{
		// Exists?

		$coupon = Coupon::where(\DB::raw('BINARY `coupon`'),'=',$coupon)
								->active()
								->valid()
								->applicable($user)
								->first();


		if(is_null($coupon))
		{
			flash()->error('Coupon does not exist');
			return false;
		}

		if($coupon->type == "lifetime" && $user->student->lifetimeOffer)
		{
			flash()->error('User is already in a lifetime offer');
			return false;
		}


		// If it's a lifetime coupon, and student already is in any offer, he can't subscribe to a different lifetime coupon.

		if($credits < $coupon->min_units_to_purchase)
		{
			flash()->error('You need to buy atleast '.$coupon->min_units_to_purchase.' to use this coupon');
			return false;
		}


		// Is Max Usage per user limit applicable?

		if($coupon->max_usage_limit_per_user > 0)
		{
			$usage = $user->student->ahamCredits()->where('coupon_id',$coupon->id)->count();

			if($usage >= $coupon->max_usage_limit_per_user)
			{
				flash()->error('Per user limit exceeded');
				return false;
			}
		}

		if($coupon->max_users_limit > 0)
		{
			$usage = $coupon->usage()->get()->unique('student_id')->count();

			// dd($usage);

			if($usage > $coupon->max_users_limit)
			{
				flash()->error('Max users limit exceeded');
				return false;
			}
		}

		return $coupon;
	}

}