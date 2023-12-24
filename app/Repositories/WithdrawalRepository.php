<?php

namespace App\Repositories;

use App\Models\Withdrawal as Model;

/**
 * Class CatalogAttributeRepository.
 */
class WithdrawalRepository extends CoreRepository
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
            ->orderBy('date_added', 'desc')
            ->get();
    }

    public function paginate(int $limit = 20)
    {
        return $this
            ->startConditions()
            ->orderBy('date_added', 'desc')
            ->paginate($limit);
    }

}
