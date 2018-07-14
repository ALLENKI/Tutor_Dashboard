<?php 

namespace Aham\Helpers;

use Aham\Models\SQL\CouponTemplate;
use Aham\Models\SQL\Coupon;

/**
 * Basic helper class to be used for SSO authentication with Disqus.
 */
class GenerateCouponsHelper {

	public static function generate($id)
	{
		$template = CouponTemplate::find($id);

  		foreach($template->users as $user)
  		{
  			$coupon_code = $template->coupon.'-'.$user->student->code;

  			$coupon_data = [

  				'coupon' => $coupon_code,
  				'valid_from' => $template->valid_from,
  				'valid_till' => $template->valid_till,
  				'type' => $template->type,
  				'additional_type' => $template->additional_type,
  				'additional_value' => $template->additional_value,
  				'max_usage_limit_per_user' => $template->max_usage_limit_per_user,
  				'max_users_limit' => $template->max_users_limit,
  				'min_units_to_purchase' => $template->min_units_to_purchase,
  				'description' => $template->description,
  				'user_id' => $user->id,
  				'active' => true
  			];

  			$coupon = Coupon::where(['coupon' => $coupon_code])->first();

  			if(is_null($coupon))
  			{
  				Coupon::create($coupon_data);
  			}

  		}

	}

}