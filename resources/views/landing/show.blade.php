<!doctype html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link href="https://studentik.online/image/catalog/favicon-32x32.png" rel="icon" />
    <title>{{ $item->meta_title }}</title>

    <meta name="description" content="{{ \Illuminate\Support\Str::limit(strip_tags($item->meta_description), 155) }}">
    <meta name="keywords" content="{{ $item->meta_keywords }}">

    <link rel="stylesheet" href="{{ asset('css/land.css') }}">

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <meta property="vk:image" content="{{ asset('img/logo_razmetki.png') }}" />

    <!-- Facebook Meta Tags -->
    <meta property="og:type" content="article">
    <meta property="og:title" content="{{ \Illuminate\Support\Str::limit($item->meta_title, 55) }}">
    <meta property="og:url" content="{{ route('landing.show', $item->slug) }}">
    <meta property="og:image" content="{{ asset('img/logo_razmetki.png') }}" />
    <meta property="og:description" content="{{ \Illuminate\Support\Str::limit(strip_tags($item->meta_description), 155) }}" />
    <meta property="og:image:secure_url" content={{ asset('img/logo_razmetki.png') }}">

    <!-- Twitter Meta Tags -->
    <meta property="twitter:domain" content="{{ parse_url(route('home'), PHP_URL_HOST) }}">
    <meta property="twitter:url" content="{{ route('landing.show', $item->slug) }}">
    <meta name="twitter:title" content="{{ \Illuminate\Support\Str::limit($item->meta_title, 55) }}">
    <meta name="twitter:description" content="{{ \Illuminate\Support\Str::limit(strip_tags($item->meta_description), 155) }}">
    <meta name="twitter:image" content="{{ asset('img/logo_razmetki.png') }}">

    <!-- Yandex.Metrika counter -->
    <script>
        (function(m,e,t,r,i,k,a){m[i]=m[i]||function(){(m[i].a=m[i].a||[]).push(arguments)};
            m[i].l=1*new Date();k=e.createElement(t),a=e.getElementsByTagName(t)[0],k.async=1,k.src=r,a.parentNode.insertBefore(k,a)})
        (window, document, "script", "https://mc.yandex.ru/metrika/tag.js", "ym");

        ym(86008313, "init", {
            clickmap:true,
            trackLinks:true,
            accurateTrackBounce:true,
            webvisor:true
        });
    </script>
    <!-- /Yandex.Metrika counter -->
</head>
<body>

<header class="clearfix ">
    <div class="container">
        <div class="row rowall ">
            <div class="logo_cath">
                <a href="/">
                    <img src="https://studentik.online/image/catalog/logoo.webp" alt="Studentik" class="logo">
                </a>
            </div>
            <div class="menu_nav clearfix">
                <button class="toogle_menu clearfix">
                    <span></span>
                    <span></span>
                    <span></span>
                </button>
                <ul class="clearfix menu_drest">
                    <li><a href="https://studentik.online/orders">Лента заказов</a></li>
                    <li><a href="https://studentik.online/services">Услуги</a></li>
                    <li><a href="https://studentik.online/experts">Рейтинг авторов</a></li>
                    <li><a href="https://studentik.online/faq">FAQ</a></li>
                </ul>
            </div>
            <div class="akk_enter">
                <div class="login">
                    <div class="btn_split clearfix">
                        <a href="https://studentik.online/index.php?route=account/login&amp;act=register" class="regs">Регистрация</a>
                        <a href="https://studentik.online/index.php?route=account/login" class="in">Вход</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</header>





<section id="form_main" class="header_lend">
    <div class="container">
        <div class="row reverse">
            <div class="col-7    my-col-7">
                <div class="block_regist">
                    <div class="heads clearfix">
                        <h1>{{ $item->title }}</h1>
                        <p>Разместите заказ на бирже и обсудите работу напрямую с исполнителем</p>
                    </div>
                    <div id="formLand" class="form_bl clearfix ">
                        <form id="guest-order-form">
                            <div class="line_inp1 clearfix">
                                <input type="text" name="title" value=""  placeholder="Введите название работы">
                            </div>
                            <div class="line_inp1 ff1 clearfix">
                                <select name="subject" id="select-section-subject" class="tempSelectLand">
                                    <option value="0">Все предметы</option>
                                    @foreach($sections as $section)
                                        <optgroup label="{{ $section->name }}">
                                            @foreach($section->subjects as $subject)
                                                <option value="{{ $subject->id }}">{{ $subject->name }}</option>
                                            @endforeach
                                        </optgroup>
                                    @endforeach

                                </select>
                            </div>
                            <div class="line_inp1 ff4 clearfix">
                                <input class="textbox-n" type="email"   name="email" placeholder="Ваш E-mail" >
                                <input type="hidden" name="agree" value="1">
                            </div>
                        </form>


                    </div>
                    <div class="footert clearfix">
                        <div class="right_rek">
                            <button id="guest-order">Узнать стоимость</button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-5 my-col-5">
                <div class="wrap_man">
                    <div class="zagol">
                        <h2>Studentik – фриланс биржа для<br> студентов и экспертов учебных <br>работ</h2>
                        <p>Подбор материала, решение задач,<br> переводы и другая помощь от лучших экспертов</p>
                    </div>
                    <img src="{{ asset('img/fon2.webp') }}"  alt="studentik-fon">
                </div>
            </div>

        </div>
    </div>
