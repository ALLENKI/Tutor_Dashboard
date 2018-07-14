<?php

namespace Aham\Http\Controllers\Frontend;

use View;
use Sentinel;
use Assets;
use Input;
use Aham\Models\SQL\Topic;
use Aham\Models\SQL\User;
use Aham\Models\SQL\FbChat;
use Aham\Models\SQL\UserEnrollment;
use Aham\Models\SQL\Teacher;
use Aham\Models\SQL\GuestSeries;
use Aham\Models\SQL\StudentCredits;
use Aham\Helpers\StudentHelper;
use Aham\Helpers\RepeatClassHelper;
use Aham\Helpers\PaymentCalculatorHelper;
use Kreait\Firebase;

use Aham\CreditsEngine\Add;
use Aham\Models\SQL\Course;
use Aham\Models\SQL\AhamClass;
use Aham\Models\SQL\ClassTiming;
use Aham\Models\SQL\RepeatClass;
use GraphAware\Neo4j\Client\ClientBuilder;
use Aham\Helpers\PushHelper;


class HomeController extends BaseController
{
    public function __construct()
    {
        parent::__construct();
    }

    public function notifyChat()
    {
        // \Log::info('Notify Chat', Input::all());

        $chatMessage = Input::all();

        $fbChat = FbChat::where('thread',Input::get('threadId'))->first();
        
        if(!is_null($fbChat))
        {
            $ahamClass = AhamClass::find($fbChat->of_id);

            if(Input::get('chatType') == 'text')
            {
                $title = Input::get('altName').' posted a message';
                $body = Input::get('chatMessage');
                $data = [];
            }

            if(Input::get('chatType') == 'file')
            {
                $title = Input::get('altName').' posted an image';
                $body = 'image';
                $data = [];
                $data['file_url'] = Input::get('fileUrl');
            }

            $data['type'] = 'chat_notification';
            $data['title'] = $title;
            $data['body'] = $body;
            $data['class_id'] = $ahamClass->id;

            foreach($ahamClass->enrollments as $enrollment)
            {
                    $user = $enrollment->student->user;

                    $iostokens = $user->pushTokens()->where(['source' => 'aham_learner','type'=> 'ios'])->get();
                    $androidtokens = $user->pushTokens()->where(['source' => 'aham_learner','type'=> 'android'])->get();

                    foreach($iostokens as $token)
                    {
                        PushHelper::sendAppleMessageLearnerApp($title, $body, $token->push_id, $data);
                    }

                    foreach($androidtokens as $token)
                    {
                        PushHelper::sendFCMMessageTutorApp($title, $body, $token->push_id, $data);
                    }

            }

            foreach($ahamClass->timings as $timing)
            {

                if($timing->teacher)
                {
                    $user = $timing->teacher->user;

                    $iostokens = $user->pushTokens()->where(['source' => 'aham_tutor','type'=> 'ios'])->get();
                    $androidtokens = $user->pushTokens()->where(['source' => 'aham_tutor','type'=> 'android'])->get();

                    foreach($iostokens as $token)
                    {
                        PushHelper::sendAppleMessageLearnerApp($title, $body, $token->push_id, $data);
                    }

                    foreach($androidtokens as $token)
                    {
                        PushHelper::sendFCMMessageTutorApp($title, $body, $token->push_id, $data);
                    }

                }

            }

        }


    }

    public function home()
    {

        // $helper = new RepeatClassHelper(RepeatClass::find(5));

        // $helper->process();
        // $helper->addToEnrollments(1);
        // $helper->addToEnrollments(134);
        // $helper->findDates();
        // $newClass = $helper->createClass('04-03-2018');

        // $helper->enrollStudents(AhamClass::find(3522));
        // $helper->scheduleClass(AhamClass::find(3438),AhamClass::find(3490),'03-02-2018');

        // dd("ddd");

        // dd($helper);

        // dd(AhamClass::find(1994)->classUnits);

        // view()->share('headerClass', 'semi-trans-light-50');

        // dd(\Aham\Helpers\PushHelper::send());

        // $this->dispatch(new \Aham\Jobs\SendStudentActivatedMail(User::find(2)->student));

        $topics = Topic::where('show_on_homepage', true)
                        ->whereIn('status', ['active', 'in_progress'])
                        ->get();
        $tutors = Teacher::where('show_on_homepage', true)->get();

        // dd($homepage_scroll);
        // dd($homepage_scroll_message);

        Assets::add('revolution/css/revolution-all.css');
        Assets::add('revolution/js/jquery.themepunch.tools.min.js');
        Assets::add('revolution/js/jquery.themepunch.revolution.min.js');

        return view('frontend.home.home', compact('topics', 'tutors'));
    }

