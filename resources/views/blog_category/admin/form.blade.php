@extends('layouts.admin')
@section('title')
    {{ __($item->id ? 'Редактирование категории' : 'Новая категория') }}
@endsection
@section('meta_desc')
    {{ config('app.name', 'Laravel') }}
@endsection
@section('styles')

@endsection
@section('scripts')

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
            <a class="breadcrumb-item" href="{{ route('admin.blog_category.index') }}">{{ __('Категории') }}</a>
            <span class="breadcrumb-item active">@yield('title')</span>
        </nav>
    </div><!-- sh-breadcrumb -->

    <form action="{{ $item->id ? route('admin.blog_category.update', $item->id) : route('admin.blog_category.store') }}" method="POST"
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
                    <span>Список категорий блога</span>
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
                        @error('meta_title')<div class="alert alert-danger">{{ $message }}</div>@enderror
                        @error('meta_desc')<div class="alert alert-danger">{{ $message }}</div>@enderror
                        @error('meta_keywords')<div class="alert alert-danger">{{ $message }}</div>@enderror
                        @error('slug')<div class="alert alert-danger">{{ $message }}</div>@enderror
                        @error('image')<div class="alert alert-danger">{{ $message }}</div>@enderror

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
                                    <label class="form-control-label" for="input-meta_h1">Заголовок H1</label>
                                    <input class="form-control"
                                           id="input-meta_h1" type="text"
                                           name="meta_h1"
                                           value="{{ old('meta_h1', $item->meta_h1 ?? '') }}">
                                </div>

                                <div class="form-group">
                                    <label class="form-control-label"
                                           for="textarea-text">Текст</label>
                                    <textarea class="form-control"
                                              id="textarea-text"
                                              name="text"
                                              cols="30"
                                              rows="3">{{ old('text', $item->text ?? '') }}</textarea>
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
                                <label for="select-category" class="form-control-label">Родительская категория</label>
                                <select id="select-category" class="form-control select2" name="blog_category_id">
                                    <option value="0">-- Не выбрано --</option>
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
                                    <option value="0" @if(old('status', $item->status) == 0) selected @endif>Отключен</option>
                                    <option value="1" @if(old('status', $item->status) == 1) selected @endif>Включен</option>
                                </select>
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
