<?php

namespace lumenous\Repositories\Interfaces;

interface RepositoryInterface {

    function all();

    function count();

    function getById($id);

    function firstOrNew(array $fields);

    /**
     * Create a new entity, using either given array of values
     * or without.
     *
     * @param array $fields
     * @return mixed
     */
    function create(array $fields = null);

    /**
     * Update an entity.
     *
     * @param Integer $id
     * @param array $fields
     * @return mixed
     */
    function update($id, array $fields = []);

    function save($obj);

    function delete($obj);

    /**
     * Find a record according to a specific field.
     * 
     * @param mixed $value
     * @param String $field
     * @return mixed
     */
    public function findBy($value, $field = 'id');

    /**
     * Find all records according to a specific field.
     * 
     * @param mixed $value
     * @param String $field
     * @return mixed
     */
    public function findAllBy($value, $field = 'id');

    /**
     * Update a record if found by a specific field,else create a new one.
     * 
     * @param array $data
     * @param String $field
     * @return mixed
     */
    public function updateOrCreateByField($data, $field = 'id');

    /**
     * Delete all model entries.
     * 
     * @return void
     */
    public function truncate();
}