</section>


<section class="bac_fone">
    <div class="bg_absolut"></div>
</section>


<section class="preim">
    <div class="container">
        <div class="row">
            <div class="col-5">
                <div class="wrap_work">
                    <div class="zagol">
                        <h3>Studentik – ценит ваше время</h3>
                        <p>Вы можете не заботиться об учебе и заниматься своими любимыми делами</p>
                    </div>
                    <img src="{{ asset('img/fon4.webp') }}"  alt="studentik-fon">
                </div>
            </div>
            <div class="col-7">
                <div class="oper opt1 clearfix">
                    <div class="preim_doort">
                        <img src="{{ asset('img/employee2.webp') }}" alt="studentik-fon">
                        <span>Разместите заказ и получите <br> предложения с ценами экспертов</span>
                    </div>
                    <img src="{{ asset('img/arrow2.webp') }}" class="arrow_l" alt="arrow">
                </div>
                <div class="oper opt2 clearfix">
                    <img src="{{ asset('img/arrow1.webp') }}" class="arrow_l" alt="arrow">
                    <div class="preim_doort">
                        <img src="{{ asset('img/employee.webp') }}" alt="studentik-fon">
                        <span>Выберите эксперта по подходящей цене <br> и хорошим отзывам</span>
                    </div>
                </div>
                <div class="oper opt3 clearfix">
                    <div class="preim_doort">
                        <img src="{{ asset('img/check-list.webp') }}" alt="studentik-fon">
                        <span>Сдайте работу на проверку  <br> преподавателю</span>
                    </div>
                    <img src="{{ asset('img/arrow2.webp') }}" class="arrow_l" alt="studentik-fon">
                </div>
                <div class="oper opt4 clearfix">
                    <img src="{{ asset('img/arrow1.webp') }}" class="arrow_l" alt="studentik-fon">
                    <div class="preim_doort">
                        <img src="{{ asset('img/medal.webp') }}" alt="studentik-fon">
                        <span>Получите положительную оценку и  <br> оставьте отзыв эксперту</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="catacomba">
    <img src="{{ asset('img/fon1.webp') }}" class="books" alt="studentik-fon">

    <div class="container">
        <div class="row">
            <div class="col-12">
                <h3>Наши преимущества</h3>
            </div>
            <div class="col-4">
                <div class="wariet">
                    <div class="wrap_img">
                        <img src="{{ asset('img/stopwatc.webp') }}" alt="studentik-fon">
                    </div>
                    <p>Выполняем срочные заказы</p>
                    <span>Даже если сроки горят, наши эксперты выполнят работу в максимально кратчайшее время</span>
                </div>
            </div>
            <div class="col-4">
                <div class="wariet">
                    <div class="wrap_img">
                        <img src="{{ asset('img/hand.webp') }}" alt="studentik-fon">
                    </div>
                    <p>Без посредников</p>
                    <span>Так как вы работаете напрямую с экспертами – цены ниже чем в агентствах</span>
                </div>
            </div>
            <div class="col-4">
                <div class="wariet">
                    <div class="wrap_img">
                        <img src="{{ asset('img/employees1.webp') }}" alt="studentik-fon">
                    </div>
                    <p>Квалифицированные <br>специалисты</p>
                    <span>Мы проверяем уровень знаний наших экспертов и обеспечиваем высокий уровень качества</span>
                </div>
            </div>
            <div class="col-4">
                <div class="wariet">
                    <div class="wrap_img">
                        <img src="{{ asset('img/wooman.webp') }}" alt="studentik-fon">
                    </div>
                    <p>Гарантия возврата денег</p>
                    <span>В случае, если что-то пойдет не так, мы гарантируем полный возврат уплаченной суммы</span>
                </div>
            </div>
            <div class="col-4">
                <div class="wariet">
                    <div class="wrap_img">
                        <img src="{{ asset('img/list.webp') }}" alt="studentik-fon">
                    </div>
                    <p>Бесплатные корректировки и <br>доработки</p>
                    <span>Доработки и консультации в рамках задания совершенно бесплатны</span>
                </div>
            </div>
            <div class="col-4">
                <div class="wariet">
                    <div class="wrap_img">
                        <img src="{{ asset('img/telemarket.webp') }}" alt="studentik-fon">
                    </div>
                    <p>Отзывчивая служба поддержки</p>
                    <span>Поможем с любыми трудностями</span>
                </div>
            </div>

        </div>
    </div>
</section>

@if($item->blocks)
    @foreach($item->blocks as $block)
        @includeIf('pagebuilder.blocks.' . $block['type'], $block)
    @endforeach
@endif

