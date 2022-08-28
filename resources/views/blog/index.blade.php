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
                            <div id="content-list" class="list_words">
                                <h2>Содержание</h2>
                                <ul>

                                </ul>
                            </div>
                            <div class="baner_single" style="background-image: url(../../../../catalog/assets/img/polez.svg)"></div>
                            <div class="mini_single_extention">
                                Окружающий нас мир не перестает меняться и адаптироваться под современность. Новые
                                научные открытия и изобретения делают жизнь человека проще, но за счет этого многие
                                специальности устаревают и теряют свою востребованность. Мы собрали 5 профессий, которые
                                будут актуальны еще целое десятилетие. О каждой из них подробнее читайте в нашей статье!
                            </div>

                            <div class="table_of_contents">
                                {!! $item->text !!}
                            </div>


                        </div>


                    </div>
                </div>


                @include("components.right_sidebar")


            </div>
        </div>
    </div>

@endsection


