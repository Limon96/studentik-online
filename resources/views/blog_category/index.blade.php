@extends('layouts.base')

@section('title'){{ $item->meta_title ?? 'Полезные материалы' }}@endsection
@section('description'){{ $item->meta_description ?? 'Полезные материалы от Studentik' }}@endsection
@section('keywords'){{ $item->meta_keywords ?? 'блог, полезные материалы, studentik' }}@endsection


@section("content")


    <div class="wrap_content">

        <div class="container">
            <div class="row">


                @include("components.left_sidebar")


                <div class="center_content_resp">
                    <div class="clearfix console_wrap">
                        <div class="clearfix search_panel">
                            <div class="heads">
                                <h2>Полезные материалы</h2>
                                <ul class="breadcrumb clearfix">
                                    <li><a href="https://studentik.online/"><i
                                                class="fa fa-home"></i></a></li>
                                    <li><a href="https://studentik.online/index.php?route=account/account">Профиль</a>
                                    </li>
                                    <li><a href="{{ route('blog_category.index') }}">Блог</a></li>
                                    @if($item)
                                        <li><a href="{{ route('blog_category.index', $item->slug) }}">{{ $item->title }}</a></li>
                                    @endif
                                </ul>
                            </div>

                            <div class="blog_wrap clearfix">


                                <div class="baner_blog"
                                     style="background-image: url(../../../../catalog/assets/img/polez.svg)">

                                </div>
                                <div class="blog_description">
                                    Студенчество — это не только лекции, сессии и курсовые, но и
                                    жизнь в общежитии, стипендия, конкурсы, стремление найти себя.
                                    Здесь вы найдете ответы на самые популярные вопросы о бытовой
                                    стороне обучения: какие скидки положены студенту, как сдать сессию,
                                    если ничего не учил и многое другое.
                                </div>

                                @if($blogCategories)
                                    @include('blog_category.components.category_block')
                                @endif


                            </div>


                        </div>


                        <div class="stipe_work_list clearfix">
                            <h2 class="h2_article">Статьи</h2>

                            <div class="list_article">

                                @foreach($blogPosts as $blogPost)
                                <div class="article">
                                    <div class="left_fon">
                                        <a href="{{ route('blog.index', $blogPost->slug) }}">
                                            <div class="img_r"
                                                 style="background-image: url('{{ thumbnail($blogPost->image, 180) }}')"></div>
                                        </a>
                                    </div>
                                    <div class="rigt_content">
                                        <div class="hesde">
                                            <a href="{{ route('blog.index', $blogPost->slug) }}">
                                                <div class="title">{{ $blogPost->title }}</div>
                                            </a>
                                            <div class="extention">
                                                {{ $blogPost->intro }}
                                            </div>
                                        </div>
                                        <div class="look_date">
                                            <div class="look">
                                                <svg data-v-b0e85714="" viewBox="0 0 576 512"
                                                     xmlns="http://www.w3.org/2000/svg"
                                                     class="sw-icon info-counter__icon">
                                                    <path fill="#456"
                                                          d="M288 144a110.94 110.94 0 0 0-31.24 5 55.4 55.4 0 0 1 7.24 27 56 56 0 0 1-56 56 55.4 55.4 0 0 1-27-7.24A111.71 111.71 0 1 0 288 144zm284.52 97.4C518.29 135.59 410.93 64 288 64S57.68 135.64 3.48 241.41a32.35 32.35 0 0 0 0 29.19C57.71 376.41 165.07 448 288 448s230.32-71.64 284.52-177.41a32.35 32.35 0 0 0 0-29.19zM288 400c-98.65 0-189.09-55-237.93-144C98.91 167 189.34 112 288 112s189.09 55 237.93 144C477.1 345 386.66 400 288 400z"></path>
                                                </svg>
                                                <span>{{ $blogPost->views }}</span>
                                            </div>
                                            <div class="date">{{ date('d.m.Y', strtotime($blogPost->publish_at)) }}</div>
                                        </div>
                                    </div>
                                </div>
                                @endforeach

                            </div>


                        </div>


                    </div>
                </div>
            </div>


            @include("components.right_sidebar")


        </div>
    </div>
    </div>

@endsection

