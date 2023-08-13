@extends('layouts.base')

@section('title')
    Лента заказов
@endsection
@section('description')
    Все заказы по гуманитарным, экономическим, техническим и другим дисциплинам. Воспользуйтесь фильтром для поиска подходящего заказа.
@endsection
@section('keywords')@endsection

@section('scripts')
    <script src="{{ asset('catalog/assets/js/custom.js') }}"></script>
    <script src="{{ asset('catalog/assets/js/offer.js') }}"></script>
    <script>
        $(document).ready(function () {

            var $headers = $('.table_of_contents h2, .table_of_contents h3');

            if ($headers.length < 1) {
                $('#content-list').remove();
                return;
            }

            $headers.each(function (i, e) {
                var tagName = $(e).prop("tagName");
                var nav_id = tagName.toLowerCase() + '-' + i;
                var name = (tagName === 'H3' ? "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;" : '') + $(e).text();

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
                        <div class="clearfix search_panel">
                            <div class="heads">
                                <h1>@yield('title')</h1>
                                <ul class="breadcrumb clearfix">
                                    <li><a href="https://studentik.online/"><i class="fa fa-home"></i></a></li>
                                    <li>@yield('title')</li>
                                </ul>
                            </div>
                            <div class="search_wrap">
                                <div class="upravlen_search">
                                    <form id="page-search" action="{{ route('search.index') }}" method="get">
                                        <div class="heads_search clearfix">
                                            <input type="text" class="search_palez" name="search" value="{{ old('search', request('search', '')) }}">
                                            <button type="submit" class="good_searc">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="21" height="21"
                                                     viewBox="0 0 21 21" fill="none">
                                                    <path d="M16.031 14.617L20.314 18.899L18.899 20.314L14.617 16.031C13.0237 17.3082 11.042 18.0029 9 18C4.032 18 0 13.968 0 9C0 4.032 4.032 0 9 0C13.968 0 18 4.032 18 9C18.0029 11.042 17.3082 13.0237 16.031 14.617ZM14.025 13.875C15.2941 12.5699 16.0029 10.8204 16 9C16 5.132 12.867 2 9 2C5.132 2 2 5.132 2 9C2 12.867 5.132 16 9 16C10.8204 16.0029 12.5699 15.2941 13.875 14.025L14.025 13.875Z"
                                                          fill="#999999"/>
                                                </svg>
                                            </button>
                                        </div>
                                        <div class="variant_search clearfix">
                                            <label class="container_ch"> Аккаунты
                                                <input type="checkbox" name="search_customer" value="1" @if(old('search_customer', request('search_customer', 0)) == 1) checked @endif>
                                                <span class="checkmark"></span>
                                            </label>
                                            <label class="container_ch"> Заказы
                                                <input type="checkbox" name="search_order" value="1" @if(old('search_order', request('search_order', 0)) == 1) checked @endif>
                                                <span class="checkmark"></span>
                                            </label>
                                        </div>
                                    </form>
                                </div>

                                <div class="sadsa">
                                    <div class="itemsssss"></div>
                                    <div class="itemsssss"></div>
                                    <div class="itemsssss"></div>
                                    <div class="itemsssss"></div>
                                    <div class="itemsssss"></div>
                                    <div class="itemsssss"></div>
                                    <div class="itemsssss"></div>
                                    <div class="itemsssss"></div>
                                    <div class="itemsssss"></div>
                                    <div class="itemsssss"></div>
                                    <div class="itemsssss"></div>
                                    <div class="itemsssss"></div>
                                    <div class="itemsssss"></div>
                                </div>

                                <div class="results clearfix">
                                    @if($results)
                                    <div class="page-{{ $results->currentPage() }}">

                                        @foreach($results as $item)
                                            @if($item->type == 'customer')
                                                @include('search.types.customer')
                                            @elseif($item->type == 'order')
                                                @include('search.types.order')
                                            @endif
                                        @endforeach
                                        <button class="show_more" onclick="loadMore({{ $results->currentPage() + 1 }}, this);">Показать ещё</button>


                                        @if(isset($results) && $results->total() > $results->count())
                                            {{ $results->withQueryString()->links('components.paginate', ['paginator' => $results]) }}
                                        @endif
                                    </div>
                                    @else
                                    Поиск не дал результатов
                                    @endif
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


