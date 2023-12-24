<?php

namespace App\Http\Controllers\Withdrawal\Admin;

use App\Http\Controllers\Controller;
use App\Models\Withdrawal;
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
