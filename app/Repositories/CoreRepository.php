<?php

namespace App\Repositories;

abstract class CoreRepository
{

    protected $model;
    protected $limit;

    public function __construct()
    {
        $this->model = app($this->getModelClass());
        $this->limit = 20;
    }

    abstract protected function getModelClass();

    protected function startConditions()
    {
        return clone $this->model;
    }

    public function limit($limit = 10): CoreRepository
    {
        $this->limit = $limit;

        return $this;
    }

}
