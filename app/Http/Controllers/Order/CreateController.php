<?php

namespace App\Http\Controllers\Order;

use App\Http\Controllers\Controller;
use App\Http\Requests\Order\CreateRequest;
use App\Models\Order;
use App\Repositories\PlagiarismCheckRepository;
use App\Repositories\SectionRepository;
use App\Repositories\WorkTypeRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CreateController extends Controller
{
    private const PRICE_PREMIUM_ORDER = 100;
    private const PRICE_HOT_ORDER = 100;

    public function showForm()
    {
        $sections = app(SectionRepository::class)->getForSelect();
        $work_types = app(WorkTypeRepository::class)->getForSelect();
        $plagiarism_checks = app(PlagiarismCheckRepository::class)->getForSelect();

        return view('order.form', compact(
            'sections',
            'work_types',
            'plagiarism_checks'
        ));
    }

    public function create(CreateRequest $request)
    {
        $amount = $this->getAmountCreatingOrder($request);

        if ($this->isNoMoneyOnCustomerBalance($amount)) {
            return $this->responseWithPay($amount);
        }

        if ($amount > 0) {
            $this->customer()->deductBalanceTransfer($amount);
        }

        $order = Order::create($request->validated());

        return $this->responceSuccess($order->getSeoUrl());
    }

    private function getAmountCreatingOrder($request)
    {
        $amount = 0;

        if ($request->premium)
            $amount += static::PRICE_PREMIUM_ORDER;

        if ($request->hot)
            $amount += static::PRICE_HOT_ORDER;

        return $amount;
    }

    private function isNoMoneyOnCustomerBalance($amount)
    {
        return $this->customer()->balance < $amount;
    }

    private function responseWithPay($amount)
    {
        return response()->json([
            'errors' => [
                'balance' => 'Нехватает средств на счету'
            ],
            'redirect' => url('index.php?route=account/finance/payment&amount=' . $amount)
        ]);
    }

    private function responceSuccess(string $slug)
    {
        return response()->json([
            'success' => 1,
            'redirect' => route('order.show', $slug)
        ]);
    }

    private function customer()
    {
        return Auth::user()->customer;
    }

}
