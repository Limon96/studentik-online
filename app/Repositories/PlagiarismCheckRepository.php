<?php

namespace App\Repositories;

use App\Models\PlagiarismCheck as Model;

/**
 * Class CatalogAttributeRepository.
 */
class PlagiarismCheckRepository extends CoreRepository
{
    /**
     * @return string
     *  Return the model
     */
    public function getModelClass()
    {
        return Model::class;
    }

    public function getForSelect()
    {
        return $this->startConditions()
            ->orderBy('name')
            ->get();
    }

}
