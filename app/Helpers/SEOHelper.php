<?php namespace App\Helpers;

use App\Seo;

class SEOHelper {

	/**
	 * undocumented function
	 *
	 * @return void
	 * @author 
	 **/
	public static function get()
	{
		$url = \Request::path();

		$seo = Seo::where('url',$url)->first();

		if(is_null($seo))
		{
			$seo = Seo::create([
					'url' => $url,
					'title' => '',
					'keywords' => '',
					'description' => ''
				]);
		}

		view()->share('seo',$seo);

		return true;
	}

}