<?php

namespace App\Http\Controllers\Landing\Admin;

use App\Components\PageBuilder\Library\PageBuilderComponent;
use App\Components\PageBuilder\PageBuilder;
use App\Http\Controllers\Controller;
use App\Models\Article;
use App\Models\Landing;
use App\Repositories\LandingRepository;
use Illuminate\Http\Request;

class LandingController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Http\Response
     */
    public function index()
    {
        $items = app(LandingRepository::class)->all();

        return view('landing.admin.index', compact('items'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Http\Response
     */
    public function create()
    {
        $item = Landing::make();

        $pageBuilder = (new PageBuilder())
            ->run();

        return view('landing.admin.form',
            compact(
                'pageBuilder',
                'item'
            )
        );
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        $data = $request->all();

        $item = Landing::create($data);

        if ($item) {
            return response()->json([
                'redirect' => route('admin.landing.index'),
                'success' => 'Успешно сохранено'
            ]);
        } else {
            return response()->json([
                'error' => "Ошибка сохранения"
            ]);
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
        $item = Landing::find($id);

        $pageBuilder = (new PageBuilder())
            ->components(
                json_decode($item->components, true)
            )
            ->run();

        return view('landing.admin.form',
            compact(
                'pageBuilder',
                'item'
            )
        );
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse|\Illuminate\Http\Response
     */
    public function copy($id)
    {
        $item = Landing::find($id);

        $copy_item = Landing::create([
            'title' => $item->title . ' копия',
            'meta_title' => $item->meta_title,
            'meta_description' => $item->meta_description,
            'meta_keywords' => $item->meta_keywords,
            'components' => json_decode($item->components),
            'type_page' => $item->type_page,
            'slug' => $item->slug . '_copy',
            'status' => 0,
        ]);

        if ($copy_item) {
            return redirect()
                ->route('admin.landing.index')
                ->with(['success' => "Создана копия <a href='" . route('admin.landing.edit', $copy_item->id) ."'>id[{$copy_item->id}]</a> записи"]);
        } else {
            return back()
                ->withErrors(['msg' => "Ошибка копирования"]);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, $id)
    {
        $item = Landing::find($id);

        if (empty($item)) {
            return response()->json([
                'error' => "Запись id=[{$id}] не найдена"
            ]);
        }

        $data = $request->all();

        $result = $item->update($data);

        if ($item) {
            return response()->json([
                'redirect' => route('admin.landing.index'),
                'success' => 'Успешно сохранено'
            ]);
        } else {
            return response()->json([
                'error' => "Ошибка сохранения"
            ]);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy($id): \Illuminate\Http\RedirectResponse
    {
        $result = Landing::find($id)->forceDelete();

        if ($result) {
            return redirect()
                ->route('admin.landing.index')
                ->with(['success' => "Запись id[{$id}] удалена"]);
        } else {
            return back()
                ->withErrors(['msg' => "Ошибка удаления"]);
        }
    }
}
