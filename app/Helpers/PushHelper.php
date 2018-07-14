<?php

namespace Aham\Helpers;

// http://stackoverflow.com/questions/37350268/send-fcm-messages-from-server-side-to-android-device

use paragraph1\phpFCM\Client;
use paragraph1\phpFCM\Message;
use paragraph1\phpFCM\Recipient\Device;
use paragraph1\phpFCM\Notification;
use Sly\NotificationPusher\Model\Device as AppleDevice;
use Sly\NotificationPusher\Model\Message as AppleMessage;
use Sly\NotificationPusher\Model\Push;
use Sly\NotificationPusher\PushManager;
use Sly\NotificationPusher\Adapter\Apns as ApnsAdapter;
use Sly\NotificationPusher\Collection\DeviceCollection;

class PushHelper
{
    public static function send()
    {
        $apiKey = 'AAAAm6KgA6E:APA91bHCLWQZfqXSV82KpNuKNJMHKoxhKmzef88ZwBXpzW9gMRPhsQWV5f-4s1bEqRAr2oashdUagPPJVQL1NHJmE4Rl8du9PSQuoaVx-_VpfXqMvhGngKfvcASfjH7JhfJytXBDE3Nm';
        $client = new Client();
        $client->setApiKey($apiKey);
        $client->injectHttpClient(new \GuzzleHttp\Client());

        $note = new Notification('note_title', 'note_body');

        // $note->setIcon('notification_icon_resource_name')
        //     ->setColor('#ffffff')
        //     ->setBadge(1);

        $message = new Message();
        $message->addRecipient(new Device('cPujNkTcSYw:APA91bGAq76z90yNzcrA0uomyg67OT-zg6cQOEu29FJ4s1E74AdFmIDnTndGHokt6Hyz49jpehdhVRyw1YEIfBRQ7lpdfRWdQDhQUzmHnk5LiR8Vzimv9PVj9NHV8it43_q7ntGLqtZF'));

        $data = [];

        $data = [
             "body" => "First Notification",
             "title" => "Collapsing A",
             "type" => "chat_notification",
             "class_id" => "430"
        ];

        $message->setNotification($note);
        $message->setData($data);

        $response = $client->send($message);

        dd($response);

        return $response;
    }

    public static function sendFCMMessageTutorApp($title, $body, $fcmToken, $data)
    {
        $apiKey = 'AAAAm6KgA6E:APA91bHCLWQZfqXSV82KpNuKNJMHKoxhKmzef88ZwBXpzW9gMRPhsQWV5f-4s1bEqRAr2oashdUagPPJVQL1NHJmE4Rl8du9PSQuoaVx-_VpfXqMvhGngKfvcASfjH7JhfJytXBDE3Nm';
        $client = new Client();
        $client->setApiKey($apiKey);
        $client->injectHttpClient(new \GuzzleHttp\Client());

        $note = new Notification('dfdfd', 'dfdff');

        $note->setIcon('notification_icon_resource_name')
            ->setColor('#ffffff')
            ->setBadge(1);

        // dd($fcmToken);

        $message = new Message();
        $message->addRecipient(new Device($fcmToken));

        // $message = new Message();
        // $message->addRecipient(new Device("d6NuOVYTd_w:APA91bF5vBXn3zpG6phczlNJwm5f2ojijR2GhGB4Bd_07fY4q2i_mXjYxyNBWpSSMhimLYdKh-aYWh15s9b6U7L3iQi1UtHBCyGYQbwkjw0-txzOwMZsSUQLkrii64xhzzOOU1RePLNz"));

        // dd($data);

        // $message->setNotification($note)
        //     	->setData($data);

        $message->setData($data);

        $response = $client->send($message);
        // dd($response);

        return $response;
    }

    public static function sendFCMMessageLearnerApp($title, $body, $fcmToken, $data)
    {
        $apiKey = 'AAAAm6KgA6E:APA91bHCLWQZfqXSV82KpNuKNJMHKoxhKmzef88ZwBXpzW9gMRPhsQWV5f-4s1bEqRAr2oashdUagPPJVQL1NHJmE4Rl8du9PSQuoaVx-_VpfXqMvhGngKfvcASfjH7JhfJytXBDE3Nm';
        $client = new Client();
        $client->setApiKey($apiKey);
        $client->injectHttpClient(new \GuzzleHttp\Client());

        // dd($data);

        $note = new Notification($title, $body);

        $note->setIcon('notification_icon_resource_name')
            ->setColor('#ffffff')
            ->setBadge(1);

        $message = new Message();
        $message->addRecipient(new Device($fcmToken));

        // dd($data);

        // $message->setNotification($note)
        //         ->setData($data);

        $message->setData($data);

        $response = $client->send($message);
        // dd($response);

        return $response;
    }

