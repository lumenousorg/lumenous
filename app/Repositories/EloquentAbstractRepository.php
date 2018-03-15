<?php

namespace lumenous\Repositories;

use lumenous\Repositories\Interfaces\RepositoryInterface;

abstract class EloquentAbstractRepository implements RepositoryInterface {

    protected $modelClass;

    function all()
    {
        return call_user_func([$this->modelClass, 'all']);
    }

    function count()
    {
        return call_user_func([$this->modelClass, 'count']);
    }

    function getById($id)
    {
        return call_user_func_array([$this->modelClass, 'find'], [$id]);
    }

    function firstOrNew(array $fields)
    {
        return call_user_func_array([$this->modelClass, 'firstOrNew'], [$fields]);
    }

    /**
     * Create a new entity, using either given array of values
     * or without.
     *
     * @param array $fields
     * @return mixed
     */
    function create(array $fields = null)
    {
        return (is_array($fields)) ? call_user_func_array([$this->modelClass, 'create'], [$fields]) : new $this->modelClass();
    }

    /**
     * Update an entity.
     *
     * @param Integer $id
     * @param array $fields
     * @return mixed
     */
    function update($id, array $fields = [])
    {
        $model = $this->getById($id);
        $model->update($fields);
        return $model;
    }

    function save($obj)
    {
        $obj->save();
    }

    function delete($obj)
    {
        $obj->delete();
    }

    /**
     * Find a record according to a specific field.
     * 
     * @param mixed $value
     * @param String $field
     * @return mixed
     */
    public function findBy($value, $field = 'id')
    {
        return call_user_func_array([$this->modelClass, 'where'], [$field, $value])->first();
    }

    /**
     * Find all records according to a specific field.
     * 
     * @param mixed $value
     * @param String $field
     * @return mixed
     */
    public function findAllBy($value, $field = 'id')
    {
        return call_user_func_array([$this->modelClass, 'where'], [$field, $value])->get();
    }

    /**
     * Update a record if found by a specific field,else create a new one.
     * 
     * @param array $data
     * @param String $field
     * @return mixed
     */
    public function updateOrCreateByField($data, $field = 'id')
    {
        $model = $this->findBy($data[$field], $field);

        if (!$model) {
            return $this->create($data);
        }

        return $model->update($data);
    }

    /**
     * Delete all model entries.
     * 
     * @return void
     */
    public function truncate()
    {
        return call_user_func_array([$this->modelClass, 'truncate'], []);
    }

}
