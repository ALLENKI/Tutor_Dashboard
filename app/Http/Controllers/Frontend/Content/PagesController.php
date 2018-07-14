<?php

namespace Aham\Http\Controllers\Frontend\Content;

use View;
use Sentinel;
use Activation;
use Reminder;
use Validator;
use Input;
use Mail;
use Carbon;
use DB;
use Assets;

use Aham\Models\SQL\Page;

use Aham\Http\Controllers\Frontend\BaseController;

class PagesController extends BaseController
{
    public function __construct()
    {
        parent::__construct();
    }

    public function joinAsTutor()
    {
        Assets::add('youtube-video-player/packages/icons/css/icons.min.css');
        Assets::add('youtube-video-player/css/youtube-video-player.min.css');
        Assets::add('youtube-video-player/js/youtube-video-player.jquery.min.js');

        // Assets::add("youtube-video-player/packages/perfect-scrollbar/perfect-scrollbar.css");
        Assets::add("youtube-video-player/packages/perfect-scrollbar/jquery.mousewheel.js");
        Assets::add("youtube-video-player/packages/perfect-scrollbar/perfect-scrollbar.js");

        $page = Page::where('slug','join-as-a-tutor')
                    ->first();

        if(!$page)
        {
            $page = Page::create(['name' => 'Join As a Tutor']);
        }

        return view('frontend.content.pages.tutor',compact('page'));
    }


    public function joinAsStudent()
    {

        Assets::add('youtube-video-player/packages/icons/css/icons.min.css');
        Assets::add('youtube-video-player/css/youtube-video-player.min.css');
        Assets::add('youtube-video-player/js/youtube-video-player.jquery.min.js');

        // Assets::add("youtube-video-player/packages/perfect-scrollbar/perfect-scrollbar.css");
        Assets::add("youtube-video-player/packages/perfect-scrollbar/jquery.mousewheel.js");
        Assets::add("youtube-video-player/packages/perfect-scrollbar/perfect-scrollbar.js");

        $page = Page::where('slug','join-as-a-student')
                    ->first();

        if(!$page)
        {
            $page = Page::create(['name' => 'Join As a Student']);
        }

        return view('frontend.content.pages.student',compact('page'));
    }


    public function aboutAham()
    {

        Assets::add('youtube-video-player/packages/icons/css/icons.min.css');
        Assets::add('youtube-video-player/css/youtube-video-player.min.css');
        Assets::add('youtube-video-player/js/youtube-video-player.jquery.min.js');

        // Assets::add("youtube-video-player/packages/perfect-scrollbar/perfect-scrollbar.css");
        Assets::add("youtube-video-player/packages/perfect-scrollbar/jquery.mousewheel.js");
        Assets::add("youtube-video-player/packages/perfect-scrollbar/perfect-scrollbar.js");



        $page = Page::where('slug','about-aham')
                    ->first();

        if(!$page)
        {
            $page = Page::create(['name' => 'About Aham']);
        }

        return view('frontend.content.pages.about',compact('page'));
    }

    public function terms()
    {
        return view('frontend.content.pages.terms');
    }

    public function privacyPolicy()
    {
        return view('frontend.content.pages.privacy');
    }

    public function pricing()
    {
        return view('frontend.content.pages.pricing');
    }
}
