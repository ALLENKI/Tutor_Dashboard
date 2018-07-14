<?php

namespace Aham\Http\Controllers\Dashboard\Student;

use Validator;
use Input;
use Assets;

use Aham\Models\SQL\Topic;
use Aham\Models\SQL\TopicsLookup;
use Aham\Models\SQL\AhamClass;
use Cviebrock\EloquentTaggable\Models\Tag;

class CoursesController extends StudentDashboardBaseController
{
    public function __construct()
    {
        parent::__construct();
    }

    public function catalog($slug = NULL)
    {
        $q = Input::get('q','');

        $tags_search = false;

        if($q != '')
        {
            $tags_search = true;

            $tagged_topics =  Topic::where(function ($query) use ($q) {
                                    
                                    $query
                                    ->where('name', 'LIKE', '%'.$q.'%')
                                    ->orWhereHas('tags', function($query) use ($q)
                                    {
                                        $query->where(function ($query) use ($q)
                                        {
                                            $query->where('normalized', 'LIKE', '%'.$q.'%');
                                        });
                                        
                                    });

                                })->pluck('id')->toArray();

        }

        $subjects = Topic::leftJoin('topics_lookup','topics.id','=','topics_lookup.subject_id')
           ->where('type','subject')
           ->whereIn('topics_lookup.status',['active','in_progress'])
           ->selectRaw('topics.*, count(topics_lookup.topic_id) AS `count`')
           ->groupBy('topics.id')
           ->orderBy('count','DESC')
           ->get();

        // If anyone selected a subject

        if(Input::has('subject'))
        {
            $selectedSubject = Topic::with('children')
                            ->where('slug',Input::get('subject'))
                            ->first();

            // If anyone selected a sub-category
            if(Input::has('sub-category'))
            {
                $selectedSubCategory = Topic::with('children')
                            ->where('slug',Input::get('sub-category'))
                            ->first();


                $topics = TopicsLookup::with('topic')
                            ->where('sub_category_id',$selectedSubCategory->id)
                            ->select('*',\DB::raw('(SELECT count(*) FROM classes WHERE classes.topic_id = topics_lookup.topic_id and ( classes.status = "scheduled" or classes.status = "open_for_enrollment")) as count_links' ))
                            ->orderBy('count_links','desc');

                if($tags_search)
                {
                    $topics = $topics->whereIn('topic_id',$tagged_topics);
                }
                            

                $topics =  $topics->whereIn('topics_lookup.status',['active','in_progress'])->paginate(12);
            }
            else
            {

                $topics = TopicsLookup::with('topic')
                            ->where('subject_id',$selectedSubject->id)
                            ->select('*',\DB::raw('(SELECT count(*) FROM classes WHERE classes.topic_id = topics_lookup.topic_id and ( classes.status = "scheduled" or classes.status = "open_for_enrollment")) as count_links' ))
                            ->orderBy('count_links','desc');

                if($tags_search)
                {
                    $topics = $topics->whereIn('topic_id',$tagged_topics);
                }
                        
                $topics =  $topics->whereIn('topics_lookup.status',['active','in_progress'])->paginate(12);
            }


            $subCategories = Topic::leftJoin('topics_lookup','topics.id','=','topics_lookup.sub_category_id')
                       ->where('type','sub-category')
                       ->whereIn('topics_lookup.status',['active','in_progress'])
                       ->whereIn('sub_category_id',$selectedSubject->children->pluck('id')->toArray())
                       ->selectRaw('topics.*, count(topics_lookup.topic_id) AS `count`')
                       ->groupBy('topics.id')
                       ->orderBy('count','DESC')
                       ->get();

            // dd($topics);

            return view('dashboard.student.courses.catalog',compact('subjects','topics', 'selectedSubject','subCategories'));

        }
        else
        {

            $topics = TopicsLookup::with('topic')
                            ->whereIn('subject_id',$subjects->pluck('id')
                            ->toArray())
                            ->select('*',\DB::raw('(SELECT count(*) FROM classes WHERE classes.topic_id = topics_lookup.topic_id and ( classes.status = "scheduled" or classes.status = "open_for_enrollment")) as count_links' ))
                            ->orderBy('count_links','desc');


            if($tags_search)
            {
                $topics = $topics->whereIn('topic_id',$tagged_topics);
            }
            
            $topics = $topics->whereIn('status',['active','in_progress'])->paginate(12); 
        }

        return view('dashboard.student.courses.catalog',compact('subjects', 'topics'));
    }

