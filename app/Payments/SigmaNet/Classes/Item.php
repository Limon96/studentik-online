<?php

namespace App\Payments\SigmaNet\Classes;

use Illuminate\Contracts\Support\Arrayable;

class Item implements Arrayable
{

    public function __construct
    (
        public string $name,
        public float $price,
        public int $quantity = 1
    )
    {

    }

    public function toArray()
    {
        return [
            'name' => $this->name,
            'price' => $this->price,
            'quantity' => $this->quantity,
        ];
    }
}
