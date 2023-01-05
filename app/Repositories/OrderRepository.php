<?php

namespace App\Repositories;

use App\Models\Order;
use App\Models\Section as Model;

/**
 * Class CatalogAttributeRepository.
 */
class OrderRepository extends CoreRepository
{

    /**
     * @return string
     *  Return the model
     */
    public function getModelClass()
    {
        return Model::class;
    }

    public function filterListOrder($filter, $order_status_id, $perPage = 10)
    {
        return Order::filter($filter)
            ->where('order_status_id', $order_status_id)
            ->with([
                'section', 'subject', 'work_type', 'customer', 'offers'
            ])
            ->orderByDesc('date_added')
            ->paginate($perPage);
    }

}