<section class="all_foon">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <h2>Другие типы работ</h2>
            </div>
            <div class="col-md-12">
                <div class="fool_roop">
                    @foreach($type_work_pages as $twp)
                    <div class="item_link">
                        <a href="{{ route('landing.show', $twp['slug']) }}">
                            <div class="wrap_comp">
                                <span>{{ mb_substr($twp['menu_title'], 0, 1) }}</span>
                                <p>{{ $twp['menu_title'] }}</p>
                            </div>
                        </a>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</section>







<footer>
    <div class="container">
        <div class="row">
            <div class="clearfix rowall">
                <div class="col-4">
                    <div class="logo">
                        <a href="/"><img src="{{ asset('img/logo.webp') }}" alt="logo"></a>
                        <p>Используя “Studentik”, вы принимаете <br>
                            <a href="/index.php?route=information/information&information_id=5">пользовательское соглашение</a> и <a href="/index.php?route=information/information&information_id=3">политику <br>
                                обработки персональных данных</a></p>
                    </div>
                </div>
                <div class="col-5">
                    <div class="sub_menu">
                        <ul class=" clearfix">
                            <li><a href="/orders">Лента заказов</a></li>
                            <li><a href="/services">Услуги</a></li>
                            <li><a href="/experts">Рейтинг авторов</a></li>
                            <li><a href="/faq">FAQ</a></li>
                            <!--<li><a href="#">Партнерам</a></li>-->
                        </ul>
                    </div>
                    <div class="social">
                        <ul class=" clearfix">
                            <li><img src="{{ asset('img/mir.webp') }}"  alt="studentik-fon"></li>
                            <li><img src="{{ asset('img/master.webp') }}"  alt="studentik-fon"></li>
                            <li><img src="{{ asset('img/visa.webp') }}"  alt="studentik-fon"></li>
                            <li><img src="{{ asset('img/qiwi.webp') }}"  alt="studentik-fon"></li>
                            <li><img src="{{ asset('img/yandex.webp') }}"  alt="studentik-fon"></li>
                        </ul>
                    </div>
                </div>
                <div class="col-3">
                    <div class="colling">
                        <span>Служба поддержки:</span>
                        <a href="mailto:studentik.online@mail.ru" class="mail">studentik.online@mail.ru</a>
                        <ul class="massengers clearfix">
                            <li><a href="tg://resolve?domain=studentik_online"><img src="{{ asset('img/tg.svg') }}" alt="studentik-fon"></a></li>
                            <li><a target="_blank" href="https://vk.com/studentik.online"><img src="{{ asset('img/vk.svg') }}" alt="studentik-fon"></a></li>
                            <li><a target="_blank" href="https://www.instagram.com/studentik.online/"><img src="{{ asset('img/insta.svg') }}" alt="studentik-fon"></a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</footer>





<div class="notification_top"></div>
<script src="{{ asset('css/land.js') }}"></script>
<script>
    $(document).on('click', '#guest-order', function () {
        $('#guest-order-form').submit();
    })

    $(document).on('submit', '#guest-order-form', function (e) {
        e.preventDefault();
        $('.has-error').remove();

        if (!$('#guest-order-form select[name=subject]').val()) {
            $('#guest-order-form select[name=subject]').before('<div class="has-error">Выберите предмет</div>');
        }

        $.ajax({
            url : '../index.php?route=account/register/guestOrder',
            method : 'post',
            data : $('#guest-order-form input, #guest-order-form select'),
            dataType : 'json',
            success : function (json) {
                if (json['error_email']) {
                    $('#guest-order-form input[name=email]').before('<div class="has-error">' + json['error_email'] + '</div>');
                }

                if (json['error_subject']) {
                    $('#guest-order-form select[name=subject]').before('<div class="has-error">' + json['error_subject'] + '</div>');
                }

                if (json['error_work_type']) {
                    $('#guest-order-form select[name=work_type]').before('<div class="has-error">' + json['error_work_type'] + '</div>');
                }

                if (json['error_title']) {
                    $('#guest-order-form input[name=title]').before('<div class="has-error">' + json['error_title'] + '</div>');
                }

                if (json['error_warning']) {
                    $('#guest-order-form').before('<div class="has-error">' + json['error_warning'] + '</div>');
                }

                if (json['redirect']) {
                    location.href = json['redirect'];
                }
            }
        });
    });
</script>


<style>


    .reverse .my-col-7{
        float: right;
    }

    .reverse .my-col-5{
        float: right;
    }




/*

    h1{
        font-weight: bold;
        font-size: 22px;
        line-height: 27px;
        margin: 0 0 25px 0;
        font-family: 'Montserrat', sans-serif;
    }




    .header_lend .block_regist .heads h2 {
        font-weight: bold;
        font-size: 30px;
        line-height: 37px;
        color: #00324F;
        margin: 0 0 10px 0;
        font-family: 'Montserrat', sans-serif;
    }
*/


    @media (min-width: 320px) and (max-width: 435px) {
        .header_lend .block_regist .form_bl .line_inp1 .has-error {
            font-size: 8px;
        }

     /*   .header_lend .block_regist .heads h2 {
            font-weight: bold;
            font-size: 24px;
            line-height: 37px;
            color: #00324F;
            margin: 0 0 10px 0;

        }
*/


    }







</style>


</body>
</html>
