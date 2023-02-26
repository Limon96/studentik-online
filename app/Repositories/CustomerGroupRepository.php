<?php

namespace App\Repositories;

use App\Models\CustomerGroup as Model;
use Illuminate\Support\Facades\DB;

/**
 * Class CatalogAttributeRepository.
 */
class CustomerGroupRepository extends CoreRepository
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
        return $this->startConditions()
            ->leftJoin('customer_group_description', 'customer_group.customer_group_id', '=', 'customer_group_description.customer_group_id')
            ->where('customer_group_description.language_id', '1')
            ->orderBy('customer_group.sort_order')
            ->select([
                'customer_group.customer_group_id',
                'customer_group.approval',
                'customer_group.sort_order',
                'customer_group_description.name',
                'customer_group_description.description'
            ])
            ->get();
    }

}
