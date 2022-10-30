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

    public function forMailing(array $filter_data)
    {
        $query = $this->startConditions()
            ->select(['customer_id AS id', 'customer_id', 'email', 'firstname', 'login'])
            ->where('setting_email_notify', 1)
            ->orderBy('date_added');

        $emails = [];
        if (isset($filter_data['email']) && $filter_data['email']) {
            $emails = explode(',', $filter_data['email']);

            $emails = array_map(function ($email) {
                return trim($email);
            }, $emails);
        }

        if ($emails) {
            $query->whereIn('email', $emails);
        } else {

            if (isset($filter_data['filter_only_admin']) && $filter_data['filter_only_admin'] == 1) {
                $query->where('is_admin', 1);
            }

            if (isset($filter_data['lack_activity']) && $filter_data['lack_activity'] > -1) {
                $days = (int)$filter_data['lack_activity'];

                $query
                    ->where('last_seen_at', '>', now()->subDays($days)->setTime(0, 0, 0)->toDateTimeString())
                    ->where('last_seen_at', '<', now()->subDays($days - 1)->setTime(0, 0, 0)->toDateTimeString());
            }

            if (isset($filter_data['filter_customer_group']) && in_array($filter_data['filter_customer_group'], [1,2])) {
                $query->where('customer_group_id', '=', (int)$filter_data['filter_customer_group']);
            }
        }

        return $query->get();
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
