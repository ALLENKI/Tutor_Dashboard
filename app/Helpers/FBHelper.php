<?php namespace Aham\Helpers;

use Aham\Models\SQL\FbUser;
use Aham\Models\SQL\FbChat;
use Aham\Models\SQL\FbParticipant;
use Kreait\Firebase;

class FBHelper {

	public function createUser($user)
	{
		$fbUser = FbUser::where([
						'email' => $user->email
				  ])->first();

		if(is_null($fbUser))
		{
			$fbUser = FbUser::create([
				'user_id' => $user->id,
				'email' => $user->email,
				'password' => md5('aham'.$user->email)
			]);
		}

		return $fbUser;
	}

	public function createChatForClass($ahamClass)
	{
		$iv = str_random(4);

		$fbChat = FbChat::where([
			'of_id' => $ahamClass->id,
			'of_type' => get_class($ahamClass),
			'type' => 'class'
		])->first();

		if(is_null($fbChat))
		{
			$fbChat = FbChat::create([
				'of_id' => $ahamClass->id,
				'of_type' => get_class($ahamClass),
				'type' => 'class',
				'name' => 'Chat for '.$ahamClass->code,
				'location_id' => $ahamClass->location->id,
				'thread' => md5($iv.$ahamClass->code)
			]);
		}

		return $fbChat;
	}

	public function syncParticipants($fbChat, $class)
	{
		if($class->teacher)
		{
			FbParticipant::firstOrCreate([
				'fb_chat_id' => $fbChat->id,
				'user_id' => $class->teacher->user->id
			]);
		}

		foreach($class->enrollments as $enrollment)
		{
			FbParticipant::firstOrCreate([
				'fb_chat_id' => $fbChat->id,
				'user_id' => $enrollment->student->user->id
			]);
		}

        $firebase = (new Firebase\Factory())
            ->withCredentials(app_path('google-service-account.json'))
            ->withDatabaseUri('https://ahamchat.firebaseio.com/')
            ->create();

        $database = $firebase->getDatabase();

        $participants = $fbChat->participants->pluck('user_id')->toArray();

        $databaseUrl = env('FB_PREFIX')."/messageThreadMetadata/".$fbChat->thread;

        $reference = $database->getReference($databaseUrl);

        $snapshot = $reference->getSnapshot();

        $data = $snapshot->getValue();

        $data['participants'] = implode(',',$participants);

        $result = $reference->set($data);

        return $result;

	}

	public function addParticipant($fbChat, $user)
	{

		$fbParticipant = FbParticipant::firstOrCreate([
			'fb_chat_id' => $fbChat->id,
			'user_id' => $user->id
		]);


        $firebase = (new Firebase\Factory())
            ->withCredentials(app_path('google-service-account.json'))
            
            ->withDatabaseUri('https://ahamchat.firebaseio.com/')
            ->create();

        $database = $firebase->getDatabase();

        $participants = $fbChat->participants->pluck('user_id')->toArray();

        $databaseUrl = env('FB_PREFIX')."/messageThreadMetadata/".$fbChat->thread;

        $reference = $database->getReference($databaseUrl);

        $snapshot = $reference->getSnapshot();

        $data = $snapshot->getValue();

        $data['participants'] = implode(',',$participants);

        $result = $reference->set($data);

		return $fbParticipant;
	}

}
