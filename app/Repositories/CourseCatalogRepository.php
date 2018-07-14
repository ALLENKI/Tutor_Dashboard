<?php

namespace Aham\Repositories;

use Bosnadev\Repositories\Eloquent\Repository;

use Aham\Models\SQL\Course;
use Aham\Models\SQL\Location;
use Aham\Models\SQL\HubTopic;
use Aham\Models\SQL\Topic;

class CourseCatalogRepository extends Repository
{
    public $hub = null;

    public function model()
    {
        return 'Aham\Models\SQL\Topic';
    }

    public function setHub($slug)
    {
        $this->hub = Location::whereSlug($slug)->first();
    }

    public function filterCourses($type, $input, $sort, $status)
    {
        $model = Course::where('name', 'LIKE', '%' . $input . '%');

        if(!is_null($this->hub))
        {
            $coursesList = HubTopic::where([
                'of_type' => Course::class,
                'hub_id' => $this->hub->id
            ])->pluck('of_id')->toArray();

               
            $model = $model->whereIn('id',$coursesList);
        }

        if ($type != 'all') {
            $model = $model->where('type', $type);
        }

        switch ($sort) {
            default:
                $model = $model->orderBy('created_at', 'desc');
                break;
        }
        
        return $model;
    }
    
    public function filterTopics($type, $input, $sort, $status)
    {
        $model = $this->makeModel();

        if(!is_null($this->hub))
        {
            $topicsList = HubTopic::where([
                'of_type' => Topic::class,
                'hub_id' => $this->hub->id,
            ])->pluck('of_id')->toArray();

            $topicsList = Topic::with('children.children.children','units')->whereIn('id',$topicsList)->get();
            $nodes = getNodes($topicsList);

            // dd($nodes->pluck('id')->toArray());
            
            $model = $model->whereIn('id',$nodes->pluck('id')->toArray());
        }

        $model= $model->with(["children","units"]);

            $model = $model->where(function ($query) use ($input) {
            $query->where('name', 'LIKE', '%' . $input . '%')
                 ->orWhere('code', 'LIKE', '%' . $input . '%');
        });
        if ($type != 'all') {
            $model = $model->where('type', $type);
        }

        if ($status != 'all') {
            $model = $model->where('status', $status);
        }

        switch ($sort) {
            default:
                $model = $model->orderBy('created_at', 'desc');
                break;
        }
        

        return $model;
    }

    public function filter($type, $input, $sort, $status)
    {
        $model = $this->makeModel();



        $model = $model->where(function ($query) use ($input) {
            $query->where('name', 'LIKE', '%' . $input . '%')
                 ->orWhere('code', 'LIKE', '%' . $input . '%');
        });

        if ($type != 'all') {
            $model = $model->where('type', $type);
        }

        if($type == 'course') {
           $model =  Course::where('name', 'LIKE', '%' . $input . '%');
        }

        switch ($sort) {
            default:
                $model = $model->orderBy('created_at', 'desc');
                break;
        }

        return $model;
    }
}
