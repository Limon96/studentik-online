<section class="otziv">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <h2>{{ $data['title'] }}</h2>
            </div>
            <div class="col-12">
                <div class="otziv_sl">

                    @foreach($data['fields'] as $field)
                        <div class="item_slick">
                            <div class="wariet">
                                <div class="wrap_img">
                                    <img src="{{ $field['data']['image'] }}" alt="">
                                </div>
                                <img src="{{ asset('img/cher.png') }}" class="kavich" alt="">
                                <p>{{ $field['data']['title'] }}</p>
                                <span>{!! $field['data']['text'] !!}</span>
                            </div>
                        </div>
                    @endforeach

                </div>
            </div>

        </div>
    </div>
    <section class="my_owerf">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <div class="otzivi_g">
                        <div class="vk_o">
                            <a href="https://vk.com/topic-190166856_39806322" target="_blank">
                                <div class="img_f">
                                    <img src="/image/landing/vk1.svg">
                                </div>
                                <div class="txt">Отзывы ВК: <strong>4.8</strong></div>
                            </a>
                        </div>
                        <div class="in_o">
                            <a href="https://irecommend.ru/content/sait-studentikonline" target="_blank">
                                <div class="img_f">
                                    <img src="/image/landing/ire.webp">
                                </div>
                                <div class="txt">IRECOMMEND: <strong>5</strong></div>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</section>

