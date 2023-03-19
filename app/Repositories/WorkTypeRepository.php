<?php

namespace App\Repositories;

use App\Models\WorkType as Model;

/**
 * Class CatalogAttributeRepository.
 */
class WorkTypeRepository extends CoreRepository
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
            ->select(['work_type_id', 'name'])
            ->orderBy('sort_order')
            ->get();
    }

}
