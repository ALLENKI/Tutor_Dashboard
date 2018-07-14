<?php

namespace Aham\TDGateways\Implementations;

use Aham\TDGateways\LocationManagementGatewayInterface;
use Aham\Models\SQL\Topic;
use Illuminate\Foundation\Bus\DispatchesJobs;


class LocationManagementGateway extends AbstractGateway implements LocationManagementGatewayInterface
{
    use DispatchesJobs;

    public function __construct(Topic $model)
    {
        $this->model = $model;
    }

    public function createTopic($data)
    {
        $this->model->create($data);
    }

    public function updateTopic($id, $data)
    {
    	$topic = $this->model->find($id);

    	$topic->fill($data);

    	$topic->save();
    }
}
