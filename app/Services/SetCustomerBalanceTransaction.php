<?php

namespace App\Services;

use App\Models\Customer;
use App\Models\CustomerTransaction;

class SetCustomerBalanceTransaction
{

    public function __construct(
        private readonly Customer  $customer,
        private readonly float|int $amount,
        private readonly string $description = ''
    )
    {

    }

    public function handle()
    {
        CustomerTransaction::create([
            'customer_id' => $this->customer->customer_id,
            'description' => $this->getDescription(),
            'amount' => $this->amount,
            'balance' => $this->customer->balance,
            'date_added' => time(),
        ]);
    }

    private function getDescription()
    {
        if ($this->description) {
            return $this->description;
        }

        if ($this->amount >= 0) {
            return 'Пополнение счета';
        }

        return 'Списание со счета';
    }

}
