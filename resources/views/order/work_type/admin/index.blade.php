@extends('layouts.admin')

@section('title') Типы работ - @endsection

@section('content')

    <div class="sh-breadcrumb">
        <nav class="breadcrumb">
            <a class="breadcrumb-item" href="{{ route('admin.dashboard') }}">{{ __('Панель управления') }}</a>
            <span class="breadcrumb-item active">{{ __('Типы работ') }}</span>
        </nav>
    </div><!-- sh-breadcrumb -->

    <div class="sh-pagetitle">
        <div>
            <a href="{{ route('admin.work_type.create') }}" class="btn btn-primary float-right">Создать новый</a>
        </div><!-- input-group -->
        <div class="sh-pagetitle-left">
            <div class="sh-pagetitle-icon"><i class="icon ion-ios-briefcase-outline"></i></div>
            <div class="sh-pagetitle-title">
                <span>Список типов работ</span>
                <h2>Типы работ</h2>
            </div><!-- sh-pagetitle-left-title -->
        </div><!-- sh-pagetitle-left -->
    </div><!-- sh-pagetitle -->

    <div class="sh-pagebody">
        @if(session('success'))
            <div class="row justify-content-center">
                <div class="col-md-12">
                    <div class="alert alert-success" role="alert">
                        <button class="close" type="button" data-dismiss="alert"
                                aria-label="Close">x
                        </button>
                        {{ session()->get('success') }}
                    </div>
                </div>
            </div>
        @endif
        <div class="row row-sm">
            <div class="col-md-12">

                <div class="table-wrapper">
                    <table id="datatable" class="table display responsive nowrap">
                        <thead>
                        <tr>
                            <th class="wd-5p">#</th>
                            <th class="wd-25p">Название</th>
                            <th class="wd-5p">Сортировка</th>
                            <th class="wd-5p">Действие</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($items as $item)
                            <tr>
                                <td>{{ $item->work_type_id }}</td>
                                <td>{{ $item->name }}</td>
                                <td>{{ $item->sort_order }}</td>
                                <td>
                                    <a href="{{ route('admin.work_type.edit', $item->work_type_id) }}" title="Редактировать" class="btn btn-sm btn-primary"><i class="fa fa-pencil"></i></a>
                                    <form method="post" class="form-delete"
                                          action="{{ route('admin.work_type.destroy', $item->work_type_id) }}">
                                        @method('DELETE')
                                        @csrf
                                        <button type="submit"
                                                class="btn btn-sm btn-danger btn-delete point_c"
                                                title="Удалить статью"
                                                onclick="return confirm('Действительно хотите удалить запись?');"><i class="fa fa-trash"></i></button>
                                    </form>
                                </td>
                            </tr>
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
        $('#datatable').DataTable({
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
            }
        });

        // Select2
        $('.dataTables_length select').select2({ minimumResultsForSearch: Infinity });
    </script>
@endsection
