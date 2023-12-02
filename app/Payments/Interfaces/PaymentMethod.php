<?php

namespace App\Payments\Interfaces;

use Illuminate\Contracts\Support\Arrayable;

interface PaymentMethod
{

    public function create(Arrayable $payment);

    public function callback();

}
