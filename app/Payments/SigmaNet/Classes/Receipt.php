<?php

namespace App\Payments\SigmaNet\Classes;

use Illuminate\Contracts\Support\Arrayable;

class Receipt implements Arrayable
{

    public function __construct
    (
        public array $items,
        public string $email,
        public string $phone
    )
    {

    }

    public function toArray()
    {
        return [
            'items' => array_map(function ($item) {
                return $item->toArray();
            }, $this->items),
            'email' => $this->email,
            'phone' => $this->phone,
        ];
    }
}
