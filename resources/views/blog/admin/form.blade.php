@extends('layouts.admin')
@section('title')
    {{ __($item->id ? 'Редактирование статьи' : 'Новая статья') }}
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
            <a class="breadcrumb-item" href="{{ route('admin.blog.index') }}">{{ __('Блог') }}</a>
            <span class="breadcrumb-item active">@yield('title')</span>
        </nav>
    </div><!-- sh-breadcrumb -->

    <form action="{{ $item->id ? route('admin.blog.update', $item->id) : route('admin.blog.store') }}" method="POST"
          enctype="multipart/form-data">
        @if($item->id)
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
                <div class="sh-pagetitle-icon"><i class="icon ion-ios-bookmarks-outline"></i></div>
                <div class="sh-pagetitle-title">
                    <span>Список всех статьи</span>
                    <h2>@yield('title')</h2>
                </div><!-- sh-pagetitle-left-title -->
            </div><!-- sh-pagetitle-left -->
        </div><!-- sh-pagetitle -->

        <div class="sh-pagebody">
            <div class="row row-sm mt-3 mb-3">
                <div class="col-lg-9">
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
                        @error('title')<div class="alert alert-danger">{{ $message }}</div>@enderror
                        @error('intro')<div class="alert alert-danger">{{ $message }}</div>@enderror
                        @error('text')<div class="alert alert-danger">{{ $message }}</div>@enderror
                        @error('meta_title')<div class="alert alert-danger">{{ $message }}</div>@enderror
                        @error('meta_desc')<div class="alert alert-danger">{{ $message }}</div>@enderror
                        @error('meta_keywords')<div class="alert alert-danger">{{ $message }}</div>@enderror
                        @error('blog_category_id')<div class="alert alert-danger">{{ $message }}</div>@enderror
                        @error('tags')<div class="alert alert-danger">{{ $message }}</div>@enderror
                        @error('slug')<div class="alert alert-danger">{{ $message }}</div>@enderror
                        @error('image')<div class="alert alert-danger">{{ $message }}</div>@enderror
                        @error('publish_at')<div class="alert alert-danger">{{ $message }}</div>@enderror

                        <div class="card-header bd-b">Основные</div>
                        <!-- card-header -->
                        <div class="card-body color-gray-lighter">
                            <div id="tab-general" class="tab-card active">
                                <div class="form-group">
                                    <label class="form-control-label" for="input-title">Заголовок</label>
                                    <input class="form-control"
                                           id="input-title" type="text"
                                           name="title"
                                           value="{{ old('title', $item->title ?? '') }}">
                                </div>

                                <div class="form-group">
                                    <label class="form-control-label"
                                           for="textarea-intro">Введение</label>
                                    <textarea class="form-control"
                                              id="textarea-intro"
                                              name="intro"
                                              cols="30"
                                              rows="7">{{ old('intro', $item->intro ?? '') }}</textarea>
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
                                        <div id="editor-text">{!! old('text', $item->text ?? '') !!}</div>
                                        <textarea class="form-control"
                                                  style="display: none"
                                                  id="textarea-text"
                                                  name="text"
                                                  cols="30"
                                                  rows="7">{{ old('text', $item->text ?? '') }}</textarea>
                                    </div>


                                </div>

                                <div class="form-group">
                                    <label class="form-control-label"
                                           for="input-meta-title">Meta Title</label>
                                    <input class="form-control"
                                           id="input-meta-title" type="text"
                                           name="meta_title"
                                           value="{{ old('meta_title', $item->meta_title ?? '') }}">
                                </div>

                                <div class="form-group">
                                    <label class="form-control-label"
                                           for="textarea-meta_description">Meta Description</label>
                                    <textarea class="form-control"
                                              id="textarea-meta_description"
                                              name="meta_description"
                                              cols="30"
                                              rows="3">{{ old('meta_description', $item->meta_description ?? '') }}</textarea>
                                </div>

                                <div class="form-group">
                                    <label class="form-control-label"
                                           for="textarea-meta_keywords">Meta Keywords</label>
                                    <textarea class="form-control"
                                              id="textarea-meta_keywords"
                                              name="meta_keywords"
                                              cols="30"
                                              rows="3">{{ old('meta_keywords', $item->meta_keywords ?? '') }}</textarea>
                                </div>

                                <div class="form-group">
                                    <label class="form-control-label"
                                           for="textarea-tags">Теги</label>
                                    <textarea class="form-control"
                                              id="textarea-tags"
                                              name="tags"
                                              cols="30"
                                              rows="3">{{ old('tags', $item->tags ?? '') }}</textarea>
                                </div>
                                <div class="form-group">
                                    <label for="input-slug" class="form-control-label">SEO URL</label>
                                    <input id="input-slug" class="form-control select2" name="slug" value="{{ old('slug', $item->slug) }}">
                                </div>
                            </div>
                        </div><!-- card-body -->

                    </div>
                </div>

                <div class="col-lg-3">
                    @include('components.admin.preview_image')

                    <div class="card bd mt-4">
                        <div class="card-header bd-b">Дополнительно</div>
                        <div class="card-body">
                            <div class="form-group">
                                <label for="select-category" class="form-control-label">Категория</label>
                                <select id="select-category" class="form-control select2" name="blog_category_id">
                                    @foreach($categories as $category)
                                        @include('components.admin.select_option_categories', [
                                            'option' => $category,
                                            'selected_id' => old('blog_category_id', $item->blog_category_id)
                                        ])
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="select-status" class="form-control-label">Статус</label>
                                <select id="select-status" class="form-control select2" name="status">
                                    <option value="0" @if(old('status', $item->status) == 0) selected @endif>Черновик</option>
                                    <option value="1" @if(old('status', $item->status) == 1) selected @endif>Опубликовано</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="input-publish_at" class="form-control-label">Дата публикации</label>
                                <input id="input-publish_at" class="form-control" type="datetime-local" name="publish_at" value="{{ old('publish_at', $item->publish_at) }}">
                            </div>
                        </div>
                    </div>
                    @if($item->views)
                    <div class="card bd mt-4">
                        <div class="card-header bd-b">Статистика</div>
                        <div class="card-body p-0">
                            <table class="table table-responsive mb-0">
                                <tr>
                                    <th>Просмотры</th>
                                    <td>{{ $item->views }}</td>
                                </tr>
                            </table>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>

    </form>
@endsection
