<?php

namespace App\Http\Controllers\Admin;

use App\Filters\Admin\UserFilter;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\AdminStoreRequest;
use App\Http\Requests\Admin\AdminUpdateRequest;
use App\Models\User;
use Spatie\Permission\Models\Role;

class UserController extends Controller
{

    /**
     * @var string
     */
    protected string $route = 'admin.user';
    /**
     * @var string
     */
    protected string $modelClass = User::class;

    public function index(UserFilter $filter)
    {
        $paginate = $this->modelClass
            ::filter($filter)
            ->orderBy('id')
            ->paginate(20);

        return view($this->route . '.list', compact(
            'paginate'
        ));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function create()
    {
        $item = $this->modelClass
            ::make();

        return view($this->route . '.form', compact(
            'item',
        ));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param AdminStoreRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(AdminStoreRequest $request)
    {
        $data = $request->validated();

        $item = $this->modelClass
            ::create($data);

        if ($item) {
            return redirect()
                ->route($this->route . '.edit', $item->id)
                ->with(['success' => 'Успешно сохранено']);
        } else {
            return back()
                ->withErrors(['msg' => "Ошибка сохранения"])
                ->withInput();
        }
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function show($id)
    {
        $item = $this->modelClass
            ::find($id);

        return view($this->route . '.form', compact(
            'item',
        ));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function edit($id)
    {
        $item = $this->modelClass
            ::find($id);

        $roles = Role::all()
            ->map(function ($role) {
                return [
                    'name' => $role->name,
                    'value' => $role->name,
                ];
            })
            ->toArray();

        return view($this->route . '.form', compact(
            'item',
            'roles',
        ));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param AdminUpdateRequest $request
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(AdminUpdateRequest $request, $id)
    {
        $data = $request->validated();

        $item = $this->modelClass
            ::findOrFail($id);

        $item->update($data);

        $item->roles()->detach();

        foreach ($data['roles'] as $roleName) {
            $item->roles()->attach(
                Role::where('name', $roleName)->where('guard_name', 'web')->first()
            );
        }

        if ($item) {
            return redirect()
                ->route($this->route . '.edit', $item->id)
                ->with(['success' => 'Успешно сохранено']);
        } else {
            return back()
                ->withErrors(['msg' => "Ошибка сохранения"])
                ->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy($id)
    {
        $result = $this->modelClass
            ::findOrFail($id)
            ->forceDelete();

        if ($result) {
            return redirect()
                ->route($this->route . '.index')
                ->with(['success' => "Запись id[{$id}] удалена"]);
        } else {
            return back()
                ->withErrors(['msg' => "Ошибка удаления"]);
        }
    }
}
