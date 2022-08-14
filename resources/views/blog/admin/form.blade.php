@extends('layouts.admin')
@section('title')
    {{ __($item->id ? 'Редактирование статьи' : 'Новая статья') }}
@endsection
@section('meta_desc')
    {{ config('app.name', 'Laravel') }}
@endsection
@section('styles')
    <link rel="stylesheet" href="{{ asset('manager/lib/quill/quill.core.css') }}">
    <link rel="stylesheet" href="{{ asset('manager/lib/quill/quill.show.css') }}">
@endsection
@section('scripts')
    <script src="{{ asset('manager/lib/quill/quill.min.js') }}"></script>
    <script src="{{ asset('manager/lib/quill/quill.core.js') }}"></script>
    <script>
        var editor = new Quill('#textarea-text', {
            //modules: { toolbar: '#toolbar' },
            //theme: 'snow',
        });
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
                        @error('name')<div class="alert alert-danger">{{ $message }}</div>@enderror
                        @error('meta_title')<div class="alert alert-danger">{{ $message }}</div>@enderror
                        @error('meta_desc')<div class="alert alert-danger">{{ $message }}</div>@enderror
                        @error('meta_keywords')<div class="alert alert-danger">{{ $message }}</div>@enderror
                        @error('tags')<div class="alert alert-danger">{{ $message }}</div>@enderror
                        @error('slug')<div class="alert alert-danger">{{ $message }}</div>@enderror
                        @error('image')<div class="alert alert-danger">{{ $message }}</div>@enderror

                        <div class="card bd">
                            <div class="card-header bd-b">
                                <ul class="nav nav-tabs card-header-tabs">
                                    <li class="nav-item">
                                        <a class="nav-link active" href="#tab-general">Основные</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" href="#tab-data">Дополнительно</a>
                                    </li>
                                </ul>
                            </div><!-- card-header -->
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
                                        <textarea class="form-control"
                                                  id="textarea-text"
                                                  name="text"
                                                  cols="30"
                                                  rows="7">{{ old('text', $item->text ?? '') }}</textarea>
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


                                </div>
                                <div id="tab-data" class="tab-card">
                                    <div class="form-group">
                                        <label for="input-slug" class="form-control-label">SEO URL</label>
                                        <input id="input-slug" class="form-control select2" name="slug" value="{{ old('slug', $item->slug) }}">
                                    </div>

                                    <div class="form-group">
                                        <label class="form-label" for="file-image">Изображение</label>
                                        <input type="hidden" name="image" value="{{ $item->image }}">
                                        <input type="file" id="file-image" name="image" class="form-control-file">
                                        @if($item->image)
                                            <div class="mt-3">
                                                <img src="{{ $item->image }}" class="img-thumbnail w-25" alt="Preview">
                                            </div>
                                        @endif
                                    </div>

                                    <div class="form-group">
                                        <label for="select-status" class="form-control-label">Статус</label>
                                        <select id="select-status" class="form-control select2" name="status">
                                            <option value="0" @if(old('status', $item->status) == 0) selected @endif>Отключен</option>
                                            <option value="1" @if(old('status', $item->status) == 1) selected @endif>Включен</option>
                                        </select>
                                    </div>
                                </div>
                            </div><!-- card-body -->
                        </div>

                    </div>
                </div>

                <div class="col-lg-3">
                    <div class="card bd">
                        <div class="card-header bd-b">Статистика</div>
                        <div class="card-body"></div>
                    </div>
                </div>
            </div>
        </div>

    </form>
@endsection
