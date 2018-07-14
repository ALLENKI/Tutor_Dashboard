<?php

namespace Aham\Http\Controllers\Frontend\GuestSeries;

use View;
use Sentinel;
use Activation;
use Reminder;
use Validator;
use Input;
use Mail;
use Carbon;
use DB;

use Aham\Models\SQL\AhamClass;
use Aham\Models\SQL\GuestSeries;
use Aham\Models\SQL\StudentEnrollment;
use Aham\Models\SQL\GuestSeriesEpisode;
use Aham\Models\SQL\GuestSeriesLevel;
use Aham\Models\SQL\UserEnrollment;

use Aham\Http\Controllers\Frontend\BaseController;

class GuestSeriesController extends BaseController
{
    public function __construct()
    {
        parent::__construct();
    }

    // public function show($slug)
    // {
    //     view()->share('bodyClass', 'fullwidth sticky-header course-single');
    	            
    // 	$guestSeries = GuestSeries::where('slug',$slug)->first();

    //     $enrolled = [];

    //     $canCancel = true;
    //     $enrolledEpisode = null;
    //     $enrolledLevel = null;

    //     if(Sentinel::check())
    //     {
    //         $user = Sentinel::getUser();

    //         if($guestSeries->enrollment_restriction == 'restrict_by_episode')
    //         {
    //             $episodes = $guestSeries->episodes->pluck('id')->toArray();

    //             $enrollments = $user->enrollments->pluck('episode_id')->toArray();

    //             $enrolled = array_intersect($episodes, $enrollments);

    //             if(count($enrolled))
    //             {
    //                 $enrolledEpisode = GuestSeriesEpisode::find(array_values($enrolled)[0]);

    //                 if($enrolledEpisode->enrollment_cutoff->isPast())
    //                 {
    //                     $canCancel = false;
    //                 }
    //             }
    //         }

    //         if($guestSeries->enrollment_restriction == 'restrict_by_level')
    //         {
    //             $levels = $guestSeries->levels->pluck('id')->toArray();

    //             $enrollments = $user->enrollments()->where([
    //                         'user_id' => $user->id,
    //                         'type' => 'level'
    //                     ])->pluck('episode_id')
    //                     ->toArray();

    //             $enrolled = array_intersect($levels, $enrollments);

    //             if(count($enrolled))
    //             {
    //                 $enrolledLevel = GuestSeriesLevel::find(array_values($enrolled)[0]);

    //                 if($enrolledLevel->enrollment_cutoff->isPast())
    //                 {
    //                     $canCancel = false;
    //                 }
    //             }
    //         }

    //     }

    //     // dd(array_values($enrolled)[0]);

    //     return view('frontend.guest_series.show',compact('guestSeries','enrolled','canCancel','enrolledEpisode','enrolledLevel'));
    // }


    public function index($slug)
    {
        view()->share('bodyClass', 'fullwidth sticky-header course-single');
                    
        $guestSeries = GuestSeries::where('slug',$slug)->first();

        $enrolled = [];

        $canCancel = true;
        $enrolledEpisode = null;
        $enrolledLevel = null;

        if(Sentinel::check())
        {
            $user = Sentinel::getUser();

            if($guestSeries->enrollment_restriction == 'restrict_by_episode')
            {
                $episodes = $guestSeries->episodes->pluck('id')->toArray();

                $enrollments = $user->enrollments->pluck('episode_id')->toArray();

                $enrolled = array_intersect($episodes, $enrollments);

                if(count($enrolled))
                {
                    $enrolledEpisode = GuestSeriesEpisode::find(array_values($enrolled)[0]);

                    if($enrolledEpisode->enrollment_cutoff->isPast())
                    {
                        $canCancel = false;
                    }
                }
            }

            if($guestSeries->enrollment_restriction == 'restrict_by_level')
            {
                $levels = $guestSeries->levels->pluck('id')->toArray();

                $enrollments = $user->enrollments()->where([
                            'user_id' => $user->id,
                            'type' => 'level'
                        ])->pluck('episode_id')
                        ->toArray();

                $enrolled = array_intersect($levels, $enrollments);

                if(count($enrolled))
                {
                    $enrolledLevel = GuestSeriesLevel::find(array_values($enrolled)[0]);

                    if($enrolledLevel->enrollment_cutoff->isPast())
                    {
                        $canCancel = false;
                    }
                }
            }

        }

        $enrolled = array_values($enrolled);


        if($slug == "art-workshop-at-aham")
        {
            return redirect()->route('series::show','art-for-summer-2017');
        }

        if($slug == "chess-workshop")
        {
            return redirect()->route('series::show','chess-beginners-course-april');
        }

        return view('frontend.guest_series.index',compact('guestSeries','enrolled','canCancel','enrolledEpisode','enrolledLevel'));
    }

    public function enroll($slug,$id)
    {
        $user = Sentinel::getUser();

        $episode = GuestSeriesEpisode::find($id);

        $enrollment = UserEnrollment::firstOrCreate([
            'user_id' => $user->id,
            'episode_id' => $episode->id
        ]);

        $this->dispatch(new \Aham\Jobs\SendWorkshopConfirmMail($enrollment));

        flash()->success('Successfully enrolled.');

        return redirect()->route('series::show',$slug);

    }

    public function cancelEnroll($slug, $episode)
    {
        $user = Sentinel::getUser();

        UserEnrollment::firstOrCreate([
            'user_id' => $user->id,
            'episode_id' => $episode
        ])->delete();

        flash()->success('Successfully cancelled enrollment.');

        return redirect()->route('series::show',$slug); 
    }
}
