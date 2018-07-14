<?php

namespace Aham\Http\Controllers\V2\AdminDB;

use Aham\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Aham\Repositories\TopicRepository;
use Aham\Models\SQL\Topic;
use Aham\Models\SQL\Course;
use Aham\Models\SQL\Location;
use Aham\Models\SQL\HubTopic;
use Aham\Models\SQL\TopicPrerequisite;
use Input;

use Aham\TransformersV2\CourseCatalogTopicTransformer;
use Aham\TransformersV2\CourseCatalogCourseTransformer;

use Aham\CourseCatalog\TopicHelper;

use Aham\Services\Storage\CDNInterface;

use Aham\Models\SQL\File;

class HubController extends BaseController
{


    public function __construct()
    {
        
    }

    public function getHubSelectedTopics($hub)
    {
        $hub = Location::whereSlug($hub)->first();

        $topics = HubTopic::where([
            'hub_id' => $hub->id,
            'of_type' => Topic::class
        ])->pluck('of_id')->toArray();

        $topics = Topic::whereIn('id',$topics)->get();

        $courses = HubTopic::where([
            'hub_id' => $hub->id,
            'of_type' => Course::class
        ])->pluck('of_id')->toArray();

        $courses = Course::whereIn('id',$courses)->get();

        return [
            'topics' => $topics,
            'courses' => $courses,
        ];
    }

    public function getTopics($hub, $filter)
    {
        if($filter == 'topics')
        {
            $topics = Topic::all();

            $topics = $topics->map(function($topic){
                $topic->name = $topic->name.' ('.$topic->type.')';
                return $topic;
            });

            return $this->response()->collection($topics,new CourseCatalogTopicTransformer);
        }

        if($filter == 'courses')
        {
            $courses = Course::all();

            return $this->response()->collection($courses,new CourseCatalogCourseTransformer);
        }
    }

    public function assignTopics($hub)
    {
        $hub = Location::whereSlug($hub)->first();

        $type = request()->get('selection');

        foreach(request()->get('items') as $item)
        {
            if($type == 'courses')
            {
                HubTopic::firstOrCreate([
                    'hub_id' => $hub->id,
                    'of_id' => $item,
                    'of_type' => Course::class
                ]);
            }

            if($type == 'topics')
            {
                HubTopic::firstOrCreate([
                    'hub_id' => $hub->id,
                    'of_id' => $item,
                    'of_type' => Topic::class
                ]);
            }
        }

        return \Response::json(array(
          'success' => true
        ), 200);

    }

    public function removeHubTopic($hub)
    {
        $hub = Location::whereSlug($hub)->first();

        $type = request()->get('type');

        if($type == 'topic')
        {
            HubTopic::where([
                'hub_id' => $hub->id,
                'of_id' => request()->get('id'),
                'of_type' => Topic::class
            ])->delete();
        }

        if($type == 'course')
        {
            HubTopic::where([
                'hub_id' => $hub->id,
                'of_id' => request()->get('id'),
                'of_type' => Course::class
            ])->delete();
        }

        return \Response::json(array(
          'success' => true
        ), 200);
    }

}
