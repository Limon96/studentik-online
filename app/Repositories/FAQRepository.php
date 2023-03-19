<?php

namespace App\Repositories;

use App\Models\FAQ as Model;

/**
 * Class CatalogAttributeRepository.
 */
class FAQRepository extends CoreRepository
{
    /**
     * @return string
     *  Return the model
     */
    public function getModelClass()
    {
        return Model::class;
    }

    public function lastUpdated()
    {
        return $this->startConditions()
            ->select(['updated_at'])
            ->orderByDesc('updated_at')
            ->first()
            ->updated_at ?? null;
    }

}
