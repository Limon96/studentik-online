<?php

namespace App\Http\Controllers\Order;

use App\Models\Order;

class OrderController extends Controller
{

    public function toVerification(int $order_id)
    {
        $order = Order::find($order_id);
        if ($order->isOwner()) {
            $verification_id = setting('config_verification_order_status_id');
            $order->setStatus($verification_id);
            $order->setHistory(
                $this->customer(),
                __('order.history.customer_verification')
            );
        }

        return $this->responceSuccess();
    }

    private function responceSuccess()
    {
        return response()->json([
            'success' => 1
        ]);
    }
}
