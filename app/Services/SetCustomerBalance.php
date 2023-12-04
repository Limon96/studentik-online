<?php

namespace App\Services;

use App\Models\Customer;
use App\Models\Payment;

class SetCustomerBalance
{

    public function __construct(
        private readonly Customer  $customer,
        private readonly float|int $amount,
    )
    {

    }

    public function handle()
    {
        $this->customer->increment('balance', $this->amount);
    }


}
