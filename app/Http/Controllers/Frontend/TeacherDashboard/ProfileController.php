<?php

namespace Aham\Http\Controllers\Frontend\TeacherDashboard;

use Aham\Http\Controllers\Frontend\BaseController;

use Sentinel;
use Assets;
use Carbon;

use Aham\Models\SQL\CloudinaryImage;
use Aham\Models\SQL\User;
use Aham\Models\SQL\Topic;

class ProfileController extends BaseController
{

    public function __construct()
    {
        parent::__construct();
    }

    public function profile($slug)
    {
    	// Find user with this username

    	$foundUser = User::where('username',$slug)->firstOrFail();

    	if(!$foundUser->teacher)
    	{
    		abort(404);
    	}

    	$teacher = $foundUser->teacher;

        $topics = Topic::topic()->active()->pluck('id')->toArray();

        $certified = $teacher->certifications()->pluck('topic_id')->toArray();

        $upcomingClasses = $teacher->classes()
                                ->whereIn('status',['open_for_enrollment','scheduled'])
                                ->where('start_date','>=', Carbon::now())
                                ->orderBy('start_date','asc')
                                ->get();

        $inSessionClasses = $teacher->classes()
                        ->whereIn('status',['in_session'])
                        ->where('start_date','>=', Carbon::now())
                        ->orderBy('start_date','asc')
                        ->get();

    	return view('frontend.tutor_dashboard.overview',compact('teacher','upcomingClasses','inSessionClasses'));
    }

    public function certification($slug)
    {
        Assets::add('js/d3/d3.v3.min.js');
        Assets::add('js/d3/tree_collapsible_aham.js');
        Assets::add('js/d3/tree_radial.js');

        // Find user with this username

        $foundUser = User::where('username',$slug)->firstOrFail();

        if(!$foundUser->teacher)
        {
            abort(404);
        }

        $teacher = $foundUser->teacher;

        $topics = Topic::topic()->active()->pluck('id')->toArray();

        $certified = $teacher->certifications()->pluck('topic_id')->toArray();

        return view('frontend.tutor_dashboard.certification',compact('teacher'));
    }

   public function populateChildren($topic, $data)
   {
        $result = [];

        switch ($topic->type) {
            case 'topic':

                if(in_array($topic->id, $data['topics']))
                {
                    $result['name'] = $topic->name;
                    $result['code'] = $topic->code;

                    $result['color'] = '#34c424';
                    $result['link'] = url('classes-in-'.$topic->slug);
                }
            break;
            
            case 'sub-category':
                if(in_array($topic->id, $data['sub_categories']))
                {
                    $result['name'] = $topic->name;
                    $result['code'] = $topic->code;
                    $result['color'] = '#2196F3';
                }
                break;
            
            case 'subject':
                if(in_array($topic->id, $data['subjects']))
                {
                    $result['name'] = $topic->name;
                    $result['code'] = $topic->code;
                    $result['color'] = '#2196F3';
                }
                break;

            case 'category':
                if(in_array($topic->id, $data['categories']))
                {
                    $result['name'] = $topic->name;
                    $result['code'] = $topic->code;
                    $result['color'] = '#2196F3';
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

        if(!$foundUser->teacher)
        {
            abort(404);
        }

        $teacher = $foundUser->teacher;

        $topics = Topic::topic()->active()->pluck('id')->toArray();

        $data['categories'] = [];
        $data['subjects'] = [];
        $data['sub_categories'] = [];
        $data['topics'] = [];


        foreach($teacher->certifications as $certification)
        {
            $topic = $certification->topic;

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

        $result['name'] = $teacher->user->name;
        $result['color'] = '#2196F3';
        $result['radius'] = 6;
        $result['image'] = cloudinary_url($teacher->user->present()->picture,array('width'=> 50,'height'=>50)) ;


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