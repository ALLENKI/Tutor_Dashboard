<?php

namespace Aham\Managers;

use Illuminate\Contracts\Mail\Mailer;

use Mail;

class SuperMail
{
	public static function mail($view, $data, $subject, $to, $cc = [])
	{
		if(env('APP_DEBUG') == 'true')
		{
			$subject = '['.env('APP_URL').'] '.$subject;
		}

        Mail::send($view, $data, function ($message) use($subject, $to, $cc) {
            
            $message->to($to)
            		// ->bcc('contactus@ahamlearning.com')
                    ->subject($subject);
        });

        return true;
	}
}