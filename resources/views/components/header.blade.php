@if(Auth()->check())

    <header class="clearfix ">
        <div class="container-fluide">
            <div class="row">
                <div class="logo_cath">
                    <a href="/">
                        <img src="https://studentik.online/image/catalog/logoo.png" alt="Studentik.Online" class="logo">
                    </a>
                </div>
                <div class="menu_nav clearfix">
                    <button class="toogle_menu clearfix">
                        <span></span>
                        <span></span>
                        <span></span>
                    </button>
                    <ul class="clearfix menu_drest">
                        <li><a href="{{ route('order.index') }}">Лента заказов</a></li>
                        <li><a href="{{ route('blog.index') }}">Блог</a></li>
                        <li><a href="/experts">Рейтинг авторов</a></li>
                        <li><a href="/faq">FAQ</a></li>
                        <li><a href="/contacts">Поддержка</a></li>
                    </ul>

                </div>

                @include('components.account')
            </div>
        </div>
    </header>

@else

    <header class="clearfix ">
        <div class="container">
            <div class="row rowall ">
                <div class="logo_cath">
                    <a href="/">
                        <img src="https://studentik.online/image/catalog/logoo.png" alt="Studentik.Online" class="logo">
                    </a>
                </div>
                <div class="menu_nav clearfix">
                    <button class="toogle_menu clearfix">
                        <span></span>
                        <span></span>
                        <span></span>
                    </button>
                    <ul class="clearfix menu_drest">
                        <li><a href="{{ route('order.index') }}">Лента заказов</a></li>
                        <li><a href="/services">Услуги</a></li>
                        <li><a href="{{ route('blog.index') }}">Блог</a></li>
                        <li><a href="/experts">Рейтинг авторов</a></li>
                        <li><a href="{{ route('blog.index') }}">FAQ</a></li>
                    </ul>
                </div>

                @include('components.account')
            </div>
        </div>

    </header>
    <style>

        header {
            padding: 10px 0 10px 0;
            position: fixed;
            z-index: 10;
            background: #fff;
            width: 100%;
            top: 0;
            left: 0;
        }



        header .logo_cath {
            width: 20%;
            float: left;
        }

        header .logo {
            width: 100%;
            padding: 7px 0 0 0px;
            max-width: 244px;
            margin-left: auto;
            display: block;
        }



        header .menu_nav {
            width: 58%;
            float: left;
            padding: 0 0 0px 15px;
        }

        header .menu_nav ul {
            padding: 0;
            margin: 0;
            display: -webkit-flex;
            display: -ms-flex;
            display: flex;
            -webkit-flex-wrap: wrap;
            -ms-flex-wrap: wrap;
            flex-wrap: wrap;
            justify-content: space-evenly;
            -ms-align-items: center;
            align-items: center;
            list-style: none;
        }

        header .menu_nav ul li{
            float: left;
        }

        header .menu_nav ul li a {
            font-size: 16px;
            line-height: 20px;
            font-weight: 400;
            display: block;
            padding: 18px 6px 0px 6px;
            border-bottom: 1px solid transparent;
        }


        header .menu_nav ul li a:hover {
            color: #1CB7AD;
            border-bottom: 1px solid #1CB7AD;
        }







        header .search_head {
            width: 8%;
            float: left;
            padding: 8px 0 0 0;
        }

        header .search_head .search_in {
            position: relative;
        }

        header .search_head .search_in input {
            padding-right: 31px;
        }

        header .search_head .search_in .go_search {
            position: absolute;
            right: 0;
            top: 0;
            background: transparent;
            border: 0;
            padding: 7px 11px 6px 6px;
        }

        header .search_head  .search_in .go_search img{

        }






        header .btn_catcher {
            width: 15%;
            float: left;
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
            padding: 5px 0 0 0;
        }




        header .btn_catcher .prm {
            float: left;
            padding-left: 10px;
        }
        header .btn_catcher .prm a {
            font-size: 14px;
            line-height: 20px;
            text-align: center;
            color: #B2E228;
            border: 3px solid #b2e228;
            padding: 8px 7px;
            font-weight: 700;
            border-radius: 37px;

        }


        header .btn_catcher .prm a:hover{
            color: #fff;
            background:#B2E228 ;
        }




        header .btn_catcher  .msg{
            float: left;
        }
        header .btn_catcher  .msg a{

        }
        header .btn_catcher  .msg img{
            width: 38px;
            margin: 2px 0 0 0;
        }


        header .btn_catcher .cos{
            float: left;
        }
        header .btn_catcher .cos a{

        }
        header .btn_catcher .cos a img{
            width: 38px;
            margin: 2px 0 0 0;
        }


        header .coast {
            float: left;
            width: 7%;
        }
        header .coast a {
            font-size: 16px;
            line-height: 20px;
            font-weight: 700;
            padding: 16px 8px 0 0;
            display: block;
            text-align: right;
        }


        header .akk {
            float: left;
            width: 10%;
            padding-top: 6px;
        }


        header .akk a {
            display: flex;
            justify-content: center;
            align-items: center;
        }




        header .akk .avatarka {
            border-radius: 50px;
            margin: 0 6px 0 0;
            width: 40px;
        }

        header .akk .log_name {
            font-size: 14px;
            line-height: 25px;
            color: #1CB7AD;
            margin: 0 5px 0 0;
        }


        header .akk .arrow{

        }


        .akk_enter {
            float: right;
            padding: 4px 0 0 0;
            width: 22%;
        }


        .akk_enter .login{

        }

        .akk_enter .login a.in {
            background: #B2E228;
            box-shadow: 0px 4px 15px rgb(178 226 40 / 25%);
            border-radius: 25px;
            padding: 15px 24px 15px 24px;
            margin: 0 10px 0px 0;
            font-weight: 700;
            font-size: 16px;
            line-height: 20px;
            text-align: center;
            color: #FFFFFF;
            display: block;
            max-width: 90px;
            float: right;
        }

        .akk_enter .login a.in:hover{
            background: #9bc425;
        }

        .akk_enter .login a.regs{
            background: #1CB7AD;
            box-shadow: 0px 4px 15px rgba(28, 183, 173, 0.25);
            border-radius: 25px;
            padding: 15px 24px 15px 24px;
            margin: 0 0 0 0;
            font-weight: 700;
            font-size: 16px;
            line-height: 20px;
            text-align: center;
            color: #FFFFFF;
            display: block;
            max-width: 160px;
            float: right;
        }

        .akk_enter .login a.regs:hover{
            background: #168780;
        }


        header .container {
            max-width: 1200px;
        }





    </style>
@endif

