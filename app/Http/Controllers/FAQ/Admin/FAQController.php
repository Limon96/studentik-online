<?php

namespace App\Http\Controllers\FAQ\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\FAQ\StoreRequest;
use App\Http\Requests\Admin\FAQ\UpdateRequest;
use App\Models\FAQ;
use App\Repositories\FAQCategoryRepository;
use Illuminate\Http\Request;

class FAQController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Http\Response
     */
    public function index()
    {
        $items = FAQ::select([
            'id',
            'question',
            'created_at',
            'updated_at',
            'status',
        ])->get();

        return view('faq.admin.index', compact(
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
        $categories = app(FAQCategoryRepository::class)->forSelect();
        $item = FAQ::make();

        return view('faq.admin.form', compact(
            'item',
            'categories'
        ));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(StoreRequest $request)
    {
        $data = $request->validated();

        $item = FAQ::create($data);

        if ($item) {
            return redirect()
                ->route('admin.faq.index')
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
        $item = FAQ::findOrFail($id);

        $categories = app(FAQCategoryRepository::class)->forSelect();

        return view('faq.admin.form', compact(
            'item',
            'categories'
        ));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(UpdateRequest $request, $id)
    {
        $item = FAQ::findOrFail($id);

        $data = $request->validated();

        $item->update($data);

        return redirect()
            ->route('admin.faq.index')
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
        $item = FAQ::findOrFail($id);

        $item->forceDelete();

        return redirect()
            ->route('admin.faq.index')
            ->with(['success' => "Запись [$id] удалена"]);
    }
}
