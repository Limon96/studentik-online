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

        if ($item->status > 0) {
            return response()->json([
                'error' => true,
                'message' => "Выплата уже произведена или отменена",
            ]);
        }

        $result = SigmaNet::payout(new Payout(
            $item->withdrawal_id. "-" . config('sigma.wallet_id'),
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

        if (in_array($result['status'] , ["init", "process", "successful"])) {
            $item->status = 1;
            $item->comment = 'Заявка на вывод #' . $item->withdrawal_id . ' ' . date("d.m.Y H:i");
            $item->save();

            return response()->json([
                'status' => $item->status,
                'comment' => $item->comment
            ]);
        }

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
