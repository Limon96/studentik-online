@if($items)
<div id="right-blog" class="clearfix worker_right">
    <h3>Новое в блоге</h3>
    @foreach($items as $item)
    <div class="item_blog_side clearfix">
        <div class="img_bg clearfix">
            <a href="{{ route('blog.index', $item->slug) }}">
                <div class="baner_s" style="background-image: url('{{ thumbnail($item->image, 80) }}')"></div>
            </a>
        </div>
        <div class="info_bgh clearfix">
            <div class="title">
                <a href="{{ route('blog.index', $item->slug) }}">{{ $item->title }}</a>
            </div>
        </div>
    </div>
    @endforeach

    <div class="foot_worker clearfix">
        <a href="{{ route('blog_category.index') }}">
            <img src="{{ asset('catalog/assets/img/icons/eye.svg') }}">
            <span>Смотреть все</span>
        </a>
    </div>
</div>
@endif
