<?php

namespace App\Services\Base;

use Exception;

abstract class BaseService
{
    protected $repositoryClass;
    protected $parserClass;

    public function __construct()
    {
        $this->repositoryClass = app($this->repositoryClass);
    }

    public function getAll()
    {
        return $this->repositoryClass->getAll();
    }

    public function getById(int $id)
    {
        $entity = $this->repositoryClass->getById($id);

        if (!$entity) {
            throw new Exception('Not found!', -404);
        }

        return $entity;
    }


    public function create(array $entityData)
    {
        $test = $entityData;
        if(isset($this->parserClass)) {
            $entityData = (new $this->parserClass())->parseFields($entityData, 'create');
        }

        return (new $this->repositoryClass)->create($entityData);
    }

    public function update(int $id, array $entityData)
    {
        $repository = new $this->repositoryClass;

        $entity = $repository->getById($id);

        if (!$entity) {
            throw new Exception('Not found!', -404);
        }

        if(isset($this->parserClass)) {
            $entityData = (new $this->parserClass())->parseFields($entityData, 'update');
        }

        return $repository->update($entity, $entityData);
    }

    public function delete(int $id)
    {
        $repository = new $this->repositoryClass;
        $entity = $repository->getById($id);

        if (!$entity) {
            throw new Exception('Not found!', -404);
        }

        $repository->delete($entity);

        return [];
    }
}
