@extends('layouts.base')

@section('title'){{ $item->meta_title }}@endsection
@section('description'){{ $item->meta_description }}@endsection
@section('keywords'){{ $item->meta_keywords }}@endsection

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
    <div class="wrap_content">

        <div class="container">
            <div class="row">

                @include("components.left_sidebar")

                <div class="center_content_resp">
                    <div class="clearfix console_wrap">

                        <div class="content_single">

                            <div class="heads">
                                <h1 class="blog_title">{{ $item->title }}</h1>
                                <ul class="breadcrumb clearfix">
                                    <li><a href="https://studentik.online/"><i
                                                class="fa fa-home"></i></a></li>
                                    <li><a href="{{ route('blog_category.index') }}">Блог</a></li>

                                    @foreach($categoryPath as $category)
                                        <li><a href="{{ route('blog_category.index', $category->slug) }}">{{ $category->title }}</a></li>
                                    @endforeach
                                    @if($item)
                                        <li><a href="{{ route('blog_category.index', $item->slug) }}">{{ $item->title }}</a></li>
                                    @endif
                                </ul>
                            </div>

                            <div id="content-list" class="list_words">
                                <h2 style="margin-top: 10px">Содержание</h2>
                                <ul id="links_yakor">

                                </ul>
                            </div>
                         {{--   <div class="baner_single" style="background-image: url(../../../../catalog/assets/img/polez.svg)"></div>--}}
                            <div class="mini_single_extention">
                                {!! nl2br($item->intro) !!}
                            </div>

                            <div class="table_of_contents">
                                {!! $item->text !!}
                            </div>

                            <div class="date_publick">
                                <div class="slk">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 20 20" fill="none">
                                        <path d="M15 2H19C19.2652 2 19.5196 2.10536 19.7071 2.29289C19.8946 2.48043 20 2.73478 20 3V19C20 19.2652 19.8946 19.5196 19.7071 19.7071C19.5196 19.8946 19.2652 20 19 20H1C0.734784 20 0.48043 19.8946 0.292893 19.7071C0.105357 19.5196 0 19.2652 0 19V3C0 2.73478 0.105357 2.48043 0.292893 2.29289C0.48043 2.10536 0.734784 2 1 2H5V0H7V2H13V0H15V2ZM13 4H7V6H5V4H2V8H18V4H15V6H13V4ZM18 10H2V18H18V10Z" fill="#1CB7AD"/>
                                    </svg>
                                    <div class="fert33">
                                        {{ format_date($item->publish_at, 'full_datetime') }}
                                    </div>
                                </div>
                                <div class="kto_look">
                                    <svg data-v-b0e85714="" viewBox="0 0 576 512" xmlns="http://www.w3.org/2000/svg" class="sw-icon info-counter__icon">
                                        <path fill="#1CB7AD" d="M288 144a110.94 110.94 0 0 0-31.24 5 55.4 55.4 0 0 1 7.24 27 56 56 0 0 1-56 56 55.4 55.4 0 0 1-27-7.24A111.71 111.71 0 1 0 288 144zm284.52 97.4C518.29 135.59 410.93 64 288 64S57.68 135.64 3.48 241.41a32.35 32.35 0 0 0 0 29.19C57.71 376.41 165.07 448 288 448s230.32-71.64 284.52-177.41a32.35 32.35 0 0 0 0-29.19zM288 400c-98.65 0-189.09-55-237.93-144C98.91 167 189.34 112 288 112s189.09 55 237.93 144C477.1 345 386.66 400 288 400z" ></path>
                                    </svg>
                                    <div class="asd">
                                        {{ $item->views }}
                                    </div>
                                </div>
                            </div>

                            @if($popularPosts->count())
                                <div class="stipe_work_list clearfix">

                                    <h2 class="h2_article">Интересные статьи </h2>
                                    <div class="list_article">
                                        @foreach($popularPosts as $post)
                                            @include('blog.post_item')
                                        @endforeach
                                    </div>
                                    <a href="{{ route('blog_category.index') }}" class="all_loock">Смотреть все</a>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
                @include("components.right_sidebar")
            </div>
        </div>
    </div>


    <script>


        $(document).ready(function(){
            $("#links_yakor").on("click","a", function (event) {
                //отменяем стандартную обработку нажатия по ссылке
                event.preventDefault();

                //забираем идентификатор бока с атрибута href
                var id  = $(this).attr('href'),

                    //узнаем высоту от начала страницы до блока на который ссылается якорь
                    top = $(id).offset().top - 75;

                //анимируем переход на расстояние - top за 1500 мс
                $('body,html').animate({scrollTop: top}, 500);
            });
        });

    </script>

@endsection


