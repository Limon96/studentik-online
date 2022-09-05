<div class="sh-sideleft-menu">
    <label class="sh-sidebar-label">Навигация</label>
    <ul class="nav">
        <li class="nav-item">
            <a href="{{ route('admin.dashboard') }}" class="nav-link @if(Route::is('admin.dashboard')) active @endif">
                <i class="icon ion-ios-home-outline"></i>
                <span>Панель управления</span>
            </a>
        </li><!-- nav-item -->
        <li class="nav-item">
            <a href="{{ route('admin.landing.index') }}" class="nav-link @if(Route::is('admin.landing.index')) active @endif">
                <i class="icon ion-ios-bookmarks-outline"></i>
                <span>Страницы</span>
            </a>
        </li><!-- nav-item -->


        <li class="nav-item">
            <a href="#" class="nav-link with-sub">
                <i class="icon ion-ios-paper-outline"></i>
                <span>Блог</span>
            </a>
            <ul class="nav-sub">
                @if(\Illuminate\Support\Facades\Route::has('admin.blog_category.index'))
                    <li class="nav-item"><a href="{{ route('admin.blog_category.index') }}" class="nav-link @if(Route::is('admin.blog_category.index')) active @endif">Категории</a></li>
                @endif
                @if(\Illuminate\Support\Facades\Route::has('admin.blog.index'))
                    <li class="nav-item"><a href="{{ route('admin.blog.index') }}" class="nav-link @if(Route::is('admin.blog.index')) active @endif">Статьи</a></li>
                @endif
            </ul>
        </li>
        <li class="nav-item">
            <a href="#" class="nav-link with-sub">
                <i class="icon ion-ios-help-outline"></i>
                <span>FAQ</span>
            </a>
            <ul class="nav-sub">
                @if(\Illuminate\Support\Facades\Route::has('admin.faq_category.index'))
                    <li class="nav-item"><a href="{{ route('admin.faq_category.index') }}" class="nav-link @if(Route::is('admin.faq_category.index')) active @endif">Категории</a></li>
                @endif
                @if(\Illuminate\Support\Facades\Route::has('admin.faq.index'))
                    <li class="nav-item"><a href="{{ route('admin.faq.index') }}" class="nav-link @if(Route::is('admin.faq.index')) active @endif">Вопросы</a></li>
                @endif
            </ul>
        </li>
    </ul>
</div>
