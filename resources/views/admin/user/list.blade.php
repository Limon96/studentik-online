@extends('layouts.admin')
@section('title')
    Администраторы
@endsection
@section('styles')
@endsection
@section('scripts')

@endsection
@section('content')
    <div class="sh-breadcrumb">
        <nav class="breadcrumb">
            <a class="breadcrumb-item" href="{{ route('admin.dashboard') }}">Панель управления</a>
            <span class="breadcrumb-item active">@yield('title')</span>
        </nav>
    </div><!-- sh-breadcrumb -->


    <div class="sh-pagetitle">
        <div class="input-group" style="width: fit-content;">
            <form action="{{ route('admin.user.index') }}" class="input-group">
                <input type="search" name="search" class="form-control" placeholder="Поиск">
                    <span class="input-group-btn">
                    <button class="btn"><i class="fa fa-search"></i></button>
                </span>
            </form>
            <a class="btn btn-primary ml-4" href="{{ route('admin.user.create') }}" title="Создать">
                <i class="fa fa-plus"></i>
            </a>
        </div><!-- input-group -->
        <div class="sh-pagetitle-left">
            <div class="sh-pagetitle-icon"><i class="icon ion-ios-paper-outline"></i></div>
            <div class="sh-pagetitle-title">
                <span>Список всех записей</span>
                <h2>@yield('title')</h2>
            </div><!-- sh-pagetitle-left-title -->
        </div><!-- sh-pagetitle-left -->
    </div><!-- sh-pagetitle -->

    <div class="sh-pagebody bg-white">
        <div class="table-responsive">

            <table class="table table-hover table-bordered mg-b-0 dataTable">
                <thead class="bg-primary">
                <tr>
                    <th>#</th>
                    <th>ФИО</th>
                    <th>Email</th>
                    <th>Роль</th>
                    <th>Дата создания</th>
                    <th class="wd-10p">Действие</th>
                </tr>
                </thead>
                <tbody>
                @foreach($paginate as $item)
                    <tr>
                        <td>{{ $item->id }}</td>
                        <td>{{ $item->name }}</td>
                        <td>{{ $item->email }}</td>
                        <td>@foreach($item->roles->pluck('name')->toArray() as $role){{ __('roles.' . $role) }}@if(!$loop->last), @endif @endforeach</td>
                        <td>{{ $item->created_at }}</td>
                        <td class="btn-group-sm">
                            <a href="{{ route('admin.user.edit', $item->id) }}"
                               class="btn btn-sm btn-success" title="Редактировать"><i class="fa fa-pencil"></i></a>
                            <button type="submit" class="btn btn-sm btn-danger" form="form-delete-{{ $item->id }}"
                                    onclick="return confirm('Действительно хотите удалить запись?');"
                                    title="Удалить"><i class="fa fa-trash"></i></button>
                            <form id="form-delete-{{ $item->id }}"
                                  action="{{ route('admin.user.destroy', $item->id) }}" method="POST">
                                @method('DELETE')
                                @csrf
                            </form>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
        @if(isset($paginate) && $paginate->total() > $paginate->count())
            {{ $paginate->withQueryString()->links('list.pagination', ['paginator' => $paginate]) }}
        @endif
    </div>
@endsection
