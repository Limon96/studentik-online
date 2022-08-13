<?php

namespace App\Repositories;

use App\Models\Section as Model;

/**
 * Class CatalogAttributeRepository.
 */
class SectionRepository extends CoreRepository
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
            ->with('subjects')
            ->orderBy('name')
            ->get();
    }

}
