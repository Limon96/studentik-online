@php $v = 0.1; @endphp
<!doctype html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">

    @yield('styles')

    <title>@yield('title')</title>
    <meta name="description" content="@yield('description')">
    <meta name="keywords" content="@yield('keywords')">
    <link rel="shortcut icon" href="{{ asset('image/catalog/favicon-32x32.png') }}" type="image/x-icon">

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <meta property="vk:image" content="{{ asset('catalog/assets/img/polez.webp') }}" />

    <!-- Facebook Meta Tags -->
    <meta property="og:type" content="article">
    <meta property="og:title" content="@yield('title')">
    <meta property="og:url" content="{{ url()->current() }}">
    <meta property="og:image" content="{{ asset('catalog/assets/img/polez.webp') }}" />
    <meta property="og:description" content="@yield('description')" />
    <meta property="og:image:secure_url" content="{{ asset('catalog/assets/img/polez.webp') }}">

    <!-- Twitter Meta Tags -->
    <meta property="twitter:domain" content="{{ request()->getHost() }}">
    <meta property="twitter:url" content="{{ url()->current() }}">
    <meta name="twitter:title" content="@yield('title')">
    <meta name="twitter:description" content="@yield('description')">
    <meta name="twitter:image" content="{{ asset('catalog/assets/img/polez.webp') }}">

    <link rel="stylesheet" href="{{ asset('catalog/assets/css/font_awesome/css/all.min.css') }}">
    <link rel="stylesheet" href="{{ asset('catalog/assets/css/select2.css?v=' . $v) }}">
    <link rel="stylesheet" href="{{ asset('catalog/assets/css/style.css?v=' . $v) }}">



    <link rel="stylesheet" href="{{ asset('catalog/assets/css/responsive.css?v=' . $v) }}">



    <script src="{{ asset('catalog/assets/js/jquery.min.js?v=' . $v) }}"></script>
    <script src="{{ asset('catalog/assets/js/custom.js?v=' . $v) }}"></script>

    <script src="{{ asset('catalog/assets/js/common.js?v=' . $v) }}" type="text/javascript"></script>

    @if(!auth()->check())
    <script src="//code-ya.jivosite.com/widget/3x3Y24atbz" async></script>
    @endif

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

    <!-- Global site tag (gtag.js) - Google Analytics -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=G-JKFYXTJR08"></script>
    <script>
        window.dataLayer = window.dataLayer || [];
        function gtag(){dataLayer.push(arguments);}
        gtag('js', new Date());

        gtag('config', 'G-JKFYXTJR08');
    </script>
    <!-- /Global site tag (gtag.js) - Google Analytics -->

    <!-- VK Pixel -->
    <script>!function(){var t=document.createElement("script");t.type="text/javascript",t.async=!0,t.src="https://vk.com/js/api/openapi.js?169",t.onload=function(){VK.Retargeting.Init("VK-RTRG-1136367-eUAqW"),VK.Retargeting.Hit()},document.head.appendChild(t)}();</script>
    <noscript><img src="https://vk.com/rtrg?p=VK-RTRG-1136367-eUAqW" style="position:fixed; left:-999px;" alt=""/></noscript>
    <!-- /VK Pixel -->
</head>
<body>



@include('components.header')

@yield('content')

<div class="notification_top"></div>

<script src="{{ asset('../catalog/assets/js/select2.js?v=' . $v) }}"></script>
<script src="{{ asset('../catalog/assets/slick/slick.min.js?v=' . $v) }}"></script>
<script src="{{ asset('../catalog/assets/js/main.js?v=' . $v) }}"></script>

@yield('scripts')

</body>
</html>
