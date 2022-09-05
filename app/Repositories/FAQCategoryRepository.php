<?php

namespace App\Repositories;

use App\Models\FAQCategory as Model;

/**
 * Class CatalogAttributeRepository.
 */
class FAQCategoryRepository extends CoreRepository
{
    /**
     * @return string
     *  Return the model
     */
    public function getModelClass()
    {
        return Model::class;
    }

    public function all()
    {
        return $this
            ->startConditions()
            ->select([
                'id',
                'title',
            ])
            ->with(['questions' => function ($query) {
                return $query->orderBy('sort_order');
            }])
            ->orderBy('title', 'asc')
            ->get();
    }

    public function forSelect()
    {
        return $this
            ->startConditions()
            ->select([
                'id',
                'title',
            ])
            ->orderBy('title', 'asc')
            ->get();
    }

}
