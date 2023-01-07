<?php

namespace App\Currencies\Currencies;

abstract class AbstractCurrency
{

    protected string $leftSymbol = '';
    protected string $rightSymbol = ' р.';

    public function __construct(
        protected string|float|int $number = 0.000
    )
    { }

    public function format()
    {
        return $this->leftSymbol . $this->number . $this->rightSymbol;
    }

}
