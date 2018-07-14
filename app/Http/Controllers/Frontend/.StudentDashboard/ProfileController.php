<?php

namespace Aham\Http\Controllers\Frontend\StudentDashboard;

use Aham\Http\Controllers\Frontend\BaseController;

use Sentinel;
use Assets;
use Carbon;

use Aham\Models\SQL\CloudinaryImage;
use Aham\Models\SQL\User;
use Aham\Models\SQL\Topic;
use Aham\Models\SQL\AhamClass;

class ProfileController extends StudentDashboardBaseController
{

    public function __construct()
    {
        parent::__construct();
    }

    public function profile($slug)
    {
    	// Find user with this username

    	$foundUser = User::where('username',$slug)->firstOrFail();

    	if(!$foundUser->student)
    	{
    		abort(404);
    	}

    	$student = $foundUser->student;

        $topics = Topic::topic()->active()->pluck('id')->toArray();

        $assessed = $student->assessments()->pluck('topic_id')->toArray();

        $enrollments = $student->enrollments->pluck('class_id')->toArray();


        $upcomingClasses = AhamClass::whereIn('status',['open_for_enrollment','scheduled'])
                                ->whereIn('id',$enrollments)
                                ->where('start_date','>=', Carbon::now())
                                ->orderBy('start_date','asc')
                                ->get();

        $inSessionClasses = AhamClass::whereIn('status',['in_session'])
                                    ->whereIn('id',$enrollments)
                                    ->orderBy('start_date','asc')
                                    ->get();


    	return view('frontend.student_dashboard.profile',compact('student','upcomingClasses','inSessionClasses'));
    }

    public function assessment($slug)
    {
        Assets::add('js/d3/d3.v3.min.js');
        Assets::add('js/d3/tree_collapsible_aham.js');
        Assets::add('js/d3/tree_radial.js');

        // Find user with this username

        $foundUser = User::where('username',$slug)->firstOrFail();

        if(!$foundUser->student)
        {
            abort(404);
        }

        $student = $foundUser->student;

        $topics = Topic::topic()->active()->pluck('id')->toArray();

        $assessed = $student->assessments()->pluck('topic_id')->toArray();

        $courses = array_intersect($topics,$assessed);

        $courses = Topic::whereIn('id',$courses)->take(3)->get();

        return view('frontend.student_dashboard.assessment',compact('student','courses'));
    }

   public function populateChildren($topic, $data)
   {
        $result = [];

        switch ($topic->type) {
            case 'topic':
                if(in_array($topic->parent->id, $data['sub_categories']))
                {
                    $result['name'] = $topic->name;
                    $result['code'] = $topic->code;
                    $result['color'] = 'red';
                    $result['link'] = url('classes-in-'.$topic->slug);
                }
                if(in_array($topic->id, $data['topics']))
                {
                    $result['color'] = '#34c424';
                    $result['radius'] = 6;
                    $result['link'] = url('classes-in-'.$topic->slug);
                }
            break;
            
            case 'sub-category':
                if(in_array($topic->id, $data['sub_categories']))
                {
                    $result['name'] = $topic->name;
                    $result['code'] = $topic->code;
                    $result['color'] = '#2196F3';
                    // $result['toggle'] = rand(0,1) ? true : false;
                }
                break;
            
            case 'subject':
                if(in_array($topic->id, $data['subjects']))
                {
                    $result['name'] = $topic->name;
                    $result['code'] = $topic->code;
                    $result['color'] = '#2196F3';
                    // $result['toggle'] = rand(0,1) ? true : false;
                }
                break;

            case 'category':
                if(in_array($topic->id, $data['categories']))
                {
                    $result['name'] = $topic->name;
                    $result['code'] = $topic->code;
                    $result['color'] = '#2196F3';
                    // $result['toggle'] = rand(0,1) ? true : false;
                }
                break;

        }

        if(!$topic->children->count())
        {

        }
        else
        {
            foreach($topic->children as $node)
            {
                $children = $this->populateChildren($node, $data);
            
                if(count($children))
                {

                    $result['children'][] = $children;

                }
            }
        }

        return $result;
   }


    public function graph($slug)
    {
        $foundUser = User::where('username',$slug)->firstOrFail();

        if(!$foundUser->student)
        {
            abort(404);
        }

        $student = $foundUser->student;

        $topics = Topic::topic()->active()->pluck('id')->toArray();

        $data['categories'] = [];
        $data['subjects'] = [];
        $data['sub_categories'] = [];
        $data['topics'] = [];

        // Populate student assessed topics and populate array

        foreach($student->assessments as $assessment)
        {
            $topic = $assessment->topic;

            switch ($topic->type) {
                case 'topic':
                    $data['categories'][] = $topic->parent->parent->parent->id;
                    $data['subjects'][] = $topic->parent->parent->id;
                    $data['sub_categories'][] = $topic->parent->id;
                    $data['topics'][] = $topic->id;
                    break;
                
                case 'sub-category':
                    $data['categories'][] = $topic->parent->parent->id;
                    $data['subjects'][] = $topic->parent->id;
                    $data['sub_categories'][] = $topic->id;
                    break;
                
                case 'subject':
                    $data['categories'][] = $topic->parent->id;
                    $data['subjects'][] = $topic->id;
                    break;

                case 'category':
                    $data['categories'][] = $topic->id;
                    break;

            }

        }

        foreach($data as $index => $item)
        {
            $data[$index] = array_unique($item);
        }

        $result = [];

        $result['name'] = $student->user->name;
        $result['color'] = '#2196F3';
        $result['radius'] = 6;
        $result['image'] = cloudinary_url($student->user->present()->picture,array('width'=> 50,'height'=>50)) ;


        $topics = Topic::with('children')
                        ->where('parent_id',0)->get();

        foreach($topics as $topic)
        {   
            $children = $this->populateChildren($topic, $data);

            if(count($children))
            {
                $result['children'][] = $children;
            }
            
        }

        return $result;
    }
}