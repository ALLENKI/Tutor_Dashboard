<?php

namespace Aham\Http\Controllers\Backend;

use View;
use Aham\Jobs\TestQueues;
use Aham\Models\SQL\AhamClass;
use Aham\Models\SQL\ClassInvitation;

use Aham\Helpers\PushHelper;

class AdminController extends BaseController
{
    public function __construct()
    {
        parent::__construct();
    }

    public function home()
    {
        return view('backend.home');
    }

    public function testQueues()
    {
        // $this->dispatch(new TestQueues());
        
        // PushHelper::send();
        // PushHelper::sendMessage([
        //     'title' => 'Trial 1',
        //     'body' => 'Trial 2'
        // ],"cPujNkTcSYw:APA91bGAq76z90yNzcrA0uomyg67OT-zg6cQOEu29FJ4s1E74AdFmIDnTndGHokt6Hyz49jpehdhVRyw1YEIfBRQ7lpdfRWdQDhQUzmHnk5LiR8Vzimv9PVj9NHV8it43_q7ntGLqtZF");

        // $ahamClass = AhamClass::find(336);
        // $invite = ClassInvitation::find(336);

        // $this->dispatch(new \Aham\Jobs\Student\SendUpcomingClassPushNotification($ahamClass));
        // $this->dispatch(new \Aham\Jobs\Teacher\SendAwardedPushNotification($invite));


        PushHelper::sendFCMMessageTutorApp("hi","bye","dtWcW_kOB0E:APA91bGqiuvI0p5RyqYSpl5MjJj6dSmtOCVXWIUP14nq0Y4EqdOGRbkaLRbUwssFkNoWJEQKdYsrO5018r9j6ZghSNpJgmmKBx1rEhaE5qjGsqAaHRfmDydBz26Pd3wUPYsCRL2sPU7l",['title' => "title",'body'=>'body']);
        PushHelper::sendAppleMessageLearnerApp("hi","bye","dtWcW_kOB0E:APA91bGqiuvI0p5RyqYSpl5MjJj6dSmtOCVXWIUP14nq0Y4EqdOGRbkaLRbUwssFkNoWJEQKdYsrO5018r9j6ZghSNpJgmmKBx1rEhaE5qjGsqAaHRfmDydBz26Pd3wUPYsCRL2sPU7l",['title' => "title",'body'=>'body']);

        dd("ddd");

        dd("die");

        $this->dispatch(new TestQueues());

        flash()->success('Test job successfuly dispatched');

        return redirect()->route('admin::admin');
    }

    public function recon()
    {
        \Artisan::queue('aham:student_reconcile');

        flash()->success('Recon dispatched');

        return redirect()->route('admin::admin');
    }
}
