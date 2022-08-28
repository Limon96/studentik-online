<?php

namespace App\Components\Widgets\TopCustomer;

use App\Components\Widgets\Widget;
use App\Repositories\CustomerRepository;

class TopCustomer implements Widget {

    public static function run()
    {
        $customers = app(CustomerRepository::class)->getTopCustomer(3);

        return view('widgets.top_customer', compact(
            'customers'
        ));
    }

}
