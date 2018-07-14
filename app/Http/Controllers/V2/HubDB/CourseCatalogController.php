<?php

namespace Aham\Http\Controllers\V2\HubDB;

use Aham\Http\Controllers\Controller;
use Aham\Repositories\CourseCatalogRepository;
use League\Fractal;
use League\Fractal\Manager;
use League\Fractal\Serializer\ArraySerializer;
use League\Fractal\Pagination\IlluminatePaginatorAdapter;
use Aham\TransformersV2\CourseCatalogTopicTransformer;
use Aham\TransformersV2\CourseCatalogCourseTransformer;
use Aham\Models\SQL\Topic;

class CourseCatalogController extends BaseController
{
    private $catalog;

    public function __construct(CourseCatalogRepository $catalog)
    {
        $this->catalog = $catalog;
    }

    public function browseCourses($hubSlug)
    {
        $this->catalog->setHub($hubSlug);

        $model = $this->catalog->filterCourses(
            request()->get('type', 'all'),
            request()->get('input', ''),
            request()->get('sort', 'created_at_desc'),
            request()->get('status', 'any')
        );

         $model->get();

        if(request()->has('page'))
        {
            $paginator = $model->paginate(20);

            $topics = $paginator->getCollection();
        }
        else
        {
            $topics = $model->get();

            return $this->response()->collection($topics,new CourseCatalogCourseTransformer);

        }

        $resource = new Fractal\Resource\Collection($topics, new CourseCatalogCourseTransformer);

        $resource->setPaginator(new IlluminatePaginatorAdapter($paginator));

        $manager = new Manager();
        $manager->setSerializer(new ArraySerializer());

        return $manager->createData($resource)->toArray();
    }
    
    public function browseTopics($hubSlug)
    {
        /*
            1. Fetch everything
            2. Fetch only categories
            3. Fetch only subjects
            4. Fetch only sub-categories
            5. Fetch only topics
            6. Sort by latest
            7. Filter by Status
        */

        $this->catalog->setHub($hubSlug);

        $model = $this->catalog->filterTopics(
            request()->get('type', 'all'),
            request()->get('input', ''),
            request()->get('sort', 'created_at_desc'),
            request()->get('status', 'any')
        );

        if(request()->has('page'))
        {
            $paginator = $model->paginate(20);

            $topics = $paginator->getCollection();
        }
        else
        {
            $topics = $model->get();
            return $this->response()->collection($topics,new CourseCatalogTopicTransformer);
        }

        $resource = new Fractal\Resource\Collection($topics, new CourseCatalogTopicTransformer);

        $resource->setPaginator(new IlluminatePaginatorAdapter($paginator));

        $manager = new Manager();
        $manager->setSerializer(new ArraySerializer());

        return $manager->createData($resource)->toArray();
    }

}
