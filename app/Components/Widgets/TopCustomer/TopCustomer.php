<?php

namespace App\Components\Widgets\TopCustomer;

use App\Models\Customer;
use Illuminate\Support\Facades\DB;

class TopCustomer {

    public static function run()
    {
        $customers = Customer
            ::select([
                'customer_id',
                'customer_group_id',
                'firstname',
                'login',
                'gender',
                'image',
                'date_added',
                'last_seen',
                'languages',
                'bdate',
                'comment',
                'email',
                'telephone',
                'rating',
                'timezone',
            ])
            ->with(['rating'])
            ->orderByDesc('rating')
            ->limit(3)
            ->get();

        return view('widgets.top_customer', compact(
            'customers'
        ));
    }



}
