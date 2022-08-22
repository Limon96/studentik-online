<?php

namespace App\Http\Controllers\Blog\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\BlogPost\StoreRequest;
use App\Http\Requests\Admin\BlogPost\UpdateRequest;
use App\Models\BlogCategory;
use App\Models\BlogPost;
use App\Repositories\BlogCategoryRepository;
use Illuminate\Http\Request;

class BlogController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $items = BlogPost::select([
            'id',
            'title',
            'created_at',
            'updated_at',
            'publish_at',
            'status',
            'slug',
        ])->get();

        return view('blog.admin.index', compact(
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
        $categories = app(BlogCategoryRepository::class)->forSelect();
        $item = BlogPost::make();

        return view('blog.admin.form', compact(
            'item',
            'categories'
        ));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  StoreRequest  $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\Response
     */
    public function store(StoreRequest $request)
    {
        $data = $request->validated();

        $item = BlogPost::create($data);

        if ($item) {
            return redirect()
                ->route('admin.blog.index')
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
        $item = BlogPost::findOrFail($id);

        $categories = app(BlogCategoryRepository::class)->forSelect();

        return view('blog.admin.form', compact(
            'item',
            'categories'
        ));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  UpdateRequest $request
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(UpdateRequest $request, $id)
    {
        $item = BlogPost::findOrFail($id);

        $data = $request->validated();

        $item->update($data);

        return redirect()
            ->route('admin.blog.index')
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
        $item = BlogPost::findOrFail($id);

        $item->forceDelete();

        return redirect()
            ->route('admin.blog.index')
            ->with(['success' => "Запись [$id] удалена"]);
    }
}
