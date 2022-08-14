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
            <a href="{{ route('admin.blog.index') }}" class="nav-link @if(Route::is('admin.blog.index')) active @endif">
                <i class="icon ion-ios-paper-outline"></i>
                <span>Блог</span>
            </a>
        </li><!-- nav-item -->
    </ul>
</div>
