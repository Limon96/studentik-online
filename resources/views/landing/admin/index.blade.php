@extends('layouts.admin')

@section('title') Страницы - @endsection

@section('content')

    <div class="sh-breadcrumb">
        <nav class="breadcrumb">
            <a class="breadcrumb-item" href="{{ route('admin.dashboard') }}">{{ __('Панель управления') }}</a>
            <span class="breadcrumb-item active">{{ __('Страницы') }}</span>
        </nav>
    </div><!-- sh-breadcrumb -->

    <div class="sh-pagetitle">
        <div>
            <a href="{{ route('admin.landing.create') }}" class="btn btn-primary float-right">Создать новую</a>
        </div><!-- input-group -->
        <div class="sh-pagetitle-left">
            <div class="sh-pagetitle-icon"><i class="icon ion-ios-home"></i></div>
            <div class="sh-pagetitle-title">
                <span>Список существующих страниц</span>
                <h2>Страницы</h2>
            </div><!-- sh-pagetitle-left-title -->
        </div><!-- sh-pagetitle-left -->
    </div><!-- sh-pagetitle -->

    <div class="sh-pagebody">
        <div class="row row-sm">
            <div class="col-md-12">
                <div class="table-wrapper">
                    <table id="datatable" class="table display responsive nowrap">
                        <thead>
                        <tr>
                            <th class="wd-20p">Название</th>
                            <th class="wd-5p">Статус</th>
                            <th class="wd-10p">Дата создания</th>
                            <th class="wd-10p">Дата обновления</th>
                            <th class="wd-10p">Действие</th>
                        </tr>
                        </thead>
                        <tbody>
                            @foreach($items as $item)
                                <tr>
                                    <td>
                                        <a href="{{ route('admin.landing.index', ['parent_id' => $item->id]) }}">{{ $item->title }}</a>
                                    </td>
                                    <td>@if($item->status) Опубликован @else Черновик @endif</td>
                                    <td>{{ $item->created_at }}</td>
                                    <td>{{ $item->updated_at }}</td>
                                    <td>
                                        <a href="{{ route('landing.show', $item->slug) }}" class="btn btn-sm btn-info" target="_blank"><i class="fa fa-eye"></i></a>
                                        <a href="{{ route('admin.landing.copy', $item->id) }}" class="btn btn-sm btn-primary"><i class="fa fa-copy"></i></a>
                                        <a href="{{ route('admin.landing.edit', $item->id) }}" class="btn btn-sm btn-primary"><i class="fa fa-pencil"></i></a>
                                        <form method="post" class="form-delete"
                                              action="{{ route('admin.landing.destroy', $item->id) }}">
                                            @method('DELETE')
                                            @csrf
                                            <button type="submit"
                                                    class="btn btn-sm btn-danger btn-delete"><i class="fa fa-trash"></i></button>
                                        </form>
                                    </td>
                                </tr>
                                @if($item->id === $parent_id)
                                    @foreach($children as $child)
                                        <tr>
                                            <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;{{ $child->title }}</td>
                                            <td>@if($child->status) Опубликован @else Черновик @endif</td>
                                            <td>{{ $child->created_at }}</td>
                                            <td>{{ $child->updated_at }}</td>
                                            <td>
                                                <a href="{{ route('landing.show', $child->slug) }}" class="btn btn-sm btn-info" target="_blank"><i class="fa fa-eye"></i></a>
                                                <a href="{{ route('admin.landing.copy', $child->id) }}" class="btn btn-sm btn-primary"><i class="fa fa-copy"></i></a>
                                                <a href="{{ route('admin.landing.edit', $child->id) }}" class="btn btn-sm btn-primary"><i class="fa fa-pencil"></i></a>
                                                <form method="post" class="form-delete"
                                                      action="{{ route('admin.landing.destroy', $child->id) }}">
                                                    @method('DELETE')
                                                    @csrf
                                                    <button type="submit"
                                                            class="btn btn-sm btn-danger btn-delete"><i class="fa fa-trash"></i></button>
                                                </form>
                                            </td>
                                        </tr>
                                    @endforeach
                                @endif
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div><!-- row -->
    </div><!-- sh-pagebody -->
@endsection

@section('scripts')
    <script src="{{ asset('manager/lib/datatables/jquery.dataTables.js') }}"></script>
    <script src="{{ asset('manager/lib/select2/js/select2.min.js') }}"></script>
    <script>
        /*$('#datatable').DataTable({
            responsive: true,
            language: {
                searchPlaceholder: 'Поиск...',
                sSearch: '',
                lengthMenu: '_MENU_ записей на странице',
                paginate: {
                    first:      "Первая",
                    last:       "Последняя",
                    next:       "Вперед",
                    previous:   "Назад"
                },
            },
            paging: false,
        });*/

        // Select2
        $('.dataTables_length select').select2({ minimumResultsForSearch: Infinity });
    </script>
@endsection
