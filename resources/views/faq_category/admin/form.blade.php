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

@endsection
@section('content')
    <div class="sh-breadcrumb">
        <nav class="breadcrumb">
            <a class="breadcrumb-item" href="{{ route('admin.dashboard') }}">{{ __('Панель управления') }}</a>
            <a class="breadcrumb-item" href="{{ route('admin.faq_category.index') }}">{{ __('Категории') }}</a>
            <span class="breadcrumb-item active">@yield('title')</span>
        </nav>
    </div><!-- sh-breadcrumb -->

    <form action="{{ $item->id ? route('admin.faq_category.update', $item->id) : route('admin.faq_category.store') }}" method="POST"
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
                    <span>Список категорий FAQ</span>
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
                        @error('title')<div class="alert alert-danger">{{ $message }}</div>@enderror

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
                            </div>
                        </div><!-- card-body -->

                    </div>
                </div>
            </div>
        </div>

    </form>
@endsection
