<?php

namespace App\Repositories;

use App\Models\Customer as Model;
use Illuminate\Support\Facades\DB;

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
                'new_rating' => function ($query) {
                    return $query
                        ->select(DB::raw("SUM(rating) as new_rating"))
                        ->from('customer_rating', 'cr')
                        ->where('cr.date_added', '>', (time() - 604800))
                        ->where('cr.customer_id', DB::raw('customer.customer_id'));
                }
            ])
            ->with(['rating', 'reviews'])
            ->orderByDesc('new_rating')
            ->orderByDesc('rating')
            ->where('customer_group_id', 2)
            ->limit($limit)
            ->get();

        return $result;
    }

}
