<?php

namespace App\Payments\SigmaNet\Classes;

use Illuminate\Contracts\Support\Arrayable;

class PayoutMethodData implements Arrayable
{

    public function __construct
    (
        public string $type,
        public string $account,
    )
    {

    }

    public function toArray()
    {
        return [
            'type' => $this->type,
            'account' => $this->clearAccount($this->account),
        ];
    }

    /**
     * @param $account
     * @return string
     */
    private function clearAccount($account): string
    {
        return str_replace(' ', '', $account);
    }
}
