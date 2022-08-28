<?php

namespace App\Repositories;

use App\Models\Customer as Model;

/**
 * Class CatalogAttributeRepository.
 */
class CustomerRepository extends CoreRepository
{
    /**
     * @return string
     *  Return the model
     */
    public function getModelClass()
    {
        return Model::class;
    }

    public function getTopCustomer($limit = 5)
    {
        $result = $this
            ->startConditions()
            ->select([
                'customer_id',
                'customer_group_id',
                'firstname',
                'login',
                'gender',
                'image',
                'date_added',
                'last_seen',
                'languages',
                'bdate',
                'comment',
                'email',
                'telephone',
                'rating',
                'timezone',
            ])
            ->with(['rating', 'reviews'])
            ->orderByDesc('rating')
            ->limit($limit)
            ->get();

        return $result;
    }

}
