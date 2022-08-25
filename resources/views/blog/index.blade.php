@extends('layouts.base')

{{--статья--}}




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


                        <div class="content_single">
                            <div class="list_words">
                                <h2>Содержание</h2>
                                <ul>
                                    <li><a href="id1">1. Содержание ссылка 1</a></li>
                                    <li><a href="id2">2. Содержание ссылка 2</a></li>
                                    <li><a href="id3">3. Содержание ссылка 3</a></li>
                                    <li><a href="id4">4. Содержание ссылка 4</a></li>
                                    <li><a href="id5">5. Содержание ссылка 5</a></li>
                                </ul>
                            </div>
                            <div class="baner_single"
                                 style="background-image: url(../../../../catalog/assets/img/polez.svg)">

                            </div>
                            <div class="mini_single_extention">
                                Окружающий нас мир не перестает меняться и адаптироваться под современность. Новые
                                научные открытия и изобретения делают жизнь человека проще, но за счет этого многие
                                специальности устаревают и теряют свою востребованность. Мы собрали 5 профессий, которые
                                будут актуальны еще целое десятилетие. О каждой из них подробнее читайте в нашей статье!
                            </div>

                            <div class="table_of_contents">
                                <div id="id1" class="title_by">Содержание ссылка 1</div>
                                Lorem ipsum dolor sit amet, consectetur adipisicing elit. Cum dolores error iste natus
                                quas sit tempore, tenetur unde! Accusantium alias delectus dignissimos ea ipsa laborum
                                magni omnis totam ullam voluptatem?
                                Lorem ipsum dolor sit amet, consectetur adipisicing elit. Cum dolores error iste natus
                                quas sit tempore, tenetur unde! Accusantium alias delectus dignissimos ea ipsa laborum
                                magni omnis totam ullam voluptatem?
                                Lorem ipsum dolor sit amet, consectetur adipisicing elit. Cum dolores error iste natus
                                quas sit tempore, tenetur unde! Accusantium alias delectus dignissimos ea ipsa laborum
                                magni omnis totam ullam voluptatem?

                            </div>


                        </div>


                    </div>
                </div>


                @include("components.right_sidebar")


            </div>
        </div>
    </div>

@endsection


