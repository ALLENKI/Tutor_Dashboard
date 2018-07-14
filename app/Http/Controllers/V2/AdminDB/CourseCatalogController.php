<?php

namespace Aham\Http\Controllers\V2\AdminDB;

use Aham\Http\Controllers\Controller;
use Aham\Repositories\CourseCatalogRepository;
use Aham\Repositories\CourseRepository;
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
    private $course;

    public function __construct(CourseCatalogRepository $catalog, CourseRepository $course)
    {
        $this->catalog = $catalog;
        $this->course = $course;
    }

    public function browseTopics()
    {
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

    public function browseCourses()
    {
        $model = $this->catalog->filterCourses(
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

            return $this->response()->collection($topics,new CourseCatalogCourseTransformer);

        }

        $resource = new Fractal\Resource\Collection($topics, new CourseCatalogCourseTransformer);

        $resource->setPaginator(new IlluminatePaginatorAdapter($paginator));

        $manager = new Manager();
        $manager->setSerializer(new ArraySerializer());

        return $manager->createData($resource)->toArray();
    }

    public function browse()
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

        $model = $this->catalog->filter(
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

            if( request()->get('type', 'all') == 'course' ) {
                return $this->response()->collection($topics,new CourseCatalogCourseTransformer);
            } else {
                 return $this->response()->collection($topics,new CourseCatalogTopicTransformer);
            }

        }

        if( request()->get('type', 'all') == 'course' ) {
            $resource = new Fractal\Resource\Collection($topics, new CourseCatalogCourseTransformer);
        } else {
             $resource = new Fractal\Resource\Collection($topics, new CourseCatalogTopicTransformer);
        }

        $resource->setPaginator(new IlluminatePaginatorAdapter($paginator));

        $manager = new Manager();
        $manager->setSerializer(new ArraySerializer());

        return $manager->createData($resource)->toArray();
    }

    public function getACatalogPath($id)
    {
        $topic =  Topic::find($id);

        return $tree = Topic::where('parent_id',$topic->id)->paginate(10);
    }

}