    public function newSlider()
    {
        return view('frontend.home_slider.slider');
    }

    public function summerCamp()
    {
        view()->share('bodyClass', 'fullwidth sticky-header course-single');
        return view('frontend.home.summer_camp');
    }

    public function contact()
    {
        return view('frontend.home.contact');
    }

    public function bet()
    {
        return view('frontend.home.bet');
    }

    public function ahamLocation()
    {
        Assets::add('youtube-video-player/packages/icons/css/icons.min.css');
        Assets::add('youtube-video-player/css/youtube-video-player.min.css');
        Assets::add('youtube-video-player/js/youtube-video-player.jquery.min.js');

        // Assets::add("youtube-video-player/packages/perfect-scrollbar/perfect-scrollbar.css");
        Assets::add('youtube-video-player/packages/perfect-scrollbar/jquery.mousewheel.js');
        Assets::add('youtube-video-player/packages/perfect-scrollbar/perfect-scrollbar.js');

        Assets::add('revolution/css/revolution-all.css');
        Assets::add('revolution/js/jquery.themepunch.tools.min.js');
        Assets::add('revolution/js/jquery.themepunch.revolution.min.js');

        return view('frontend.home.locationG');
    }

    public function ahamBanjara()
    {
        Assets::add('youtube-video-player/packages/icons/css/icons.min.css');
        Assets::add('youtube-video-player/css/youtube-video-player.min.css');
        Assets::add('youtube-video-player/js/youtube-video-player.jquery.min.js');

        // Assets::add("youtube-video-player/packages/perfect-scrollbar/perfect-scrollbar.css");
        Assets::add('youtube-video-player/packages/perfect-scrollbar/jquery.mousewheel.js');
        Assets::add('youtube-video-player/packages/perfect-scrollbar/perfect-scrollbar.js');

        Assets::add('revolution/css/revolution-all.css');
        Assets::add('revolution/js/jquery.themepunch.tools.min.js');
        Assets::add('revolution/js/jquery.themepunch.revolution.min.js');

        return view('frontend.home.location2');
    }

    public function profile()
    {
        return view('frontend.home.profile');
    }

    public function tutor_profile()
    {
        return view('frontend.home.tutor_profile');
    }

    public function course($slug)
    {
        $topic = Topic::where('slug', $slug)->first();

        $user = Sentinel::getUser();

        StudentHelper::isAvailable($topic->classes->last(), $user->student);

        return view('frontend.home.course', compact('topic'));
    }

    public function categories()
    {
        $topics = Topic::topic()->get();

        return view('frontend.home.categories', compact('topics'));
    }

    public function period()
    {
        return view('frontend.home.class');
    }

    public function suggest()
    {
        $pluck = [];

        $topics = Topic::where('name', 'LIKE', '%' . Input::get('query') . '%')
                        ->get();

        foreach ($topics as $topic) {
            $list = [];

            $list['value'] = $topic->name;
            $list['link'] = url('classes-in-' . $topic->slug);

            $pluck[] = $list;
        }

        return $pluck;
    }

    public function tags()
    {
        return \Cviebrock\EloquentTaggable\Models\Tag::where('name', 'LIKE', Input::get('term') . '%')->select('name as label', 'name as value')->get();
    }

    public function phantom()
    {
        return view('backend.render_unit');
    }

    public function mimicEmail()
    {
        // $user = User::find(2);

        $enrollment = UserEnrollment::find(40);
        $user = $enrollment->user;

        // dd($enrollment->level);

        // $this->dispatch(new \Aham\Jobs\SendLevelWorkshopConfirmMail($enrollment));

        return view('emails_new.workshops.level_confirm', compact('user', 'enrollment', 'guestSeries'));
    }

    public function mimicPush()
    {
        $creditsModel = StudentCredits::find(106);

        $this->dispatch(new \Aham\Jobs\Student\RefundedCreditsMail($creditsModel));
    }

    public function getNodes($topics)
    {
       $nodes = new \Illuminate\Support\Collection;

       foreach($topics as $topic)
       {
            if($topic->children->count() == 0)
            {
                $nodes->push($topic);
            }
            else
            {
                $children = $this->getNodes($topic->children);

                foreach($children as $child)
                {
                    $nodes->push($child);
                }
            }
       }

       return $nodes;

    }

    public function testCrap()
    {
        $ahamClass = AhamClass::find(7880);
        PaymentCalculatorHelper::calculateForClass($ahamClass);
        // PaymentCalculatorHelper::calculateForTiming($classTiming);
    }
}
