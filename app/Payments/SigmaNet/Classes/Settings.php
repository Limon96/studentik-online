<?php

namespace App\Payments\SigmaNet\Classes;

use Illuminate\Contracts\Support\Arrayable;

class Settings implements Arrayable
{

    public function __construct
    (
        public string $project_id,
        public string $payment_method,
        public string $success_url = '',
        public string $fail_url = '',
        public string $back_url = '',
        public string $wallet_id = '',
        public bool $is_test = false,
        public bool $hide_form_methods = true
    )
    {

    }

    public function toArray()
    {
        return [
            'project_id' => $this->project_id,
            'payment_method' => $this->payment_method,
            'success_url' => $this->success_url,
            'fail_url' => $this->fail_url,
            'back_url' => $this->back_url,
            'wallet_id' => $this->wallet_id,
            'is_test' => $this->is_test,
            'hide_form_methods' => $this->hide_form_methods,
        ];
    }
}
