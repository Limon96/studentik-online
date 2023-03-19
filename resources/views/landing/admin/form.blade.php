@extends('layouts.admin')

@section('title')@if($item->exists) Редактирование @else Создание @endif страницы - @endsection

@section('content')

    <div class="sh-breadcrumb">
        <nav class="breadcrumb">
            <a class="breadcrumb-item" href="{{ route('admin.dashboard') }}">{{ __('Панель управления') }}</a>
            <a class="breadcrumb-item" href="{{ route('admin.landing.index') }}">{{ __('Страницы') }}</a>
            <span class="breadcrumb-item active">{{ __('Новая страница') }}</span>
        </nav>
    </div><!-- sh-breadcrumb -->

    <div class="sh-pagetitle">
        <div>
            <button id="save-landing" class="btn btn-primary">Опубликовать</button>
            <a href="{{ route('admin.landing.index') }}" class="btn btn-primary">Назад</a>
        </div><!-- input-group -->
        <div class="sh-pagetitle-left">
            <div class="sh-pagetitle-icon"><i class="icon ion-ios-home"></i></div>
            <div class="sh-pagetitle-title">
                <span>Форма редактирования страницы</span>
                <h2>Новая страница</h2>
            </div><!-- sh-pagetitle-left-title -->
        </div><!-- sh-pagetitle-left -->
    </div><!-- sh-pagetitle -->

    <div class="sh-pagebody">
        <div class="row row-sm">
            <div class="col-md-8">
                @csrf
                {!! $pageBuilder !!}
            </div>
            <div class="col-md-4">
                <div id="page-form" class="alert alert-primary bd bd-primary pd-25 mg-b-20">
                    <div class="form-group">
                        <label for="input-title">Заголовок</label>
                        <input id="input-title" type="text" name="title" class="form-control" value="{{ old('title', $item->title) }}">
                    </div>
                    <div class="form-group">
                        <label for="input-menu_title">Название в меню</label>
                        <input id="input-menu_title" type="text" name="menu_title" class="form-control" value="{{ old('menu_title', $item->menu_title) }}">
                    </div>
                    <div class="form-group">
                        <label for="input-meta_title">Meta title</label>
                        <input id="input-meta_title" type="text" name="meta_title" class="form-control" value="{{ old('meta_title', $item->meta_title) }}">
                    </div>
                    <div class="form-group">
                        <label for="input-meta_description">Meta description</label>
                        <input id="input-meta_description" type="text" name="meta_description" class="form-control" value="{{ old('meta_description', $item->meta_description) }}">
                    </div>
                    <div class="form-group">
                        <label for="input-meta_keywords">Meta keywords</label>
                        <input id="input-meta_keywords" type="text" name="meta_keywords" class="form-control" value="{{ old('meta_keywords', $item->meta_keywords) }}">
                    </div>
                    <div class="form-group">
                        <label for="input-slug">SEO URL</label>
                        <input id="input-slug" type="text" name="slug" class="form-control" value="{{ old('slug', $item->slug) }}">
                    </div>
                    <div class="form-group">
                        <label class="form-label" for="select-type_page">Тип страницы</label>
                        <select class="form-control" type="text" id="select-type_page" name="type_page">
                            <option value="1" @if($item->type_page == 1) selected @endif>Тип работы</option>
                            <option value="2" @if($item->type_page == 2) selected @endif>Предмет</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="form-label" for="select-status">Статус</label>
                        <select class="form-control" type="text" id="select-status" name="status">
                            @if(old('status', $item->status) == 1)
                                <option value="1" selected>Включено</option>
                                <option value="0">Отключено</option>
                            @else
                                <option value="1">Включено</option>
                                <option value="0" selected>Отключено</option>
                            @endif
                        </select>
                    </div>
                </div>
                <div class="tree">
                 <p></p>
                </div>
            </div>
        </div><!-- row -->
    </div><!-- sh-pagebody -->
@endsection

@section('scripts')

    <link href="{{ asset('manager/lib/summernote/summernote.css') }}" rel="stylesheet">
    <link href="{{ asset('manager/lib/summernote/summernote-bs4.css') }}" rel="stylesheet">
    <script src="{{ asset('manager/lib/summernote/summernote.min.js') }}"></script>
    <script src="{{ asset('manager/lib/summernote/summernote-bs4.min.js') }}"></script>
    <script src="{{ asset('manager/js/pagebuilder.js') }}"></script>
    <script>

        initPageBuilder();

        @if($item->exists)
        $(document).on('click', '#save-landing', function () {
            page_builder_submit(
                '{{ route('admin.landing.update', $item->id) }}',
                'PUT'
            );
        });
        @else
        $(document).on('click', '#save-landing', function () {
            page_builder_submit(
                '{{ route('admin.landing.store') }}',
                'POST'
            );
        });
        @endif
    </script>
@endsection
