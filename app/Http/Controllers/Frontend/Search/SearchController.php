<?php

namespace Aham\Http\Controllers\Frontend\Search;

use View;
use Sentinel;
use Assets;
use Request;
use Carbon;


use Aham\Models\SQL\Topic;     
use Aham\Models\SQL\AhamClass;     

use Aham\Helpers\ClassesFilterHelper;  

use Aham\Http\Controllers\Frontend\BaseController;   

use Aham\Helpers\StudentHelper;
use Aham\Helpers\TopicLookupHelper;

class SearchController extends BaseController
{
    public function __construct()
    {
        parent::__construct();
    }

    public function index()
    {
    	$result = ClassesFilterHelper::getResults(Request::path());
        // return $result;
    	if($result['type'] == 'list')
    	{
            return $this->showCoursesList($result);
    	}
        elseif($result['type'] == 'item')
        {
            view()->share('bodyClass', 'fullwidth sticky-header  course-single');

            $course = $result['item'];

            $topic = Topic::where('visibility',true)->get();

            // dd( $topic );

            $classes = $course->classes()
                                ->whereIn('status',['open_for_enrollment','scheduled'])
                                ->where('start_date','>=', Carbon::now())
                                ->orderBy('start_date','asc')
                                ->get();

            $eligibility = StudentHelper::getEligibilityStatus($course);

            return view('frontend.course.course',compact('course','classes','eligibility','topic'));
        }

    }

    public function showCoursesList($result)
    {
        $subjects = Topic::leftJoin('topics_lookup','topics.id','=','topics_lookup.subject_id')
                        ->where('visibility',true)
                        ->where('type','subject')
                        ->whereIn('topics_lookup.status',['active','in_progress'])
                        ->selectRaw('topics.*, count(topics_lookup.topic_id) AS `count`')
                        ->groupBy('topics.id')
                        ->orderBy('count','DESC')
                        ->get();

        $courseList = $result['list'];

        $selectedSubject = null;

        if(isset($result['subject']))
        {
            $selectedSubject = $result['subject'];
        }

        $classes = $courseList;

        // dd($courseList->first()->present()->picture);

        return view('frontend.search.list',compact('result','courseList','subjects','selectedSubject','classes'));
    }

}
