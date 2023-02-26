<?php

namespace App\Repositories;

use App\Models\Order as Model;
use App\Models\SeoUrl;

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
        return $this->startConditions()->filter($filter)
            ->where('order_status_id', $order_status_id)
            ->with([
                'section', 'subject', 'work_type', 'customer', 'offers'
            ])
            ->orderByDesc('date_added')
            ->paginate($perPage);
    }

    public function findSlug(string $slug)
    {
        $seoUrl = SeoUrl::where('keyword', $slug)->first();

        if (is_null($seoUrl)) {
            return null;
        }

        $query = explode('=', $seoUrl->query);

        $orderId = (int)$query[1];

        return $this
            ->startConditions()
            ->with([
                'section',
                'subject',
                'work_type',
                'customer',
                'plagiarism_check',
                'attachments',
                'offerAttachments',
                'offers' => function ($query) {
                    return $query->orderByDesc('created_at');
                },
            ])
            ->where('order_id', $orderId)
            ->first();
    }

}
