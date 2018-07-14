<?php namespace Aham\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Foundation\Inspiring;

use Sentinel;
use Log;
use Carbon;

use Aham\Models\SQL\MobileOtp;
use Aham\Models\SQL\AhamClass;
use Aham\Models\SQL\ClassTiming;

use Aham\Helpers\PushHelper;

use Illuminate\Foundation\Bus\DispatchesJobs;


class ClassNotifications extends Command {

	use DispatchesJobs;

	/**
	 * The console command name.
	 *
	 * @var string
	 */
	protected $name = 'aham:class_notifications';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Install New Aham';

	/**
	 * Execute the console command.
	 *
	 * @return mixed
	 */
	public function handle()
	{
		$today = Carbon::now();
		$tomorrow = Carbon::now()->addDays(1);

		$next3Hours = Carbon::now()->addHours(3);
		$next12Hours = Carbon::now()->addHours(12);
		$next24Hours = Carbon::now()->addHours(24);

		$classTimings = ClassTiming::whereBetween('date',[$today,$tomorrow])
									->where('status','<>','cancelled')
									->get();

	 	// var_dump(Carbon::now()->format('H:i'));

		foreach($classTimings as $classTiming)
		{

			$ahamClass = $classTiming->ahamClass;

			if($ahamClass->teacher)
			{
			    // $this->dispatch(new \Aham\Jobs\Teacher\SendClassPushNotification($ahamClass));
			

				var_dump($classTiming->date->format('d-m-y').' '.$classTiming->start_time);

				$timeStamp = Carbon::createFromTimestamp(strtotime($classTiming->date->format('y-m-d').' '.$classTiming->start_time));

				$hours = $timeStamp->diffInHours($today);

				// 3,6,24

				$send = false;

				switch ($hours) 
				{
					case ($hours > 6 && $hours <= 24):
						if(!$ahamClass->notification_24)
						{
							$ahamClass->notification_24 = true;
							$send = true;
						}
						break;
			
					case ($hours > 3 && $hours <= 6):
						if(!$ahamClass->notification_6)
						{
							$ahamClass->notification_6 = true;
							$send = true;
						}
						break;	

					case ($hours > 0 && $hours <= 3):
						if(!$ahamClass->notification_3)
						{
							$ahamClass->notification_3 = true;
							$send = true;
						}
						break;	

				}

				$ahamClass->save();

		        $teacher = $ahamClass->teacher;
        		$user = $teacher->user;


		        $tokens = $user->pushTokens()->where(['source' => 'aham_tutor','type'=> 'android'])->get();

		        $title = $ahamClass->topic->name;
		        $body = 'Class on '.$timeStamp->format('jS M Y H:i A');
		        $data = [];

		        $data['type'] = 'class_notification';
		        $data['title'] = $title;
		        $data['body'] = $body;
		        $data['class_id'] = $ahamClass->id;

		        if($send)
		        {
	                foreach($tokens as $token)
			        {
			            PushHelper::sendFCMMessageTutorApp($title, $body, $token->push_id, $data);
			        }
		        }


			}

			// var_dump($hours);
		}

		// var_dump($classTimings->count());

	}


}
