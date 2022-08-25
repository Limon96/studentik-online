@extends('layouts.base')

{{--статья--}}




@section("content")


    <div class="wrap_content">

        <div class="container">
            <div class="row">


                @include("components.left_sidebar")


                <div class="center_content_resp">
                    <div class="clearfix console_wrap">



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


