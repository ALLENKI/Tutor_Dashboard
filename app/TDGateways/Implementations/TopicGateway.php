<?php

namespace Aham\TDGateways\Implementations;

use Aham\TDGateways\TopicGatewayInterface;
use Aham\Models\SQL\Topic;
use Illuminate\Foundation\Bus\DispatchesJobs;


class TopicGateway extends AbstractGateway implements TopicGatewayInterface
{
    use DispatchesJobs;

    public function __construct(Topic $model)
    {
        $this->model = $model;
    }

    public function createTopic($data)
    {
        return $this->model->create($data);
    }

    public function updateTopic($id, $data)
    {

    	$topic = $this->model->find($id);

    	$topic->fill($data);

    	$topic->save();
    }
}
