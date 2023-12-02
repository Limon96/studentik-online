<?php

namespace App\Payments\SigmaNet;

use App\Payments\Interfaces\PaymentMethod;
use App\Payments\SigmaNet\Classes\Payment;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Support\Facades\Http;

class SigmaNet implements PaymentMethod
{


    public function create(Payment|Arrayable $payment)
    {
        return Http::withHeaders(['Authorization' => 'Bearer studentik.online@mail.ru:26C8838F-B880-4D8C-AD6E-86D7A840067B'])->post('https://api.sigma.net/v1/payment/create', $payment->toArray())->json();
    }

    public function callback()
    {
        // TODO: Implement callback() method.
    }
}
