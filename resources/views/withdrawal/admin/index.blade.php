@extends('layouts.admin')

@section('title') Заявки на вывод средств - @endsection

@section('content')

    <div class="sh-breadcrumb">
        <nav class="breadcrumb">
            <a class="breadcrumb-item" href="{{ route('admin.dashboard') }}">{{ __('Панель управления') }}</a>
            <span class="breadcrumb-item active">{{ __('Заявки на вывод средств') }}</span>
        </nav>
    </div><!-- sh-breadcrumb -->

    <div class="sh-pagetitle">
        <div>

        </div><!-- input-group -->
        <div class="sh-pagetitle-left">
            <div class="sh-pagetitle-icon"><i class="icon ion-ios-home"></i></div>
            <div class="sh-pagetitle-title">
                <span>Список статей</span>
                <h2>Блог</h2>
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
                            <th class="wd-5p">Клиент</th>
                            <th class="wd-5p">Сумма</th>
                            <th class="wd-5p">Баланс</th>
                            <th class="wd-5p">Способ вывода </th>
                            <th class="wd-5p">Реквизиты</th>
                            <th class="wd-5p">Статус</th>
                            <th class="wd-15p">Комментарий</th>
                            <th class="wd-5p">Дата создания</th>
                            <th class="wd-5p">Дата обновления</th>
                            <th class="wd-5p">Действие</th>
                        </tr>
                        </thead>
                        <tbody>
                            @foreach($items as $item)
                                <tr>
                                    <td>{{ $item->withdrawal_id }}</td>
                                    <td>{{ $item->customer->login }}</td>
                                    <td>{{ $item->amount }}</td>
                                    <td>{{ $item->balance }}</td>
                                    <td>{{ $item->method }}</td>
                                    <td>{{ $item->card_number }}</td>
                                    <td>
                                        <select name="status" id="" class="select2">
                                            @foreach([0 => 'Ожидание', 1 => 'Выполнен', 2 => 'Не выполнен'] as $status => $name)
                                                <option value="{{ $status }}" @if($item->status == $status) selected @endif >{{ $name }}</option>
                                            @endforeach
                                        </select>
                                    </td>
                                    <td><textarea name="comment" cols="30">{{ $item->comment }}</textarea></td>
                                    <td>{{ format_date($item->date_added, 'Y.m.d H:i:s') }}</td>
                                    <td>{{ format_date($item->date_updated, 'Y.m.d H:i:s') }}</td>
                                    <td>
                                        <form method="post" class="form-delete"
                                              action="{{ route('admin.withdrawal.destroy', $item->withdrawal_id) }}">
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
