@extends('layouts.base')

@section('title') FAQ – Часто задаваемые вопросы @endsection
@section('description') Часто задаваемые вопросы на бирже Studentik. Здесь вы найдёте ответы на возникающие вопросы. @endsection
@section('keywords')  @endsection

@section('scripts')

@endsection

@section("content")


    <div class="wrap_content">

        <div class="container">
            <div class="row">


                @include("components.left_sidebar")


                <div class="center_content_resp">
                    <div class="wrap_faq">
                        <div class="info_faq">
                            <h1 class="heading-title"> FAQ – Часто задаваемые вопросы</h1>
                            <span class="title-tag">{{ format_date($lastUpdated, 'full_datetime') }}</span>

                            @if($items->count())
                            @foreach($items as $category)
                                <h2 class="faq_h2">{{ $category->title }}</h2>
                                @foreach($category->questions as $item)
                                    <div class="faq_acc">
                                        <button class="accordion_faq clearfix">
                                            <span class="name_k">{{ $item->question }}</span>
                                            <img class="arrow_tr" src="{{ asset('catalog/assets/img/icons/arrow.svg') }}">
                                        </button>
                                        <div class="panelclim">
                                            <p class="hider_mar"></p>
                                            {!! htmlspecialchars_decode($item->answer) !!}
                                            <p class="hider_mar"></p>
                                        </div>
                                    </div>
                                @endforeach
                            @endforeach
                            @endif
                        </div>
                    </div>

                </div>

                @include("components.right_sidebar")

            </div>
        </div>
    </div>

@endsection


