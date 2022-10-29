@extends('layouts.admin')
@section('title') Рассылка @endsection
@section('meta_desc')
    {{ config('app.name', 'Laravel') }}
@endsection
@section('styles')

@endsection
@section('scripts')
    <link href="{{ asset('admin/lib/summernote/summernote.css') }}" rel="stylesheet">
    <link href="{{ asset('admin/lib/summernote/summernote-bs4.css') }}" rel="stylesheet">
    <style>
        .note-editable {
            min-height: 200px;
            max-height: 600px;
        }
    </style>
    <script src="{{ asset('admin/lib/summernote/summernote.min.js') }}"></script>
    <script src="{{ asset('admin/lib/summernote/summernote-bs4.min.js') }}"></script>
    <script>
        $(document).ready(function (){
            $('.summernote').summernote();
        });
    </script>
@endsection
@section('content')
    <div class="sh-breadcrumb">
        <nav class="breadcrumb">
            <a class="breadcrumb-item" href="{{ route('admin.dashboard') }}">Панель управления</a>
            <span class="breadcrumb-item active">@yield('title')</span>
        </nav>
    </div><!-- sh-breadcrumb -->

    <form action="{{ route('admin.newsletter.send') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="sh-pagetitle">
            <div class="input-group" style="width: fit-content;">
                <!-- input-group-btn -->
            </div><!-- input-group -->
            <div class="sh-pagetitle-left">
                <div class="sh-pagetitle-icon"><i class="icon ion-ios-email-outline"></i></div>
                <div class="sh-pagetitle-title">
                    <span>Список всех заявок</span>
                    <h2>@yield('title')</h2>
                </div><!-- sh-pagetitle-left-title -->
            </div><!-- sh-pagetitle-left -->
        </div><!-- sh-pagetitle -->

        <div class="sh-pagebody">
            <div class="row row-sm mt-3 mb-3">
                <div class="col-lg-12">
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
                    @error('msg')
                    <div class="row justify-content-center">
                        <div class="col-md-12">
                            <div class="alert alert-danger" role="alert">
                                <button class="close" type="button" data-dismiss="alert"
                                        aria-label="Close">x
                                </button>
                                {{ $message }}
                            </div>
                        </div>
                    </div>
                    @endif
                    @error('lack_activity')
                    <div class="row justify-content-center">
                        <div class="col-md-12">
                            <div class="alert alert-danger" role="alert">
                                <button class="close" type="button" data-dismiss="alert"
                                        aria-label="Close">x
                                </button>
                                {{ $message }}
                            </div>
                        </div>
                    </div>
                    @enderror
                    @error('subject')
                    <div class="row justify-content-center">
                        <div class="col-md-12">
                            <div class="alert alert-danger" role="alert">
                                <button class="close" type="button" data-dismiss="alert"
                                        aria-label="Close">x
                                </button>
                                {{ $message }}
                            </div>
                        </div>
                    </div>
                    @enderror

                    <div class="card bd">
                        <div class="card-body color-gray-lighter">
                            <h5>Фильтр по получателям</h5>
                            <div class="form-group">
                                <label for="input-email" class="form-control-label">Email <small>(Не учитывает фильтры ниже)</small></label>
                                <input id="input-email" class="form-control" name="email" value="{{ old('email', '') }}">
                            </div>
                            <div class="form-group">
                                <label for="input-lack_activity" class="form-control-label">Был в сети дней назад</label>
                                <input id="input-lack_activity" type="number" min="0" max="365" class="form-control" name="lack_activity" value="{{ old('lack_activity', '') }}">
                            </div>

                            <div class="form-group">
                                <label for="input-filter_customer_group" class="form-control-label">Группа пользователей</label>
                                <select id="input-filter_customer_group"class="form-control" name="filter_customer_group">
                                    <option value="">Всем</option>
                                    <option value="1">Заказчики</option>
                                    <option value="2">Авторы</option>
                                </select>
                            </div>

                            <h5>Письмо</h5>
                            <div class="form-group">
                                <label for="input-topic" class="form-control-label">Тема</label>
                                <input id="input-topic" class="form-control" name="subject" value="{{ old('subject', '') }}">
                            </div>
                            <div class="form-group">
                                <label for="select-template" class="form-control-label">Готовый шаблон</label>
                                <select id="select-template" name="template" class="form-control">
                                    <option value="0">-- Не использовать --</option>
                                    @foreach($templates as $item)
                                        <option value="{{ $item['value'] }}">{{ $item['name'] }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="textarea-text" class="form-control-label">Сообщение</label>
                                <textarea id="textarea-text" class="form-control summernote" name="body" rows="10">{{ old('body', '') }}</textarea>
                            </div>
                            <div class="form-group">
                                <button class="btn btn-primary">Отправить</button>
                            </div>
                        </div><!-- card-body -->
                    </div>
                </div>
            </div>
        </div>
    </form>
@endsection
