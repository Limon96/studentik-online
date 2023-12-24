@extends('layouts.admin')
@section('title')
    {{ __($item->id ? 'Пользователь #' . $item->id : 'Новый пользователь') }}
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
            <a class="breadcrumb-item" href="{{ route('admin.dashboard') }}">Панель управления</a>
            <a class="breadcrumb-item" href="{{ route('admin.user.index') }}">Администраторы</a>
            <span class="breadcrumb-item active">@yield('title')</span>
        </nav>
    </div><!-- sh-breadcrumb -->

    <form action="{{ $item->id ? route('admin.user.update', $item->id) : route('admin.user.store') }}"
          method="POST"
          enctype="multipart/form-data">
        @if($item->id)
            @method('PATCH')
        @endif
        @csrf
        <div class="sh-pagetitle">
            <div class="input-group justify-content-end">
                <button class="btn btn-primary"><i class="fa fa-save mr-2"></i>Сохранить</button>
            </div><!-- input-group -->
            <div class="sh-pagetitle-left">
                <div class="sh-pagetitle-icon"><i class="icon ion-clipboard"></i></div>
                <div class="sh-pagetitle-title">
                    <span>Список всех записей</span>
                    <h2>@yield('title')</h2>
                </div><!-- sh-pagetitle-left-title -->
            </div><!-- sh-pagetitle-left -->
        </div><!-- sh-pagetitle -->

        <div class="sh-pagebody">
            <div class="row row-sm mt-3 mb-3">
                <div class="col-lg-12 col-md-12 col-sm-12">
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

                        <div class="card-header bd-b">
                            <ul class="nav nav-tabs card-header-tabs">
                                <li class="nav-item">
                                    <a class="nav-link active" href="#tab-general">Основное</a>
                                </li>
                            </ul>
                        </div><!-- card-header -->
                        <div class="card-body color-gray-lighter">
                            <div id="tab-general" class="tab-card active">

                                @include('fields.text', [
                                    'field_id' => 'name',
                                    'label' => 'Имя',
                                    'name' => 'name',
                                    'value' => $item->name,
                                ])

                                @include('fields.text', [
                                    'field_id' => 'login',
                                    'label' => 'Логин',
                                    'name' => 'login',
                                    'value' => $item->login,
                                ])

                                @include('fields.email', [
                                    'field_id' => 'email',
                                    'label' => 'Email',
                                    'name' => 'email',
                                    'value' => $item->email,
                                ])

                                @include('fields.password', [
                                    'field_id' => 'password',
                                    'label' => 'Пароль',
                                    'name' => 'password',
                                ])
                                @include('fields.select', [
                                    'field_id' => 'role',
                                    'label' => 'Роль',
                                    'name' => 'roles[]',
                                    'value' => implode(', ', $item->roles->pluck('name')->toArray()),
                                    'options' => $roles,
                                ])

                            </div>

                        </div><!-- card-body -->
                    </div>

                </div>

            </div>
        </div>

    </form>
@endsection
