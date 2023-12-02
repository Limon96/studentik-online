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
        return Http
            ::withHeaders([
                'Authorization' => 'Bearer ' . config('sigma.email') . ':' . config('sigma.api_key')
            ])
            ->post(
                'https://api.sigma.net/v1/payment/create',
                $payment->toArray()
            )
            ->json();
    }

    public function callback()
    {
        // TODO: Implement callback() method.
    }

}
