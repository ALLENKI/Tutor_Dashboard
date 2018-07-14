<?php

namespace Aham\Http\Controllers\V2\common;

use Aham\Http\Controllers\Controller;
use Aham\Models\SQL\Topic;
use Aham\Models\SQL\Course;
use Illuminate\Http\Request;
use Aham\Models\SQL\Location;
use Aham\Models\SQL\HubTopic;

class CatalogueController extends Controller
{
    public $courseTopics;

    public function __construct()
    {
         $this->courseTopics = [];
    }

    public function subject($hub)
    {
        $hub =  Location::where('slug',$hub)->first();
    
        $topicsList = HubTopic::where('hub_id',$hub->id)
                                ->where('of_type',Topic::class)
                                ->pluck('of_id')->toArray();

        $topicsList = Topic::with('children.children','units')
                            ->whereIn('id',$topicsList)
                            ->where('type','category')->get();

        $subCategoryTree = [];

        foreach ($topicsList as $category) {
            $subjectBranch = [];
            $subjectBranch['value'] = $category->id;
            $subjectBranch['label'] = $category->name;
            $subjectBranch['children'] = $this->children($category, false,false);
            $subCategoryTree[] = $subjectBranch;
        }

        return $subCategoryTree;
    }

    public function categoryTree($hub)
    {
        $hub =  Location::where('slug',$hub)->first();
    
        $topicsList = HubTopic::where('hub_id',$hub->id)
                                ->where('of_type',Topic::class)
                                ->pluck('of_id')->toArray();
                    
        $categories = Topic::with('children','units')
                            ->whereIn('id',$topicsList)
                            ->where('type','category')
                            ->get();

        $categoryTree = [];

        foreach ($categories as $category) {
            $categoryBranch = [];
            $categoryBranch['value'] = $category->id;
            $categoryBranch['label'] = $category->name;
            $categoryTree[] = $categoryBranch;
        }

        return $categoryTree;
    }

    public function children($topic, $children = false, $deep = false)
    {
        $topicChildren = [];
        foreach ($topic->children as $child) {
            $topicChild = [];
            $topicChild['value'] = $child->id;
            $topicChild['label'] = $child->name;
            $topicChild['status'] = $child->status;
            $topicChild['type'] = $child->type;
            if ($children) {
                $topicChild['children'] = $this->children($child, $deep);
            }
            if ($child->units->count()) {
                $topicChild['units'] = $child->units->count();
                $topicChild['label'] .= ' - '.$topicChild['units'].' Units';
            }
            $topicChildren[] = $topicChild;
        }

        return $topicChildren;
    }
    
    public function courseTree($hub)
    {
        $hub =  Location::where('slug',$hub)->first();
    
        $topicsList = HubTopic::where('hub_id',$hub->id)
                                ->where('of_type',Course::class)
                                ->pluck('of_id')
                                ->toArray();

        $course = Course::whereIn('id',$topicsList)->get();

        return $course;
    }

    public function hubTopics($hub)
    {
        $hub =  Location::where('slug',$hub)->first();
    
        $topicsList = HubTopic::where('hub_id',$hub->id)
                                ->where('of_type',Course::class)
                                ->pluck('of_id')->toArray();


       return Topic::whereIn('id',$topicsList)->select('id','name')->get();
        
    }

    public function courseTopicTree($hub)
    {
        $hub =  Location::where('slug',$hub)->first();
    
        $topicsList = HubTopic::where('hub_id',$hub->id)
                                ->where('of_type',Topic::class)
                                ->pluck('of_id')
                                ->toArray();

        $topicsList = Topic::whereIn('id',$topicsList)
                            ->get();
        
        // dd($topicsList);

        $this->fillTopicForCourses($topicsList);
        
        return $this->courseTopics;
    }

    public function fillTopicForCourses($topics)
    {

            foreach ($topics as $lists) {

                try {

                        if ($lists->children->count()) {

                            $this->fillTopicForCourses($lists->children);
                        
                        } else {

                            $this->courseTopics[$lists->id] = ['id' => $lists->id,'name' => $lists->name];

                        } 

                } catch (\Exception $e) {
                        var_dump('no children');
                }
                
            }

    }

    public function topicTree($hub)
    {
        $hub =  Location::where('slug',$hub)->first();
    
        $topicsList = HubTopic::where('hub_id',$hub->id)
                                ->where('of_type',Topic::class)
                                ->pluck('of_id')
                                ->toArray();
        
        $topicsList = Topic::with('children.children.children')
                            ->whereIn('id',$topicsList)->get();

        $subCategoryTree = [];

        foreach ($topicsList as $category) {
            $subjectBranch = [];
            // var_dump($category);
            $subjectBranch['value'] = $category->id;
            $subjectBranch['label'] = $category->name;
            $subjectBranch['type'] = $category->type;



            if ($category->type != 'topic') {
                $subjectBranch['children'] = $this->children($category, true,false);
            }

            if ($category->type == 'sub-category') {

                $subjectBranch['children'] = $this->children($category, false,false);

            }
            
            $subCategoryTree[] = $subjectBranch;
        }

        return $subCategoryTree;
    }

}