    public static function sendAppleMessageLearnerApp($title, $body, $fcmToken, $data)
    {
        $apiKey = 'AAAAm6KgA6E:APA91bHCLWQZfqXSV82KpNuKNJMHKoxhKmzef88ZwBXpzW9gMRPhsQWV5f-4s1bEqRAr2oashdUagPPJVQL1NHJmE4Rl8du9PSQuoaVx-_VpfXqMvhGngKfvcASfjH7JhfJytXBDE3Nm';
        $client = new Client();
        $client->setApiKey($apiKey);
        $client->injectHttpClient(new \GuzzleHttp\Client());

        // dd($data);

        $note = new Notification($title, $body);

        $note->setIcon('notification_icon_resource_name')
            ->setColor('#ffffff')
            ->setBadge(1);

        $message = new Message();
        $message->addRecipient(new Device($fcmToken));

        // dd($data);

        $message->setNotification($note)
                ->setData($data);

        // $message->setData($data);

        $response = $client->send($message);
        // dd($response);

        return $response;
    }

    public static function sendAppleMessageLearnerApp2($title, $body, $fcmToken, $data)
    {
        $pushManager = new PushManager(PushManager::ENVIRONMENT_DEV);

        $apnsAdapter = new ApnsAdapter([
            'certificate' => app_path('server_certificates_bundle_sandbox.pem'),
        ]);

        $devices = new DeviceCollection([
            new AppleDevice($fcmToken),
        ]);

        // $message = new AppleMessage($title, $data);

        $message = new AppleMessage($body, [
            'title' => $title,
            'custom' => ['payload' => $data]
        ]);

        $push = new Push($apnsAdapter, $devices, $message);
        $pushManager->add($push);
        $pushManager->push();

        foreach ($push->getResponses() as $token => $response) {
        }
    }

    public static function push($reg_id, $message)
    {
        if (!is_array($reg_id)) {
            $reg_id_array = [];

            $reg_id_array[] = $reg_id;

            $reg_id = [];

            $reg_id = $reg_id_array;
        }

        $url = 'https://fcm.googleapis.com/fcm/send';

        $fields = [
          'registration_ids' => $reg_id,
          'data' => ['title' => $message],
        ];

        $headers = [
          'Authorization: key=AIzaSyDp3Nxh5h_IpjXNk7nP8vIJERbGM_UuTaQ',
          'Content-Type: application/json'
        ];

        $ch = curl_init();

        // Set the url, number of POST vars, POST data
        curl_setopt($ch, CURLOPT_URL, $url);

        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        // Disabling SSL Certificate support temporarly
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));

        // Execute post
        $result = curl_exec($ch);
        if ($result === false) {
            die('Curl failed: ' . curl_error($ch));
        }

        // Close connection
        curl_close($ch);

        dd($result);

        // PushNotification::create([
        // 		'reg_ids' => serialize($reg_id),
        // 		'message' => $message,
        // 		'result' => $result
        // 	]);

        return $result;
    }

    /*
    Parameter Example
        $data = array('post_id'=>'12345','post_title'=>'A Blog post');
        $target = 'single tocken id or topic name';
        or
        $target = array('token1','token2','...'); // up to 1000 in one request
    */
    public static function sendMessage($data, $target)
    {
        //FCM api URL
        $url = 'https://fcm.googleapis.com/fcm/send';
        //api_key available in Firebase Console -> Project Settings -> CLOUD MESSAGING -> Server key
        $server_key = 'AAAAm6KgA6E:APA91bHCLWQZfqXSV82KpNuKNJMHKoxhKmzef88ZwBXpzW9gMRPhsQWV5f-4s1bEqRAr2oashdUagPPJVQL1NHJmE4Rl8du9PSQuoaVx-_VpfXqMvhGngKfvcASfjH7JhfJytXBDE3Nm';

        $fields = [];
        $fields['data'] = $data;
        if (is_array($target)) {
            $fields['registration_ids'] = $target;
        } else {
            $fields['to'] = $target;
        }


        $fcmFields = [
         "to" => "cPujNkTcSYw:APA91bGAq76z90yNzcrA0uomyg67OT-zg6cQOEu29FJ4s1E74AdFmIDnTndGHokt6Hyz49jpehdhVRyw1YEIfBRQ7lpdfRWdQDhQUzmHnk5LiR8Vzimv9PVj9NHV8it43_q7ntGLqtZF",
         "notification" => [
             "body" => "First Notification",
             "title" => "Collapsing A"
         ],
         "data" =>  [
             "body" => "First Notification",
             "title" => "Collapsing A",
             "type" => "chat_notification",
             "class_id" => "430"
            ]
        ];


        //header with content_type api key
        $headers = [
            'Content-Type:application/json',
            'Authorization: key=' . $server_key
        ];

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fcmFields));
        $result = curl_exec($ch);
        if ($result === false) {
            die('FCM Send Error: ' . curl_error($ch));
        }
        curl_close($ch);
        return $result;
    }
}
