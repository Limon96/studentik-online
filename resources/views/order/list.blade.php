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
                            <div id="order-search" class="parametrs clearfix">
                                <div class="line_search clearfix">
                                    <div class="search_in">
                                        <input type="text" name="search" value="{{ request()->search ?? '' }}">
                                        <button class="go_search"><img src="catalog/assets/img/icons/search.svg" alt="">
                                        </button>
                                    </div>

                                    <div class="clearfix balitarda">
                                        <div class="razdel_wrap">
                                            <select name="filter_section_id" class="templatingSelect2">
                                                <option value="0">Все разделы</option>
                                                @foreach($sections as $option)
                                                    @if($option->id == request()->filter_section_id)
                                                        <option value="{{ $option->id }}" selected>{{ $option->name }}</option>
                                                    @else
                                                        <option value="{{ $option->id }}">{{ $option->name }}</option>
                                                    @endif
                                                @endforeach
                                            </select>
                                        </div>

                                        <div class="razdel_wrap">
                                            @if($subjects)
                                                <select name="filter_subject_id" class="templatingSelect2">
                                                    <option value="0">Все предметы</option>
                                                    @foreach($subjects as $option)
                                                        @if($option->id == request()->filter_subject_id)
                                                            <option value="{{ $option->id }}" selected>{{ $option->name }}</option>
                                                        @else
                                                            <option value="{{ $option->id }}">{{ $option->name }}</option>
                                                        @endif
                                                    @endforeach
                                                </select>
                                            @else
                                                <select name="filter_subject_id" class="templatingSelect2" disabled>
                                                    <option value="0">Все предметы</option>
                                                </select>
                                            @endif
                                        </div>
                                        <div class="razdel_wrap">
                                            <select name="filter_work_type_id" class="templatingSelect2">
                                                <option value="0">Все типы работ</option>
                                                @foreach($work_types as $option)
                                                    @if($option->work_type_id == request()->filter_work_type_id)
                                                        <option value="{{ $option->work_type_id }}"
                                                                selected>{{ $option->name }}</option>
                                                    @else
                                                        <option
                                                            value="{{ $option->work_type_id }}">{{ $option->name }}</option>
                                                    @endif
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>


                                    <div class="clearfix cadoks">
                                        <div class="razdel_wrap_chek">
                                            <label class="container_ch">Без предложений
                                                @if(request()->filter_no_offer ?? null)
                                                    <input type="checkbox" name="filter_no_offer" value="1"
                                                           checked="checked">
                                                    <span class="checkmark"></span>
                                                @else
                                                    <input type="checkbox" name="filter_no_offer" value="1">
                                                    <span class="checkmark"></span>
                                                @endif
                                            </label>
                                        </div>
                                        <div class="razdel_wrap_chek">
                                            <label class="container_ch">Моя специализация
                                                @if(request()->filter_my_specialization ?? null)
                                                    <input type="checkbox" name="filter_my_specialization" value="1"
                                                           checked="checked">
                                                    <span class="checkmark"></span>
                                                @else
                                                    <input type="checkbox" name="filter_my_specialization" value="1">
                                                    <span class="checkmark"></span>
                                                @endif
                                            </label>
                                        </div>
                                    </div>

                                    <div class="rezult_search">
                                        <p>Найдено: <span>{{ $orders->total() }}</span> заказов</p>
                                    </div>
                                    <div class="btn_search">
                                        <button>Поиск</button>
                                    </div>

                                </div>
                            </div>
                        </div>
                        @if(isset($orders) && $orders->total() > $orders->count())
                            {{ $orders->withQueryString()->links('components.paginate', ['paginator' => $orders]) }}
                        @endif
                        <div class="wrap_results clearfix">
                            @if($orders)
                                @foreach($orders as $order)
                                    <div class="item_result clearfix">
                                        <div class="lable_dop clearfix">
                                            @if($order->hot)
                                            <div class=" wraps_warning red">
                                                <img src="{{ asset('catalog/assets/img/icons/ogony.svg') }}">
                                                <span>срочно</span>
                                            </div>
                                            @endif
                                            @if($order->premium)
                                            <div class=" wraps_warning yellow">
                                                <img src="{{ asset('catalog/assets/img/icons/screpka.svg') }}">
                                                <span>важно</span>
                                            </div>
                                            @endif
                                        </div>
                                        <div class="head">
                                            <a href="{{ route('order.show', $order->getSeoUrl()) }}"><h3>{{ $order->title }}</h3></a>
                                        </div>
                                        <div class="opis">
                                            <p>{{ $order->description }}</p>

                                            <ul class="bread clearfix">
                                                <li>{{ $order->work_type->name ?? '' }} /</li>
                                                <li>{{ $order->subject->name ?? '' }} /</li>
                                                <li>{{ $order->section->name ?? '' }}</li>
                                            </ul>


                                            <div class="logof clearfix">
                                                <div class="img_face">
                                                    <img src="{{ thumbnail($order->customer->getImage(), 80) }}" alt="{{ $order->customer->login }}">
                                                    @if($order->customer->isOnline())<span></span>@endif
                                                </div>
                                                <div class="login">
                                                    <a href="{{ route('account.profile', $order->customer->login) }}">{{ $order->customer->login }}</a>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="descript clearfix">
                                            <ul>
                                                <li>
                                                    <img src="{{ asset('catalog/assets/img/icons/papka.svg') }}">
                                                    <p>Цена: <span>{{ $order->getPrice() }}</span></p>
                                                </li>
                                                <li>
                                                    <img src="{{ asset('catalog/assets/img/icons/book_green.svg') }}">
                                                    <p>Срок сдачи: <span
                                                            class="tonk">{{ $order->getDateEnd() }}</span></p>
                                                </li>
                                            </ul>
                                            <div class="div_coner clearfix">
                                                <div class="look">
                                                    <img src="{{ asset('catalog/assets/img/icons/eye_ser.svg') }}">
                                                    <span>{{ $order->viewed }}</span>
                                                </div>
                                                <div class="answer">
                                                    <img src="{{ asset('catalog/assets/img/icons/massage_min.svg') }}">
                                                    <span>{{ $order->offers->count() }}</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            @else
                                <p class="no_results">Поиск не дал результатов</p>
                            @endif
                        </div>
                        @if(isset($orders) && $orders->total() > $orders->count())
                            {{ $orders->withQueryString()->links('components.paginate', ['paginator' => $orders]) }}
                        @endif

                    </div>
                </div>
                @include("components.right_sidebar")
            </div>
        </div>
    </div>

@endsection


