<?php

namespace App\Http\Controllers\Payment;

use App\Http\Controllers\Controller;
use App\Payments\SigmaNet\Classes\Item;
use App\Payments\SigmaNet\Classes\Order;
use App\Payments\SigmaNet\Classes\Payment;
use App\Payments\SigmaNet\Classes\Receipt;
use App\Payments\SigmaNet\Classes\Settings;
use App\Payments\SigmaNet\SigmaNet;
use Illuminate\Http\Request;

class SigmaController extends Controller
{

    public function create(Request $request)
    {
        $payment_id = $request->get('pid');
        $customer_id = $request->get('cid');

        $payment = \App\Models\Payment::find($payment_id);
        $customer = \App\Models\Customer::find($customer_id);

        $sigma = new SigmaNet();
        $response = $sigma->create(new Payment(
            $payment->payment_id,
            new Order(
                $payment->amount,
                $payment->currency,
                "Пополнение счета #{$payment->payment_id}"
            ),
            new Settings(
                config('sigma.project_id'),
                $payment->payment_method,
                route('payment.sigma.success', ['pid' => $payment_id, 'cid' => $customer_id]),
                route('payment.sigma.fail', ['pid' => $payment_id, 'cid' => $customer_id]),
                route('payment.sigma.success', ['pid' => $payment_id, 'cid' => $customer_id]),
                8617,
                true
            ),
            new Receipt(
                [
                    new Item(
                        "Пополнение счета #{$payment->payment_id}",
                        $payment->amount
                    ),
                ],
                $customer->email,
                $customer->telephone,
            ),
        ));

        //dd($response);

        if (isset($response['error'])) {
            return redirect()
                ->route('payment.sigma.fail', ['pid' => $payment_id, 'cid' => $customer_id])
                ->with(['error' => $response['message']]);
        }

        $payment->update([
            'platform_payment_id' => $response['token']
        ]);

        return redirect($response['payment_url']);
    }

    public function success(Request $request)
    {
        $payment_id = $request->get('pid');

        $payment = \App\Models\Payment::find($payment_id);
        $sigma = new SigmaNet();

        $paymentInfo = $sigma->get($payment->platform_payment_id);
        dd($paymentInfo);
        return redirect(
            url('/index.php?route=account/finance/success')
        );
    }


    public function callback(Request $request)
    {
        file_put_contents('sdas.log', file_get_contents('sdas.log') . "\n\r================\n\r" . print_r($request->all(), true));

        return response()->json([
            'status' => 'ok'
        ]);
    }


    public function fail(Request $request)
    {
        file_put_contents('sdas.log', file_get_contents('sdas.log') . "\n\r================\n\r" . print_r($request->all(), true));
        return redirect(
            url('/index.php?route=account/finance/canceled')
        );
    }

}
