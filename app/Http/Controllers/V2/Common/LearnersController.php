<?php

namespace Aham\Http\Controllers\V2\Common;

use Aham\Http\Controllers\Controller;
use Aham\Repositories\LearnerRepository;
use Aham\CreditsEngine\Sync;

use Aham\Models\SQL\Location;

use League\Fractal;
use League\Fractal\Manager;
use League\Fractal\Serializer\ArraySerializer;
use League\Fractal\Pagination\IlluminatePaginatorAdapter;
use Aham\TransformersV2\LearnerTransformer;

class LearnersController extends BaseController
{
    private $learner;

    public function __construct(LearnerRepository $learner)
    {
        $this->learner = $learner;
    }

    public function show($id)
    {
        $learner = $this->learner->find($id);
        $user = $learner->user;

        return $this->response->item($learner, new LearnerTransformer);
    }

    public function index()
    {
        $filters = [];

        if(request()->get('preferred_location') != '')
        {
          $filters['preferred_location'] = request()->get('preferred_location', '');
        }
        
        $model = $this->learner->filter(
            request()->get('input', ''),
            request()->get('sort', 'created_at_desc'),
            $filters
        );

        $paginator = $model->paginate(20);

        $topics = $paginator->getCollection();

        $resource = new Fractal\Resource\Collection($topics, new LearnerTransformer);
        $resource->setPaginator(new IlluminatePaginatorAdapter($paginator));

        $manager = new Manager();
        $manager->setSerializer(new ArraySerializer());

        return $manager->createData($resource)->toArray();
    }

}
