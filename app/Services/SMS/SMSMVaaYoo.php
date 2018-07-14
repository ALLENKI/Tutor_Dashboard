<?php namespace Aham\Services\SMS;

class SMSMVaaYoo implements SMSInterface {

	/**
	 * undocumented function
	 *
	 * @return void
	 * @author 
	 **/
	public function send($number,$message)
	{
		$ch = curl_init();
		$user="ajitha@ahamlearning.com:ahamlearning";
		$receipientno=$number; 
		$senderID="AHAMLH"; 
		$msgtxt=$message; 
		$message = rawurlencode($message);
		curl_setopt($ch,CURLOPT_URL,  "http://api.mVaayoo.com/mvaayooapi/MessageCompose");
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, "user=$user&senderID=$senderID&receipientno=$receipientno&msgtxt=$msgtxt");
		$status = curl_exec($ch);

		if(empty ($status))
		{
		 	//echo " buffer is empty "; 
		}
		else
		{
		 	//echo $buffer; 
		} 

		curl_close($ch);

		return $status;
	}

}