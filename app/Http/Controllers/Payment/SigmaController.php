<?php

namespace App\Http\Controllers\Payment;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use App\Payments\SigmaNet\Classes\Item;
use App\Payments\SigmaNet\Classes\Order;
use App\Payments\SigmaNet\Classes\Payment;
use App\Payments\SigmaNet\Classes\Receipt;
use App\Payments\SigmaNet\Classes\Settings;
use App\Payments\SigmaNet\SigmaNet;
use App\Services\SetCustomerBalance;
use App\Services\SetCustomerBalanceTransaction;
use Illuminate\Http\Request;

class SigmaController extends Controller
{

    public function create(Request $request)
    {
        $payment_id = $request->get('pid');
        $customer_id = $request->get('cid');

        $payment = \App\Models\Payment::find($payment_id);
        $customer = \App\Models\Customer::find($customer_id);

        $telephone = $request->get('telephone', $customer->telephone);

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
                route('payment.sigma.fail', ['pid' => $payment_id, 'cid' => $customer_id]),
                $this->getWalletId($payment->payment_method),
                config('sigma.is_test')
            ),
            new Receipt(
                [
                    new Item(
                        "Пополнение счета #{$payment->payment_id}",
                        $payment->amount
                    ),
                ],
                $customer->email,
                $telephone,
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
        return redirect(
            url('/index.php?route=account/finance/success')
        );
    }

    public function fail(Request $request)
    {
        return redirect(
            url('/index.php?route=account/finance/canceled')
        );
    }

    public function pending(Request $request)
    {
        return redirect(
            url('/index.php?route=account/finance/pending')
        );
    }

    public function callback(Request $request)
    {
        file_put_contents('sdas.log', file_get_contents('sdas.log') . "\n\r================\n\r" . print_r($request->all(), true));

        $token = $request->get('token');
        $status = $request->get('status');

        $payment = \App\Models\Payment::where('platform_payment_id', $token)->first();

        if ($payment->payment_status_id == 1) {
            $payment->update([
                'payment_status_id' => $this->getPaymentStatus($status)
            ]);

            if ($status == 'successful') {
                $amount = $this->getAmountWithoutPercent($payment->payment_method, $payment->amount);

                (new SetCustomerBalance($payment->customer, $amount))->handle();
                (new SetCustomerBalanceTransaction($payment->customer, $amount))->handle();
            }
        }

        return response()->json([
            'status' => 'ok'
        ]);
    }

    /**
     * @param $payment_method
     * @param $amount
     * @return float
     */
    private function getAmountWithoutPercent($payment_method, $amount): float
    {
        return $amount;
        /*return match($payment_method) {
            "sbp" => $amount * 0.985,
            "card" => $amount * 0.97,
        };*/
    }

    private function getWalletId($payment_method)
    {
        return match ($payment_method) {
            "card" => config('sigma.payout_wallet_id'),
            default => Setting::get('wallet_id', config('sigma.wallet_id')),
        };
    }

    private function getPaymentStatus($status)
    {
        return match ($status) {
            "process" => 1,
            "successful" => 2,
            "canceled" => 3,
            default => 4,
        };
    }

}
