<?php

namespace App\Http\Controllers\Order\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\WorkType\StoreRequest;
use App\Http\Requests\Admin\WorkType\UpdateRequest;
use App\Models\WorkType;
use Illuminate\Http\Request;

class WorkTypeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $items = WorkType::all();

        return view('order.work_type.admin.index', compact(
            'items'
        ));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $item = WorkType::make();

        return view('order.work_type.admin.form', compact(
            'item'
        ));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  StoreRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreRequest $request)
    {
        $data = $request->validated();

        $item = WorkType::create($data);

        if ($item) {
            return redirect()
                ->route('admin.work_type.index')
                ->with(['success' => "Запись [{$item->work_type_id}] успешно сохранена"]);
        } else {
            return back()
                ->withErrors(['msg' => "Ошибка сохранения"])
                ->withInput();
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $item = WorkType::findOrFail($id);

        return view('order.work_type.admin.form', compact(
            'item'
        ));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  UpdateRequest  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateRequest $request, $id)
    {
        $item = WorkType::findOrFail($id);

        $data = $request->validated();

        $item->update($data);

        return redirect()
            ->route('admin.work_type.index')
            ->with(['success' => "Запись [$id] успешно сохранена"]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $item = WorkType::findOrFail($id);

        $item->forceDelete();

        return redirect()
            ->route('admin.work_type.index')
            ->with(['success' => "Запись [$id] удалена"]);
    }
}
