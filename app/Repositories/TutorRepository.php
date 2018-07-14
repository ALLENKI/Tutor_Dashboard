<?php

namespace Aham\Repositories;

use Bosnadev\Repositories\Eloquent\Repository;

class TutorRepository extends Repository
{
    public function model()
    {
        return 'Aham\Models\SQL\Teacher';
    }

    public function filter($q, $sort, $filters)
    {
        $model = $this->makeModel();

        $model =
        $model
            ->with('user')
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

        switch ($sort) {
            default:
                $model = $model->orderBy('created_at', 'desc');
                break;
        }

        return $model;
    }
}
