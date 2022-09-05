<?php

namespace App\Http\Controllers\FAQ\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\FAQCategory\StoreRequest;
use App\Http\Requests\Admin\FAQCategory\UpdateRequest;
use App\Models\FAQCategory;
use App\Repositories\FAQCategoryRepository;

class FAQCategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $items = app(FAQCategoryRepository::class)->forSelect();

        return view('faq_category.admin.index', compact(
            'items'
        ));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Http\Response
     */
    public function create()
    {
        $item = FAQCategory::make();

        return view('faq_category.admin.form', compact(
            'item'
        ));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  StoreRequest  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(StoreRequest $request)
    {
        $data = $request->validated();

        $item = FAQCategory::create($data);

        if ($item) {
            return redirect()
                ->route('admin.faq_category.index')
                ->with(['success' => "Запись [{$item->id}] успешно сохранена"]);
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
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Http\Response
     */
    public function edit($id)
    {
        $item = FAQCategory::findOrFail($id);

        return view('faq_category.admin.form', compact(
            'item'
        ));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  UpdateRequest  $request
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(UpdateRequest $request, $id)
    {
        $item = FAQCategory::findOrFail($id);

        $data = $request->validated();

        $item->update($data);

        return redirect()
            ->route('admin.faq_category.index')
            ->with(['success' => "Запись [$id] успешно сохранена"]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy($id)
    {
        $item = FAQCategory::findOrFail($id);

        $item->forceDelete();

        return redirect()
            ->route('admin.faq_category.index')
            ->with(['success' => "Запись [$id] удалена"]);
    }
}
