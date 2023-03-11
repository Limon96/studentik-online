<?php

namespace App\Http\Controllers\Order;

use App\Http\Controllers\Controller as BaseController;
use Illuminate\Support\Facades\Auth;

class Controller extends BaseController
{

    protected function customer()
    {
        return Auth::user()->customer;
    }

}
