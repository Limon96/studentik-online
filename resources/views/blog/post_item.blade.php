<div class="item_blog_side clearfix">
    <div class="img_bg clearfix">
        <a href="{{ route('blog.index', $post->slug) }}">
            <div class="baner_s" style="background-image: url('{{ thumbnail($post->image, 80) }}')"></div>
        </a>
    </div>
    <div class="info_bgh clearfix">
        <div class="title">
            <a href="{{ route('blog.index', $post->slug) }}">{{ $post->title }}</a>
        </div>
        <div>{{ $post->intro }}</div>
        <div>{{ $post->views }}</div>
    </div>
</div>