    public function actionEnroll($topic)
    {
        $enrollments = $this->student
                            ->enrollments()
                            ->whereHas('ahamClass',function($query){
                                $query->whereIn('status',['open_for_enrollment','scheduled']);
                            })
                            ->pluck('class_id')
                            ->toArray();

        $classes = $topic
                        ->classes()
                        ->with('topic.units','teacher.user','location.locality.city')
                        ->whereIn('status',['open_for_enrollment','scheduled'])
                        ->orderBy('start_date','asc')
                        ->get();

        $intersected_classes = array_intersect($enrollments, $classes->pluck('id')->toArray());

        if(count($intersected_classes) == 0)
        {
            $result['status'] = 'enroll';

            return $result;
        }

        $enrolledClass = array_values($intersected_classes)[0];
        $enrolledClass = AhamClass::find($enrolledClass);

        if($enrolledClass->status == 'open_for_enrollment')
        {
            $result['status'] = 'enrolled';
            $result['class'] = $enrolledClass;
        }

        if($enrolledClass->status == 'scheduled')
        {
            $result['status'] = 'class_page';
            $result['class'] = $enrolledClass;
        }

        return $result;

    }

    public function show($slug)
    {   
        $topic = Topic::where('slug',$slug)->first();  

        $enrollments = $this->student
                            ->enrollments()
                            ->whereHas('ahamClass',function($query){
                                $query->whereIn('status',['open_for_enrollment','scheduled']);
                            })
                            ->pluck('class_id')
                            ->toArray();

        $classes = $topic->classes()
                        ->with('topic.units','teacher.user','location.locality.city')
                        ->whereIn('status',['open_for_enrollment','scheduled'])
                        ->orderBy('start_date','asc')
                        ->get();

        $enrolledClass = $this->actionEnroll($topic);

        return view('dashboard.student.courses.show',compact('topic','classes','enrollments','enrolledClass'));
    }

    public function prerequisites($slug)
    {   
        Assets::add('js/plugins/visualization/vis/vis.min.css');     
        Assets::add('js/plugins/visualization/vis/vis.min.js');     

        $topic = Topic::where('slug',$slug)->first();  

        $nodes = [];
        $edges = [];

        $node = [];
        $node['id'] = $topic->id;
        $node['label'] = $topic->name;
        $node['title'] = $topic->name;
        $node['mass'] = 10;
        $nodes[] = $node;

        foreach($topic->prerequisites as $prerequisite)
        {
            $assessed = \Aham\Helpers\StudentHelper::isAssessed($prerequisite, $this->student);

            $node = [];
            $node['id'] = $prerequisite->id;
            $node['label'] = $prerequisite->name;
            $node['title'] = $prerequisite->name;
            $node['mass'] = 5;
            $node['color'] = $assessed ? 'green' : 'orange';
            $nodes[] = $node;

            $edge = [];
            $edge['from'] = $topic->id;
            $edge['to'] = $prerequisite->id;
            $edge['arrows'] = 'from';

            $edges[] = $edge;
        }

        $classes = $topic->classes()
                        ->with('topic.units','teacher.user','location.locality.city')
                        ->whereIn('status',['open_for_enrollment','scheduled'])
                        ->orderBy('start_date','asc')
                        ->get();

        $enrolledClass = $this->actionEnroll($topic);

        $all_topics = Topic::topic()->pluck('slug','id')->toArray();

        // dd($all_topics);

        return view('dashboard.student.courses.prerequisites',compact('topic','nodes','edges','classes','enrolledClass','all_topics'));
    }

    public function goals($slug)
    {   
        $topic = Topic::where('slug',$slug)->first();  

        $classes = $topic->classes()
                        ->with('topic.units','teacher.user','location.locality.city')
                        ->whereIn('status',['open_for_enrollment','scheduled'])
                        ->orderBy('start_date','asc')
                        ->get();

        $enrolledClass = $this->actionEnroll($topic);


        return view('dashboard.student.courses.goals',compact('topic','classes','enrolledClass'));
    }

    public function forGoalPage($id)
    {

        // dd(Input::all());

        $topic = Topic::find($id);

        $nodes = Input::get('nodes',[]);
        $edges = Input::get('edges',[]);

        return view('dashboard.student.courses.for_goal_page',compact('topic','nodes','edges'));
    }
}