<section class="otziv">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <h3>{{ $data['title'] }}</h3>
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
</section>



<section class="moree">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <h2>Теперь вы знаете всё. <br> Разместите задание и проверьте, как работает сервис.<br> Это быстро и бесплатно:</h2>
            </div>
            <div class="col-md-12">
                <a href="#form_main" class="btn_grdarck">Разместить заказ</a>
            </div>
        </div>
    </div>
</section>
