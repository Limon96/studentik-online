<?php

namespace App\Http\Controllers\Feedback\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Support\Facades\DB;

class PreviewMailController extends Controller
{

    public function index()
    {
        $days = 1;

        $order = Order
            ::where('date_added', '>', now()->subDays($days + 1)->getTimestamp())
            ->where('date_added', '<', now()->subDays($days)->getTimestamp())
            ->where('order_status_id', 1)
            ->where(DB::raw(0), '<', function ($query) {
                return $query->from('offer', 'of')->where(DB::raw('`order`.order_id'), DB::raw('of.order_id'))->select(DB::raw('COUNT(1)'));
            })
            ->with(['customer', 'offers' => function ($query) {
                return $query->with('customer');
            }])
            ->get()->first();

        //dd($order);

        return view('mail.time_choose_offer', ['unsubscribe_token' => '', 'order' => $order]);
    }

}
