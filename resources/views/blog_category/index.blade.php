@extends('layouts.base')


{{--категории--}}


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
                                    <li><a href="https://studentik.online/index.php?route=common/home"><i
                                                class="fa fa-home"></i></a></li>
                                    <li><a href="https://studentik.online/index.php?route=account/account">Профиль</a>
                                    </li>
                                    <li><a href="https://studentik.online/my-orders">Блог</a></li>
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

                                <div class="kat_wrap">
                                    <h2>Категории</h2>
                                    <div class="list_category_blog">
                                        <div class="item">
                                            <a href="#/">
                                                <div class="img_svg">
                                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 40 40"
                                                         width="40px" height="40px">
                                                        <path fill="#b6dcfe"
                                                              d="M1.5 35.5L1.5 4.5 11.793 4.5 14.793 7.5 35.5 7.5 35.5 35.5z"/>
                                                        <path fill="#4788c7"
                                                              d="M11.586,5l2.707,2.707L14.586,8H15h20v27H2V5H11.586 M12,4H1v32h35V7H15L12,4L12,4z"/>
                                                        <g>
                                                            <path fill="#dff0fe"
                                                                  d="M1.599 35.5L5.417 14.5 16.151 14.5 19.151 12.5 39.41 12.5 35.577 35.5z"/>
                                                            <path fill="#4788c7"
                                                                  d="M38.82,13l-3.667,22H2.198l3.636-20H16h0.303l0.252-0.168L19.303,13H38.82 M40,12H19l-3,2H5L1,36 h35L40,12L40,12z"/>
                                                        </g>
                                                    </svg>
                                                </div>
                                                <div class="text">Акции</div>
                                                <div class="count">8</div>
                                            </a>
                                        </div>
                                        <div class="item">
                                            <a href="#/">
                                                <div class="img_svg">
                                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 40 40"
                                                         width="40px" height="40px">
                                                        <path fill="#b6dcfe"
                                                              d="M1.5 35.5L1.5 4.5 11.793 4.5 14.793 7.5 35.5 7.5 35.5 35.5z"/>
                                                        <path fill="#4788c7"
                                                              d="M11.586,5l2.707,2.707L14.586,8H15h20v27H2V5H11.586 M12,4H1v32h35V7H15L12,4L12,4z"/>
                                                        <g>
                                                            <path fill="#dff0fe"
                                                                  d="M1.599 35.5L5.417 14.5 16.151 14.5 19.151 12.5 39.41 12.5 35.577 35.5z"/>
                                                            <path fill="#4788c7"
                                                                  d="M38.82,13l-3.667,22H2.198l3.636-20H16h0.303l0.252-0.168L19.303,13H38.82 M40,12H19l-3,2H5L1,36 h35L40,12L40,12z"/>
                                                        </g>
                                                    </svg>
                                                </div>
                                                <div class="text">ЕГЭ</div>
                                                <div class="count">28</div>
                                            </a>
                                        </div>
                                        <div class="item">
                                            <a href="#/">
                                                <div class="img_svg">
                                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 40 40"
                                                         width="40px" height="40px">
                                                        <path fill="#b6dcfe"
                                                              d="M1.5 35.5L1.5 4.5 11.793 4.5 14.793 7.5 35.5 7.5 35.5 35.5z"/>
                                                        <path fill="#4788c7"
                                                              d="M11.586,5l2.707,2.707L14.586,8H15h20v27H2V5H11.586 M12,4H1v32h35V7H15L12,4L12,4z"/>
                                                        <g>
                                                            <path fill="#dff0fe"
                                                                  d="M1.599 35.5L5.417 14.5 16.151 14.5 19.151 12.5 39.41 12.5 35.577 35.5z"/>
                                                            <path fill="#4788c7"
                                                                  d="M38.82,13l-3.667,22H2.198l3.636-20H16h0.303l0.252-0.168L19.303,13H38.82 M40,12H19l-3,2H5L1,36 h35L40,12L40,12z"/>
                                                        </g>
                                                    </svg>
                                                </div>
                                                <div class="text">Всё о великих</div>
                                                <div class="count">128</div>
                                            </a>
                                        </div>
                                        <div class="item">
                                            <a href="#/">
                                                <div class="img_svg">
                                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 40 40"
                                                         width="40px" height="40px">
                                                        <path fill="#b6dcfe"
                                                              d="M1.5 35.5L1.5 4.5 11.793 4.5 14.793 7.5 35.5 7.5 35.5 35.5z"/>
                                                        <path fill="#4788c7"
                                                              d="M11.586,5l2.707,2.707L14.586,8H15h20v27H2V5H11.586 M12,4H1v32h35V7H15L12,4L12,4z"/>
                                                        <g>
                                                            <path fill="#dff0fe"
                                                                  d="M1.599 35.5L5.417 14.5 16.151 14.5 19.151 12.5 39.41 12.5 35.577 35.5z"/>
                                                            <path fill="#4788c7"
                                                                  d="M38.82,13l-3.667,22H2.198l3.636-20H16h0.303l0.252-0.168L19.303,13H38.82 M40,12H19l-3,2H5L1,36 h35L40,12L40,12z"/>
                                                        </g>
                                                    </svg>
                                                </div>
                                                <div class="text">Поступление в ВУЗ</div>
                                                <div class="count">48</div>
                                            </a>
                                        </div>
                                    </div>
                                </div>


                            </div>


                        </div>


                        <div class="stipe_work_list clearfix">
                            <h2 class="h2_article">Статьи</h2>

                            <div class="list_article">
                                <div class="article">
                                    <div class="left_fon">
                                        <a href="#">
                                            <div class="img_r"
                                                 style="background-image: url(../../../../catalog/assets/img/polez.svg)"></div>
                                        </a>
                                    </div>
                                    <div class="rigt_content">
                                        <div class="hesde">
                                            <a href="#/">
                                                <div class="title">5 профессий, актуальных через 10 лет</div>
                                            </a>
                                            <div class="extention">
                                                Lorem ipsum dolor sit amet, consectetur adipisicing elit.
                                                A est et in inventore nihil! Dolorum exercitationem modi nihil,
                                                omnis perferendis unde voluptatum...
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
                                                <span>26</span>
                                            </div>
                                            <div class="date">22 мая 2022</div>
                                        </div>
                                    </div>
                                </div>
                                <div class="article">
                                    <div class="left_fon">
                                        <a href="#">
                                            <div class="img_r"
                                                 style="background-image: url(../../../../catalog/assets/img/polez.svg)"></div>
                                        </a>
                                    </div>
                                    <div class="rigt_content">
                                        <div class="hesde">
                                            <a href="#/">
                                                <div class="title">5 профессий, актуальных через 10 лет</div>
                                            </a>
                                            <div class="extention">
                                                Lorem ipsum dolor sit amet, consectetur adipisicing elit.
                                                A est et in inventore nihil! Dolorum exercitationem modi nihil,
                                                omnis perferendis unde voluptatum...
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
                                                <span>26</span>
                                            </div>
                                            <div class="date">22 мая 2022</div>
                                        </div>
                                    </div>
                                </div>
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

