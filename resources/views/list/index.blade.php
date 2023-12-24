@extends('admin_panel::layouts.admin')
@section('title')
    {{ $title }}
@endsection
@section('styles')
@endsection
@section('scripts')

@endsection
@section('content')
    <div class="sh-breadcrumb">
        <nav class="breadcrumb">
            <a class="breadcrumb-item" href="{{ route('admin.dashboard.index') }}">Панель управления</a>
            <span class="breadcrumb-item active">{{ $title }}</span>
        </nav>
    </div><!-- sh-breadcrumb -->


    <div class="sh-pagetitle">
        <div class="input-group" style="width: fit-content;">
            <a class="btn btn-primary" href="{{ route('admin.' . $prefix . '.create') }}" title="Создать"><i
                        class="fa fa-plus"></i></a>
        </div><!-- input-group -->
        <div class="sh-pagetitle-left">
            <div class="sh-pagetitle-icon"><i class="icon ion-ios-paper-outline"></i></div>
            <div class="sh-pagetitle-title">
                <span>Список всех записей</span>
                <h2>{{ $title }}</h2>
            </div><!-- sh-pagetitle-left-title -->
        </div><!-- sh-pagetitle-left -->
    </div><!-- sh-pagetitle -->

    <div class="sh-pagebody bg-white">
        <div class="table-responsive">

            <table class="table table-hover table-bordered mg-b-0 dataTable">
                <thead class="bg-primary">
                <tr>
                    @foreach($columns as $key => $name)
                        <th>{{ $name }}</th>
                    @endforeach
                    <th class="wd-10p">Действие</th>
                </tr>
                </thead>
                <tbody>
                @foreach($paginate as $item)
                    <tr>
                        @foreach($columns as $key => $name)
                            <td>
                                @php
                                    $key = explode('.', $key);
                                    $value = $item;
                                    foreach($key as $k) $value = $value->$k ?? '';
                                @endphp

                                @if($key[0] == 'is_published')
                                    {{ ($value ? 'Опубликовано': 'Черновик') }}
                                @else
                                    {{ \Illuminate\Support\Str::limit($value, 50) }}
                                @endif
                            </td>
                        @endforeach
                        <td class="btn-group-sm">
                            @if($canView)
                                <a href="{{ route('admin.' . $prefix . '.view', $item->id) }}"
                                   class="btn btn-sm btn-info" title="Просмотр"><i class="fa fa-eye"></i></a>
                            @endif
                            @if($canUpdate)
                                <a href="{{ route('admin.' . $prefix . '.edit', $item->id) }}"
                                   class="btn btn-sm btn-success" title="Редактировать"><i class="fa fa-pencil"></i></a>
                            @endif
                            @if($canDelete)
                                <button type="submit" class="btn btn-sm btn-danger" form="form-delete-{{ $item->id }}"
                                        onclick="return confirm('Действительно хотите удалить запись?');"
                                        title="Удалить"><i class="fa fa-trash"></i></button>
                                <form id="form-delete-{{ $item->id }}"
                                      action="{{ route('admin.' . $prefix . '.delete', $item->id) }}" method="POST">
                                    @method('DELETE')
                                    @csrf
                                </form>
                            @endif
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
        @if(isset($paginate) && $paginate->total() > $paginate->count())
            {{ $paginate->withQueryString()->links('admin_panel::list.pagination', ['paginator' => $paginate]) }}
        @endif
    </div>
@endsection
