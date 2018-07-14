<?php

namespace Aham\TDGateways\Implementations;

use Illuminate\Database\Eloquent\Model;

abstract class AbstractGateway
{
    /**
     * The model to execute queries on.
     *
     * @var \Illuminate\Database\Eloquent\Model
     */
    protected $model;

    /**
     * Create a new repository instance.
     *
     * @param \Illuminate\Database\Eloquent\Model $model The model to execute queries on
     */
    public function __construct(Model $model)
    {
        $this->model = $model;
    }

    /**
     * Get a new instance of the model.
     *
     * @param array $attributes
     *
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function getNew(array $attributes = array())
    {
        return $this->model->newInstance($attributes);
    }

    /**
     * Return all.
     *
     * @return Illuminate\Database\Eloquent\Collection
     */
    public function all(array $with = array(), array $select = array())
    {
        $query = $this->make($with);

        if (count($select) > 0) {
            # code...
            return $query->select($select)->get();
        }

        return $query->get();
    }

    /**
     * Make a new instance of the entity to query on.
     *
     * @param array $with
     */
    public function make(array $with = array())
    {
        return $this->model->with($with);
    }

    /**
     * Find an entity by id.
     *
     * @param int   $id
     * @param array $with
     *
     * @return Illuminate\Database\Eloquent\Model
     */
    public function getById($id, array $with = array())
    {
        $query = $this->make($with);

        return $query->find($id);
    }

    /**
     * Find a single entity by key value.
     *
     * @param string $key
     * @param string $value
     * @param array  $with
     */
    public function getFirstBy($key, $value, array $with = array())
    {
        return $this->make($with)->where($key, '=', $value)->first();
    }

    /**
     * Find a single entity by key value.
     *
     * @param string $key
     * @param string $value
     * @param array  $with
     */
    public function getFirstByKeys(array $keys = array(), array $with = array())
    {
        $model = $this->make($with);

        foreach ($keys as $key => $value) {
            $model = $model->where($key, '=', $value);
        }

        return $model->first();
    }

    /**
     * Find many entities by key value.
     *
     * @param string $key
     * @param string $value
     * @param array  $with
     */
    public function getManyBy($key, $value, array $with = array())
    {
        $this->make($with)->where($key, '=', $value)->get();
    }

    /**
     * Get Results by Page.
     *
     * @param int   $page
     * @param int   $limit
     * @param array $with
     *
     * @return StdClass Object with $items and $totalItems for pagination
     */
    public function getByPage($page = 1, $limit = 10, $with = array())
    {
        $result = new \StdClass();
        $result->page = $page;
        $result->limit = $limit;
        $result->totalItems = 0;
        $result->items = array();

        $query = $this->make($with);

        $model = $query->skip($limit * ($page - 1))
                     ->take($limit)
                     ->get();

        $result->totalItems = $this->model->count();
        $result->items = $model->all();

        return $result;
    }

    /**
     * Return all results that have a required relationship.
     *
     * @param string $relation
     */
    public function has($relation, array $with = array())
    {
        $entity = $this->make($with);

        return $entity->has($relation)->get();
    }

    /**
     * undocumented function.
     *
     * @author
     **/
    public function destroy($id)
    {
        return $this->model->destroy($id);
    }

    /**
     * undocumented function.
     *
     * @author
     **/
    public function create($resource)
    {
        return $this->model->create($resource);
    }

    /**
     * undocumented function.
     *
     * @author
     **/
    public function update($resource)
    {
        return $this->model->update($resource);
    }

    /**
     * [getModelByPage description].
     *
     * @param [type] $model [description]
     * @param int    $page  [description]
     * @param int    $limit [description]
     *
     * @return [type] [description]
     */
    public function getModelByPage($model, $page = 1, $limit = 10)
    {
        $result = new \StdClass();
        $result->page = $page;
        $result->limit = $limit;
        $result->totalItems = $model->count();
        $result->items = array();

        $model = $model->skip($limit * ($page - 1))
                         ->take($limit)
                         ->get();

        $result->items = $model->all();

        return $result;
    }
}
