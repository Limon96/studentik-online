@extends('layouts.admin')
@section('title') Рассылка @endsection
@section('meta_desc')
    {{ config('app.name', 'Laravel') }}
@endsection
@section('styles')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/KaTeX/0.7.1/katex.min.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/highlight.js/9.12.0/styles/monokai-sublime.min.css" />

    <!-- Theme included stylesheets -->
    {{--<link href="//cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet">--}}
    {{--<link href="{{ asset('manager/lib/quill/quill.core.css') }}" rel="stylesheet">--}}
    <link href="{{ asset('manager/lib/quill/quill.snow.css') }}" rel="stylesheet">
@endsection
@section('scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/KaTeX/0.7.1/katex.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/highlight.js/9.12.0/highlight.min.js"></script>

    <!-- Main Quill library -->
    <script src="{{ asset('manager/lib/quill/quill.min.js') }}"></script>
    <script>
        var container = document.getElementById('editor-text');

        var options = {
            modules: {
                formula: true,
                syntax: true,
                toolbar: '#toolbar-container'
            },
            placeholder: 'Начните вводить текст...',
            theme: 'snow'
        };

        var quill = new Quill(container, options);

        quill.on('text-change', function(delta, oldDelta, source) {
            $('#textarea-text').val($('#editor-text .ql-editor').html());
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
                                <label class="form-control-label"
                                       for="textarea-text">Текст</label>

                                <div id="standalone-container">
                                    <div id="toolbar-container">
                                            <span class="ql-formats">
                                              <select class="ql-font"></select>
                                              <select class="ql-size"></select>
                                            </span>
                                        <span class="ql-formats">
                                              <button class="ql-bold"></button>
                                              <button class="ql-italic"></button>
                                              <button class="ql-underline"></button>
                                              <button class="ql-strike"></button>
                                            </span>
                                        <span class="ql-formats">
                                              <select class="ql-color"></select>
                                              <select class="ql-background"></select>
                                            </span>
                                        <span class="ql-formats">
                                              <button class="ql-script" value="sub"></button>
                                              <button class="ql-script" value="super"></button>
                                            </span>
                                        <span class="ql-formats">
                                              <button class="ql-header" value="2"></button>
                                                <button class="ql-header tx-bold" value="3">H<small>3</small></button>
                                              <button class="ql-blockquote"></button>
                                              <button class="ql-code-block"></button>
                                            </span>
                                        <span class="ql-formats">
                                              <button class="ql-list" value="ordered"></button>
                                              <button class="ql-list" value="bullet"></button>
                                              <button class="ql-indent" value="-1"></button>
                                              <button class="ql-indent" value="+1"></button>
                                            </span>
                                        <span class="ql-formats">
                                              <button class="ql-direction" value="rtl"></button>
                                              <select class="ql-align"></select>
                                            </span>
                                        <span class="ql-formats">
                                              <button class="ql-link"></button>
                                              <button class="ql-image"></button>
                                              <button class="ql-video"></button>
                                              <button class="ql-formula"></button>
                                            </span>
                                        <span class="ql-formats">
                                              <button class="ql-clean"></button>
                                            </span>
                                    </div>
                                    <div id="editor-text">{!! old('body', $item->body ?? '') !!}</div>
                                    <textarea class="form-control"
                                              style="display: none"
                                              id="textarea-text"
                                              name="body"
                                              cols="30"
                                              rows="7">{{ old('body', $item->body ?? '') }}</textarea>
                                </div>


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
