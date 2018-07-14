<?php

namespace Aham\Repositories;

use Bosnadev\Repositories\Eloquent\Repository;

class LearnerRepository extends Repository
{
    public function model()
    {
        return 'Aham\Models\SQL\Student';
    }

    public function filter($q, $sort, $filters)
    {
        $model = $this->makeModel();

        $model =
        $model
            ->with('user','hubs')
            ->where(function ($query) use ($q) {
                $query
                ->where('code', 'LIKE', '%'.$q.'%')
                ->orWhereHas('user', function ($query) use ($q) {
                    $query->where(function ($query) use ($q) {
                        $query->where('name', 'LIKE', '%'.$q.'%')
                            ->orWhere('email', 'LIKE', '%'.$q.'%');
                    });
                });
            });

        if(isset($filters['preferred_location']))
        {
            $model->whereHas('hubs',function($query) use ($filters){
                $query->where('id',$filters['preferred_location']);
            });
        }

        switch ($sort) {
            default:
                $model = $model->orderBy('created_at', 'desc');
                break;
        }

        return $model;
    }
}
