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
                    <li><a href="/orders">Лента заказов</a></li>
                    @if(!auth()->check())<li><a href="/services">Услуги</a></li>@endif
                    <li><a href="/experts">Рейтинг авторов</a></li>
                    <li><a href="/faq">FAQ</a></li>
                    <li><a href="/contacts">Поддержка</a></li>
                </ul>

            </div>
            {{-- account --}}
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
                    <li><a href="/orders">Лента заказов</a></li>
                    @if(!auth()->check())<li><a href="/services">Услуги</a></li>@endif
                    <li><a href="/experts">Рейтинг авторов</a></li>
                    <li><a href="/faq">FAQ</a></li>
                </ul>
            </div>
            {{-- account --}}
        </div>
    </div>

</header>

@endif

<script>
    $('header').load('/orders header > *');
    
</script>
