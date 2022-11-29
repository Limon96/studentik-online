@extends('layouts.auth')
@section('styles')
    <link rel="stylesheet" href="{{ asset('catalog/assets/css/style_landing.css') }}">
@endsection
@section('scripts')
    <script>
        $(document).on('submit', '#form-login', function (e) {
            e.preventDefault();
            $('.alert, .error').remove();

            $.ajax({
                url : $(this).attr('action'),
                method : $(this).attr('method'),
                data : $(this).serialize(),
                dataType : 'json',
                success : function (json) {
                    if (json['error_warning']) {
                        $('#form-login').prepend('<span class="error">' + json['error_warning'] + '</span>');
                    }
                    if (json['success']) {
                        /*$('#form-login').prepend('<span class="error">' + json['success'] + '</span>');*/
                        if (json['redirect']) {
                            location.href = json['redirect'];
                        }
                    }
                }
            });
        });
    </script>
    <script>
        $(document).on('submit', '#form-register', function (e) {
            e.preventDefault();

            $('.alert, .error').remove();

            var error = 0;

            if (!$('#form-register').find('input[name=email]').val()) {
                $('#form-register').find('input[name=email]').after('<span class="error">Неверный формат электронной почты</span>');
                error = 1;
            }

            if (!$('#form-register').find('input[name=password]').val()) {
                $('#form-register').find('input[name=password]').after('<span class="error">В пароле должно быть от 4 до 20 символов!</span>');
                error = 1;
            }

            if (!error) {
                $.ajax({
                    url: $(this).attr('action'),
                    method: $(this).attr('method'),
                    data: $(this).serialize(),
                    dataType: 'json',
                    success: function (json) {
                        console.log(json);
                        if (json['error_warning']) {
                            $('#form-register').prepend('<div class="alert alert-danger">' + json['error_warning'] + '</div>');
                        }
                        if (json['error_email']) {
                            $('#form-register').find('input[name=email]').after('<div class="alert alert-danger">' + json['error_email'] + '</div>');
                        }
                        if (json['error_password']) {
                            $('#form-register').find('input[name=password]').after('<div class="alert alert-danger">' + json['error_password'] + '</div>');
                        }
                        if (json['error_customer_group_id']) {
                            $('#form-register').find('input[name=customer_group_id]').parent().append('<div class="alert alert-danger">' + json['error_customer_group_id'] + '</div>');
                        }
                        if (json['success']) {
                            /*$('#form-register').prepend('<div class="alert alert-success">' + json['success'] + '</div>');*/
                            if (json['redirect']) {
                                location.href = json['redirect'].replace('&amp;','&');
                            }
                        }
                    }
                });
            }
        });
    </script>
    <script>
        $(document).on('submit', '#form-forgotten', function (e) {
            e.preventDefault();
            $('.alert, .error').remove();

            $.ajax({
                url : $(this).attr('action'),
                method : $(this).attr('method'),
                data : $(this).serialize(),
                dataType : 'json',
                success : function (json) {
                    if (json['error_warning']) {
                        $('#form-forgotten').prepend('<div class="alert alert-danger">' + json['error_warning'] + '</div>');
                    }
                    if (json['success']) {
                        $('#form-forgotten').prepend('<div class="alert alert-success">' + json['success'] + '</div>');
                        if (json['redirect']) {
                            location.href = json['redirect'];
                        }
                    }
                }
            });
        });
    </script>
@endsection
@section('content')

    <div class="wrap_content">
        <section class="register">
            <div class="container">
                <div class="row rowall">
                    <div class="col-7">
                        <div class="content_f tab-login current" >
                            <div class="img_wrap">
                                <img src="../image/landing/fon3.webp">
                            </div>
                            <div class="text_wrap">
                                <h3>Вход</h3>
                                <span>Чтобы войти в личный кабинет, необходимо ввести зарегистрированный E-mail и пароль. Если вы забыли пароль, то воспользуйтесь соответствующей вкладкой "Забыли пароль".</span>
                            </div>
                        </div>
                        <div class="content_f tab-register">
                            <div class="img_wrap">
                                <img src="../image/landing/studentrevert.webp">
                            </div>
                            <div class="text_wrap">
                                <h3>Регистрация</h3>
                                <p>Станьте частью большой семьи Studentik</p>
                                <span>Чтобы зарегистрироваться на сайте, необходимо указать действующую почту и желаемый пароль. После регистрации необходимо подтвердить свою почту, перейдя по ссылке в письме.</span>
                            </div>
                        </div>
                        <div class="content_f tab-forgotten">
                            <div class="img_wrap">
                                <img src="../image/landing/password.svg">
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
                                    <form id="form-login" action="{{ route('sign_in') }}" method="post">
                                        @csrf
                                        <div class="includ clearfix">
                                            <i class="fas fa-user"></i>
                                            <input type="text" name="login" placeholder="Логин или email">
                                        </div>
                                        <div class="includ clearfix">
                                            <i class="fas fa-lock"></i>
                                            <input type="password" name="password" placeholder="Пароль">
                                        </div>
                                        <div class="includ clearfix">
                                            <button type="submit">Войти</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                            <div id="tab-register" class="tab-content @if(request('act', '') == 'register') current @endif">
                                <div class="col_in clearfix">
                                    <form id="form-register" action="#" method="post">
                                        <div class="includ clearfix">
                                            <i class="fas fa-envelope"></i>
                                            <input type="text" name="email" placeholder="E-Mail">
                                        </div>
                                        <div class="includ clearfix">
                                            <i class="fas fa-lock"></i>
                                            <input type="password" name="password" placeholder="Пароль">
                                        </div>
                                        {% if customer_groups %}
                                        <div class="includ clearfix radio">
                                            {% for customer_group in customer_groups %}
                                            {% if customer_group.customer_group_id == customer_group_id %}
                                            <label class="container_lan"> customer_group.name
                                                <input type="radio" id="radio_customer_group customer_group.customer_group_id "
                                                       name="customer_group_id" value=" customer_group.customer_group_id " checked>
                                                <span class="checkmark_lan"></span>
                                            </label>
                                            {% else %}
                                            <label class="container_lan"> customer_group.name
                                                <input  type="radio" id="radio_customer_group customer_group.customer_group_id "
                                                        name="customer_group_id" value=" customer_group.customer_group_id ">
                                                <span class="checkmark_lan"></span>
                                            </label>
                                            {% endif %}
                                            {% endfor %}

                                        </div>
                                        {% endif %}
                                        <div class="includ clearfix">
                                            <input type="hidden" name="agree" value="1">
                                            <button type="submit">Зарегистрироваться</button>
                                        </div>
                                        <span class="igree">Нажимая кнопку «Регистрация», я принимаю <a href="%s" target="_blank"> пользовательское соглашение </a> и <a href="%s" target="_blank"> политику конфиденциальности </a></span>
                                    </form>

                                </div>
                            </div>
                            <div id="tab-forgotten" class="tab-content @if(request('act', '') == 'forgotten') current @endif">

                                <form id="form-forgotten" action="#" method="post">
                                    <div class="includ clearfix">
                                        <i class="fas fa-envelope"></i>
                                        <input type="text" name="email" placeholder="E-Mail">
                                    </div>
                                    <div class="includ clearfix">
                                        <button type="submit">Восстановить</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>












@endsection
