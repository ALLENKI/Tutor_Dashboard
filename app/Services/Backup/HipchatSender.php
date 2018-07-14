<?php namespace Aham\Services\Backup;

use Aham\Helpers\HipchatHelper;

class HipchatSender extends \Spatie\Backup\Notifications\BaseSender
{
	public function send()
	{
        $hipchat = new HipchatHelper();

        $hipchat->sendMessage('Aham',
        [
            'message' => $this->subject,
            'id' => 'Aham',
            'from' => env('APP_URL'),
            'notify' => false,
            'color' => $this->type === static::TYPE_SUCCESS ? 'green' : 'red'
        ]);
	}
}