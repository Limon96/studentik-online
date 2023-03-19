@extends('layouts.auth')
@section('title')Авторизация@endsection
@section('styles')
    <link rel="stylesheet" href="{{ asset('catalog/assets/css/style_landing.css') }}">
@endsection
@section('scripts')
    <script src="{{ asset('js/sign_in.js') }}"></script>
@endsection
@section('content')
    <div class="wrap_content">
        <section class="register">
            <div class="container">
                <div class="row rowall">
                    <div class="col-7">
                        <div class="content_f tab-login current" >
                            <div class="img_wrap">
                                <img src="{{ asset('image/landing/fon3.webp') }}">
                            </div>
                            <div class="text_wrap">
                                <h3>Вход</h3>
                                <span>Чтобы войти в личный кабинет, необходимо ввести зарегистрированный E-mail и пароль. Если вы забыли пароль, то воспользуйтесь соответствующей вкладкой "Забыли пароль".</span>
                            </div>
                        </div>
                        <div class="content_f tab-register">
                            <div class="img_wrap">
                                <img src="{{ asset('image/landing/studentrevert.webp') }}">
                            </div>
                            <div class="text_wrap">
                                <h3>Регистрация</h3>
                                <p>Станьте частью большой семьи Studentik</p>
                                <span>Чтобы зарегистрироваться на сайте, необходимо указать действующую почту и желаемый пароль. После регистрации необходимо подтвердить свою почту, перейдя по ссылке в письме.</span>
                            </div>
                        </div>
                        <div class="content_f tab-forgotten">
                            <div class="img_wrap">
                                <img src="{{ asset('image/landing/password.svg') }}">
                            </div>
                            <div class="text_wrap">
                                <h3>Забыли пароль?</h3>
                                <p>Для восстановления доступа укажите e-mail вашей учетной записи и желаемый новый пароль.</p>
                                <span>Если не можете вспомнить свой E-mail, рекомендуем связаться с технической поддержкой  и решить данную проблему.</span>
                            </div>
                        </div>

                    </div>
                    <div class="col-5">
                        <div class="clearfix tabs_regis" >
                            <ul class="tabs clearfix">
                                <li class="tab-link @if(request('act', 'login') == 'login') current @endif" data-tab="tab-login">Вход на сайт</li>
                                <li class="tab-link @if(request('act', '') == 'register') current @endif" data-tab="tab-register">Регистрация</li>
                                <li class="tab-link @if(request('act', '') == 'forgotten') current @endif" data-tab="tab-forgotten">Забыли пароль</li>
                            </ul>
                            <div id="tab-login" class="tab-content @if(request('act', 'login') == 'login') current @endif">
                                <div class="col_in clearfix">
                                    @include('auth.forms.login')
                                </div>
                            </div>
                            <div id="tab-register" class="tab-content @if(request('act', '') == 'register') current @endif">
                                <div class="col_in clearfix">
                                    @include('auth.forms.register')
                                </div>
                            </div>
                            <div id="tab-forgotten" class="tab-content @if(request('act', '') == 'forgotten') current @endif">
                                @include('auth.forms.forgotten')
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection
