<?php

namespace App\Http\Controllers\Withdrawal\Admin;

use App\Http\Controllers\Controller;
use App\Models\Withdrawal;
use App\Payments\SigmaNet\Classes\Order;
use App\Payments\SigmaNet\Classes\Payout;
use App\Payments\SigmaNet\Classes\PayoutMethodData;
use App\Payments\SigmaNet\SigmaNet;
use App\Repositories\WithdrawalRepository;

class WithdrawalController extends Controller
{

    public function index()
    {
        $items = app(WithdrawalRepository::class)->all();

        return view('withdrawal.admin.index', compact(
            'items'
        ));
    }

    public function confirm($id)
    {
        $item = Withdrawal::findOrFail($id);

        $result = SigmaNet::payout(new Payout(
            $item->withdrawal_id,
            config('sigma.wallet_id'),
            new PayoutMethodData(
                'card',
                $item->card_number,
            ),
            'payout',
            new Order(
                $item->amount,
                'RUB',
                'Заявка на вывод #' . $item->withdrawal_id
            )
        ));

        return response()->json($result);
    }

    public function cancel($id)
    {
        $item = Withdrawal::findOrFail($id);
        $item->status = 2;
        $item->comment = "Отказ, выплата не выполнена";
        $item->save();

        return response()->json([
            'success' => true,
            'message' => "Отказ, выплата не выполнена"
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy($id)
    {
        Withdrawal::findOrFail($id)->forceDelete();

        return redirect()
            ->route('admin.withdrawal.index')
            ->with(['success' => "Запись [$id] удалена"]);
    }

}
