<?php

namespace Aham\Http\Controllers\Dashboard\Teacher;

use Aham\Managers\TeacherClassesManager;

use Validator;
use Input;
use Assets;

use Aham\Models\SQL\Topic;
use Aham\Models\SQL\TopicsLookup;

class CoursesController extends TeacherDashboardBaseController
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

            return view('dashboard.teacher.courses.catalog',compact('subjects','topics', 'selectedSubject','subCategories'));

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

        return view('dashboard.teacher.courses.catalog',compact('subjects', 'topics'));
    }
    public function show($slug)
    {   
        $topic = Topic::where('slug',$slug)->first();  

        $classes = $topic->classes()
                        ->with('topic.units','teacher.user','location.locality.city')
                        ->whereIn('status',['open_for_enrollment','scheduled'])
                        ->orderBy('start_date','asc')
                        ->paginate(10);

        return view('dashboard.teacher.courses.show',compact('topic','classes'));
    }

    public function classes()
    {  
         
        $teacherClassesManager = new TeacherClassesManager($this->teacher);

        $ongoingClasses = $teacherClassesManager->getInSessionClasses();
        $upcomingClasses = $teacherClassesManager->getUpcomingClasses();



        return view('dashboard.teacher.classes.show',compact('ongoingClasses','upcomingClasses'));
    }

    public function goals($slug)
    {   
        $topic = Topic::where('slug',$slug)->first();  

        return view('dashboard.teacher.courses.goals',compact('topic'));
    }



}