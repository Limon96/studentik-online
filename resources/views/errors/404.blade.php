@extends('layouts.base')

@section('title') 404 - Not Found @endsection
@section('description') 404 - Not Found @endsection
@section('keywords') 404 - Not Found @endsection

@section('scripts')
    <script>
        $(document).ready(function () {

            var $headers = $('.table_of_contents h2, .table_of_contents h3');

            if ($headers.length < 1) {
                $('#content-list').remove();
                return;
            }

            $headers.each(function (i, e) {
                var tagName =  $(e).prop("tagName");
                var nav_id = tagName.toLowerCase() + '-' + i;
                var name = (tagName === 'H3' ? "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;": '') + $(e).text();

                $(e).attr('id', nav_id);

                $('#content-list ul').append("<li><a href='#" + nav_id + "'>" + name + "</a></li>");
            });
        });
    </script>
@endsection

@section("content")


    <section class="error404">
        <div class="container">
            <div class="row">
                <div class="clearfix kitr">
                    <div class="dearfit_left">
                        <img src="{{ asset('image/landing/kit.webp') }}">
                    </div>
                    <div class="dearfit_right">
                        <p>Такой страницы не существует</p>
                        <span>Кажется, такой страницы не существует. <br>Попробуйте ещё раз или перейдите:</span>
                        <a href="https://studentik.online/">На главную</a>
                    </div>
                </div>
            </div>
        </div>
    </section>




    <section class="uslug">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <h3>Популярные услуги</h3>
                </div>
                <div class="col-3">
                    <a href="https://studentik.online/new-order/laboratornaya-rabota">
                        <div class="wariet">
                            <div class="wrap_img"><img src="{{ asset('image/uslug/011-chemistry.svg') }}"></div>
                            <span>Лабораторная</span>
                            <p>от <strong>500</strong> р.</p>
                        </div>
                    </a>
                </div>
                <div class="col-3"> <a href="https://studentik.online/new-order/kursovaya-rabota">
                        <div class="wariet">
                            <div class="wrap_img"><img src="{{ asset('image/uslug/048-exam.svg') }}"></div>
                            <span>Курсовая</span>
                            <p>от <strong>1 000</strong> р.</p>
                        </div>  </a>
                </div>
                <div class="col-3">
                    <a href="https://studentik.online/new-order/referat">
                        <div class="wariet">
                            <div class="wrap_img"><img src="{{ asset('image/uslug/001-referat.webp') }}"></div>
                            <span>Реферат</span>
                            <p>от <strong>500</strong> р.</p>
                        </div>  </a>
                </div>
                <div class="col-3">
                    <a href="https://studentik.online/new-order/kontrolnaya-rabota">
                        <div class="wariet">
                            <div class="wrap_img"><img src="{{ asset('image/uslug/008-homework.svg') }}"></div>
                            <span>Контрольная работа</span>
                            <p>от <strong>300</strong> р.</p>
                        </div>
                    </a>
                </div>

                <div class="col-12">
                    <a href="/index.php?route=services/services" class="btn_gr">Все услуги</a>
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
                            <a href="#"><img src="{{ asset('image/landing/logo.webp') }}"></a>
                            <p>Используя “Studentik”, вы принимаете <br>
                                <a href="/index.php?route=information/information&amp;information_id=5">пользовательское соглашение</a> и <a href="/index.php?route=information/information&amp;information_id=3">политику <br>
                                    обработки персональных данных</a></p>
                        </div>
                    </div>
                    <div class="col-5">
                        <div class="sub_menu">
                            <ul class=" clearfix">
                                <li><a href="/index.php?route=order/order">Лента заказов</a></li>
                                <li><a href="/index.php?route=services/services">Услуги</a></li>
                                <li><a href="/index.php?route=expert/expert">Рейтинг авторов</a></li>
                                <li><a href="/index.php?route=support/faq">FAQ</a></li>
                                <!--<li><a href="#">Партнерам</a></li>-->
                            </ul>
                        </div>
                        <div class="social">
                            <ul class=" clearfix">
                                <li><img src="{{ asset('image/landing/mir.webp') }}"></li>
                                <li><img src="{{ asset('image/landing/master.webp') }}"></li>
                                <li><img src="{{ asset('image/landing/visa.webp') }}"></li>
                                <li><img src="{{ asset('image/landing/qiwi.webp') }}"></li>
                                <li><img src="{{ asset('image/landing/yandex.webp') }}"></li>
                            </ul>
                        </div>
                    </div>
                    <div class="col-3">
                        <div class="colling">
                            <span>Служба поддержки:</span>
                            <a href="mailto:studentik.online@mail.ru" class="mail">studentik.online@mail.ru</a>
                            <ul class="massengers clearfix">
                                <li><a href="tg://resolve?domain=studentik_online"><img src="{{ asset("image/landing/tg.svg") }}"></a></li>
                                <li><a target="_blank" href="https://vk.com/studentik.online"><img src="{{ asset("image/landing/vk.svg") }}"></a></li>
                                <li><a target="_blank" href="https://www.instagram.com/studentik.online/"><img src="{{ asset("image/landing/insta.svg") }}"></a></li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </footer>


    <style>
        .error404{
            margin-top: 150px;
        }

        .error404 .kitr{
            margin: 0 auto 40px;
        }

        .error404 .kitr .dearfit_left {
            width: 40%;
            float: left;
            padding: 15px;
        }
        .error404 .kitr .dearfit_left img{
            width: 100%;
            max-width: 500px;
            margin: 0 auto;
            display: block;
        }



        .error404 .kitr .dearfit_right{
            width: 60%;
            float: left;
            padding: 15px;
        }
        .error404 .kitr .dearfit_right p {
            font-weight: bold;
            font-size: 40px;
            line-height: 37px;
            color: #00324F;
            margin: 80px 0 20px 0;
            text-align: center;
        }

        .error404 .kitr .dearfit_right span {
            font-size: 16px;
            line-height: 22px;
            margin: 0 auto 20px;
            display: block;
            text-align: center;
        }

        .error404 .kitr .dearfit_right a {
            background: #1CB7AD;
            border-radius: 25px;
            font-weight: 700;
            font-size: 16px;
            line-height: 20px;
            text-align: center;
            color: #FFFFFF;
            padding: 15px 0 15px 0;
            display: block;
            width: 100%;
            border: 0;
            max-width: 190px;
            margin: 0 auto;
        }


        .error404 .kitr .dearfit_right a:hover{
            cursor: pointer;
            background: #168d85;
        }




        .uslug {
            padding: 180px 0 100px 0;
        }
        .uslug h3{
            font-size: 30px;
            line-height: 37px;
            font-weight: 700;
            text-align: center;
            color: #00324F;
            margin: 0 auto 60px;
        }

        .uslug .wariet {
            background: #FFFFFF;
            border: 1px solid rgba(206, 192, 213, 0.3);
            box-shadow: 0px 4px 25px rgb(206 192 213 / 30%);
            border-radius: 20px;
            max-width: 260px;
            margin: 0 auto 30px;
            padding: 30px 0;
            -webkit-transition: all 0.3s ease;;
            -moz-transition: all 0.3s ease;;
            -o-transition: all 0.3s ease;;
            transition: all 0.3s ease;
        }


        .uslug .wariet:hover {
            -webkit-transform: scale(1.2);
            -ms-transform: scale(1.2);
            transform: scale(1.05);
        }




        .uslug .wariet .wrap_img {
            display: flex;
            justify-content: center;
            align-items: center;
        }


        .uslug img {
            margin: 0 auto;
            display: block;
            max-width: 100px;
            height: 100px;
        }
        .uslug span {
            display: block;
            text-align: center;
            font-size: 14px;
            line-height: 17px;
            font-weight: 400;
            margin: 20px auto 15px;
        }
        .uslug p{
            font-weight: 700;
            font-size: 14px;
            line-height: 17px;
            text-align: center;
            display: block;
            margin: 0 auto 0;
        }

        .uslug p strong {
            font-weight: 800;
            font-size: 20px;
            line-height: 17px;
            text-align: center;
            margin: 0 auto 0;
        }



        .uslug a.btn_gr{
            background: #B2E228;
            box-shadow: 0px 4px 15px rgba(178, 226, 40, 0.25);
            border-radius: 25px;
            font-weight: 700;
            font-size: 16px;
            line-height: 20px;
            text-align: center;
            color: #FFFFFF;
            padding: 15px 0;
            margin: 50px auto 0;
            display: block;
            max-width: 270px;



        }

        .uslug a.btn_gr:hover{
            background: #93ba23;
        }


        .container {
            max-width: 1200px;
        }

        footer{
            padding: 140px 0 100px;
            background-image: url(../../../image/landing/bg3.webp);
            background-position: top center;
            background-size: cover;
            background-repeat: no-repeat;
            position: relative;
        }

        footer .logo{

        }

        footer .logo img {
            width: 316px;
            display: block;
            margin: 0 0 0 0;
        }

        footer .logo p{
            font-size: 14px;
            line-height: 17px;
            margin: 23px 0 0 0;
        }


        footer .logo p a{
            color: #13847c;
        }

        footer .logo p a:hover{
            text-decoration: underline;
        }

        footer .sub_menu{
            margin: 15px 0 0 0;
        }
        footer .sub_menu ul {
            display: -webkit-flex;
            display: -ms-flex;
            display: flex;
            -webkit-flex-wrap: wrap;
            -ms-flex-wrap: wrap;
            flex-wrap: wrap;
            justify-content: space-between;
            -ms-align-items: center;
            align-items: center;
            list-style: none;
        }
        footer .sub_menu ul li{

        }
        footer .sub_menu ul li a {
            font-weight: 500;
            font-size: 14px;
            line-height: 20px;
            padding: 0 0 0 0;
            margin: 0 0 0 0;
            color: #000;
            border-bottom: 1px solid transparent;
        }
        footer .sub_menu ul li a:hover{
            color: #1CB7AD;
            border-bottom: 1px solid #1CB7AD;
            cursor: pointer;
        }


        footer .social {
            margin: 55px 0 0 0;
        }
        footer .social ul{
            display: -webkit-flex;
            display: -ms-flex;
            display: flex;
            -webkit-flex-wrap: wrap;
            -ms-flex-wrap: wrap;
            flex-wrap: wrap;
            justify-content: space-between;
            -ms-align-items: center;
            align-items: center;
            list-style: none;
        }
        footer .social ul li{

        }
        footer .social ul li a{

        }
        footer .social ul li a img{

        }

        footer .colling{
            margin: 0 0 0 0;
        }


        footer .colling span{
            font-weight: 400;
            font-size: 14px;
            line-height: 17px;
            text-align: right;
            display: block;
            margin: 0 0 0 0;
        }
        footer .colling a.phone {
            font-weight: 700;
            font-size: 22px;
            line-height: 26px;
            text-align: right;
            display: block;
            margin: 1px 0 1px 0;
        }

        footer .colling a.mail{
            font-weight: 400;
            font-size: 14px;
            line-height: 17px;
            text-align: right;
            display: block;
            margin: 0 0 0 0;
        }

        footer .colling ul.massengers {
            margin-top: 13px;
        }
        footer .colling ul.massengers li {
            margin: 0 0 0 20px;
            float: right;
        }
        footer .colling ul.massengers li a{

        }
        footer .colling ul.massengers li a img{
            width: 20px;
        }

        @media (min-width: 320px) and (max-width: 479px) {

            .error404 {
                margin-top: 65px;
            }

            .error404 .kitr .dearfit_left {
                width: 100%;
                float: left;
                padding: 15px;
            }

            .error404 .kitr .dearfit_right {
                width: 100%;
                float: left;
                padding: 15px;
            }

            .error404 .kitr .dearfit_left img {
                max-width: 320px;
            }

            .error404 .kitr .dearfit_right p {
                font-size: 22px;
                line-height: 24px;
                margin: 20px 0 20px 0;
            }

            .error404 .kitr .dearfit_right span {
                font-size: 16px;
                line-height: 22px;
                margin: 0 auto 20px;
            }


            .error404 .kitr .dearfit_right span br {
                display: none;
            }


            .uslug {
                padding: 30px 0 30px 0;
            }
            .uslug .col-3 {
                width: 50%;
            }

            footer .rowall {
                display: flex;
                flex-flow: wrap;
            }

            footer .rowall .col-4{
                order: 1;
                width: 100%;
            }
            footer .rowall .col-3{
                order: 2;
                width: 100%;
            }
            footer .rowall .col-5{
                order: 3;
                width: 100%;
            }
            footer .sub_menu {
                margin: 30px 0 0 0;
            }
            footer .sub_menu ul {
                justify-content: space-evenly;
            }


            footer .social ul {
                justify-content: space-evenly;
            }
            footer .social {
                margin: 15px 0 0 0;
            }

            footer .logo img {
                width: 270px;
                display: block;
                margin: 0 0 0 0;
            }
        }
    </style>

@endsection


