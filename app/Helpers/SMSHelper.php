<?php namespace Aham\Helpers;

use Aham\Models\SQL\Sms;

class SMSHelper {

	/**
	 * undocumented function
	 *
	 * @return void
	 * @author 
	 **/
	public function __construct()
	{
		$this->sms = app()->make('Aham\Services\SMS\SMSInterface');
	}

	/**
	 * undocumented function
	 *
	 * @return void
	 * @author 
	 **/
	public function sendMessage($number, $message)
	{
		$result = $this->sms->send($number,$message);

		Sms::create(['number' => $number, 'message' => $message,'result' => $result]);

		return $result;
	}

}