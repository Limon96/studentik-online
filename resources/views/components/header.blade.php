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
                        <li><a href="/contacts">Поддержка</a></li>
                    </ul>
                </div>

                @include('components.account')
            </div>
        </div>

    </header>

@endif

