<?php

namespace YooKassa;


class PaymentResponse {

    private $array;

    public function __construct(array $array)
    {
        $this->array = $array;
    }

    public function __get($key)
    {
        if (isset($this->array[$key])) {
            return $this->array[$key];
        }

        return null;
    }

    public function __set($key, $value)
    {
        $this->array[$key] = $value;
    }

}
