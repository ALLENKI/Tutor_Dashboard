<?php namespace Aham\Helpers;

use GorkaLaucirica\HipchatAPIv2Client\Auth\OAuth2;
use GorkaLaucirica\HipchatAPIv2Client\Client;
use GorkaLaucirica\HipchatAPIv2Client\API\RoomAPI;
use GorkaLaucirica\HipchatAPIv2Client\Model\Message;

class HipchatHelper {

	protected $roomAPI;

	/**
	 * undocumented function
	 *
	 * @return void
	 * @author 
	 **/
	public function __construct()
	{
        $auth = new OAuth2('A0XGKcB3lrMsJ5R3cx3TjjDeqtDFIKsg287hRngk');
        $client = new Client($auth);
        $this->roomAPI = new RoomAPI($client);
	}

	/**
	 * undocumented function
	 *
	 * @return void
	 * @author 
	 **/
	public function sendMessage($room, $message)
	{
		$message['date'] = \Carbon::now();
		$message['message_format'] = 'html';
		
		$message = new Message($message);

		try
		{
			$this->roomAPI->sendRoomNotification($room,$message);
		}
		catch(\Exception $e){

		}
		
	}

}