<!doctype html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>{{ $title }}</title>



    <meta name="description" content="{{ $description }}">
    <meta name="keywords" content="{{ $keywords }}">


    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <meta property="vk:image" content="https://studentik.online/img/logo_razmetki.png" />

    <!-- Facebook Meta Tags -->
    <meta property="og:type" content="article">
    <meta property="og:title" content="{{ $title }}">
    <meta property="og:url" content="{{ $current_url }}">
    <meta property="og:image" content="https://studentik.online/img/logo_razmetki.png" />
    <meta property="og:description" content="{{ $description }}" />
    <meta property="og:image:secure_url" content="https://studentik.online/img/logo_razmetki.png">

    <!-- Twitter Meta Tags -->
    <meta property="twitter:domain" content="{{ $domain }}">
    <meta property="twitter:url" content="{{ $current_url }}">
    <meta name="twitter:title" content="{{ $title }}">
    <meta name="twitter:description" content="{{ $description }}">
    <meta name="twitter:image" content="https://studentik.online/img/logo_razmetki.png">

    <link href="img/favicon-32x32.png" rel="icon">
    <link rel="stylesheet" href="{{ asset('style_landing.css') }}">

    <script src="//code-ya.jivosite.com/widget/3x3Y24atbz" async></script>


    <!-- Yandex.Metrika counter -->
    <script type="text/javascript" >
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
    <noscript><div><img src="https://mc.yandex.ru/watch/86008313" style="position:absolute; left:-9999px;" alt="" /></div></noscript>
    <!-- /Yandex.Metrika counter -->
</head>
<body>

<div class="menu">
    <div class="container">
        <div class="logo">
            <img src="{{ asset('img/logo.webp') }}" alt="logo">
        </div>
    </div>

</div>



<section class="info">
    <div class="container">
        <div class="wrap_tops">
            <div class="left">
                <div class="outer_blo">
                    <h1>Ты учишься? Мы поможем!</h1>
                    <form action="#" id="guest-order-form">
                        <div class="block_form">
                            <input type="text" name="title" placeholder="Опишите задание. Например: «Подбор материала для эссе»" minlength="4" maxlength="150">
                            <input type="email" name="email" placeholder="E-mail">
                        </div>
                        <div class="block_go">
                            <a href="https://studentik.online/index.php?route=account/login&act=register" target="_blank" class="autor">Вы автор?</a>
                            <button class="submit">Получить помощь</button>
                        </div>
                        <div class="mini_footer">
                            <label class="container1">
                                <p>Я принимаю <a href="https://studentik.online/index.php?route=information/information&information_id=3" target="_blank">политику обработки персональных данных</a></p>
                                <input type="checkbox" name="agree" checked="checked">
                                <span class="checkmark"></span>
                            </label>
                        </div>
                    </form>
                </div>
            </div>
            <div class="right">
                <div class="img_wrap">
                    <img src="{{ asset('img/fon1.png') }}" alt="fon.png">
                </div>
            </div>
        </div>
    </div>
</section>



<footer>
    <div class="container">

        <div class="oputert">
            <div class="pilorem_left">
                <div class="left_col">
                    <div class="one">
                        <div class="item">
                            <img src="{{ asset('img/clock.png') }}" alt="clock">
                            <p>Отклики от экспертов в течение 30 минут</p>
                        </div>
                    </div>
                    <div class="two">
                        <div class="item">
                            <img src="{{ asset('img/thumb-up.png') }}" alt="thumb-up">
                            <p>Без посредников</p>
                        </div>
                        <div class="item">
                            <img src="{{ asset('img/security.png') }}" alt="security">
                            <p>100% гарантия возврата средств</p>
                        </div>
                    </div>
                </div>
                <div class="left_col_visa">
                    <p>Мы принимаем:</p>
                    <ul>
                        <li><img src="{{ asset('img/master.png') }}" alt="master"></li>
                        <li><img src="{{ asset('img/visa.png') }}" alt="visa"></li>
                        <li><img src="{{ asset('img/qiwi.png') }}" alt="qiwi"></li>
                        <li><img src="{{ asset('img/webmoney.png') }}" alt="webmoney"></li>
                        <li><img src="{{ asset('img/yandex.png') }}" alt="yandex"></li>
                    </ul>
                </div>
            </div>
            <div class="pilorem_right">
                <div class="contact_right clearfix">
                    <h3>Контакты</h3>
                    <p>ИП Хамитов Радик Темурович</p>
                    <a href="mailto:studentik.online@mail.ru" class="mail">studentik.online@mail.ru</a>
                    <a href="https://studentik.online/index.php?route=information/information&amp;information_id=5" class="polit">Пользовательское соглашение</a>

                    <ul class="social clearfix">
                        <li><a target="_blank" href="https://vk.com/studentik.online"><img src="../catalog/assets/img/icons/vk.svg"></a></li>
                        <li><a href="tg://resolve?domain=studentik_online"><img src="../catalog/assets/img/icons/telegram.svg"></a></li>
                        <li><a target="_blank" href="https://www.instagram.com/studentik.online/"><img src="../catalog/assets/img/icons/instg.svg"></a></li>
                    </ul>
                </div>
            </div>
        </div>





    </div>
</footer>

<script type="text/javascript" src="https://studentik.online/catalog/assets/js/jquery.min.js"></script>
<script>
    $(document).on('submit', '#guest-order-form', function (e) {
        e.preventDefault();
        $('.eror').remove();
        $.ajax({
            url : 'https://studentik.online/index.php?route=account/register/landingOrder',
            method : 'post',
            data : $('#guest-order-form input[type=text], #guest-order-form input[type=email], #guest-order-form input[type=checkbox]:checked, #guest-order-form select'),
            dataType : 'json',
            success : function (json) {
                if (json['error_warning']) {
                    $('#guest-order-form input[name=email]').after('<span class="eror">' + json['error_warning'] + '</span>');
                }
                if (json['error_email']) {
                    $('#guest-order-form input[name=email]').after('<span class="eror">' + json['error_email'] + '</span>');
                }

                if (json['error_title']) {
                    $('#guest-order-form input[name=title]').after('<span class="eror">' + json['error_title'] + '</span>');
                }

                if (json['error_agree']) {
                    $('#guest-order-form input[name=agree]').parents('label').after('<span class="eror">' + json['error_agree'] + '</span>');
                }

                if (json['redirect']) {
                    location.href = json['redirect'];
                }
            }
        });
    });
</script>

</body>
</html>




























