<?php

namespace App\Http\Controllers\Setting\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Setting\UpdateRequest;
use App\Models\Setting;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

class SettingController extends Controller
{

    /**
     * @return Application|Factory|View
     */
    public function index(): View|Factory|Application
    {
        $items = Setting::all();

        $wallet_id = $items->where('key', 'wallet_id')->first()->value ?? null;

        return view('setting.admin.setting', compact(
            'wallet_id',
        ));
    }

    public function update(UpdateRequest $request)
    {
        Setting::set('wallet_id', $request->get('wallet_id'));

        return back()->with(['success' => 'Успешно сохранено']);
    }

}
