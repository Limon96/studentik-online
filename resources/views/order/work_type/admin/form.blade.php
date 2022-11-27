@extends('layouts.admin')
@section('title')
    {{ __($item->work_type_id ? 'Редактирование типа работы' : 'Новый тип работы') }}
@endsection
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

    <script>
        var loadFile = function(event) {
            var src = URL.createObjectURL(event.target.files[0])

            if (!$('#preview img').length) {
                $('#preview').prepend('<img class="img-thumbnail">');
            }

            $('#preview img').attr('src', src);

            $('#preview img').onload = function() {
                URL.revokeObjectURL(src) // free memory
            }
        };
    </script>

@endsection
@section('content')
    <div class="sh-breadcrumb">
        <nav class="breadcrumb">
            <a class="breadcrumb-item" href="{{ route('admin.dashboard') }}">{{ __('Панель управления') }}</a>
            <a class="breadcrumb-item" href="{{ route('admin.work_type.index') }}">{{ __('Типы работ') }}</a>
            <span class="breadcrumb-item active">@yield('title')</span>
        </nav>
    </div><!-- sh-breadcrumb -->

    <form action="{{ $item->work_type_id ? route('admin.work_type.update', $item->work_type_id) : route('admin.work_type.store') }}" method="POST"
          enctype="multipart/form-data">
        @if($item->work_type_id)
            @method('PATCH')
        @endif
        @csrf
        <div class="sh-pagetitle">
            <div class="input-group" style="width: fit-content;">
                <button type="submit" class="btn btn-primary">
                    <i class="fa fa-save mr-2"></i>Сохранить</button>
                <!-- input-group-btn -->
            </div><!-- input-group -->
            <div class="sh-pagetitle-left">
                <div class="sh-pagetitle-icon"><i class="icon ion-ios-briefcase-outline"></i></div>
                <div class="sh-pagetitle-title">
                    <span>Список всех типов работ</span>
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

                    <div class="card">
                        @error('name')<div class="alert alert-danger">{{ $message }}</div>@enderror
                        @error('sort_order')<div class="alert alert-danger">{{ $message }}</div>@enderror

                        <div class="card-header bd-b">Основные</div>
                        <!-- card-header -->
                        <div class="card-body color-gray-lighter">
                            <div id="tab-general" class="tab-card active">
                                <input type="hidden" name="language_id" value="1">

                                <div class="form-group">
                                    <label class="form-control-label" for="input-name">Название</label>
                                    <input class="form-control"
                                           id="input-name" type="text"
                                           name="name"
                                           value="{{ old('name', $item->name ?? '') }}">
                                </div>
                                <div class="form-group">
                                    <label class="form-control-label" for="input-sort_order">Сортировка</label>
                                    <input class="form-control"
                                           id="input-sort_order" type="text"
                                           name="sort_order"
                                           value="{{ old('sort_order', $item->sort_order ?? '') }}">
                                </div>
                            </div>
                        </div><!-- card-body -->

                    </div>
                </div>

            </div>
        </div>

    </form>
@endsection
