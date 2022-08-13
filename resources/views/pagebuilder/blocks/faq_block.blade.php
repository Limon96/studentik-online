<section class="questions">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <h3>{{ $data['title'] }}</h3>
            </div>
            <div class="col-12">
                @foreach($data['fields'] as $field)
                <div class="faq_accd">
                    <button class="ac_faq clearfix">
                        <span class="name_k">{{ $field['data']['title'] }}</span>
                        <img class="arrow_tr" src="{{ asset('img/arow_down.svg') }}" alt="">
                    </button>
                    <div class="panelclim22">
                        <div class="cropke clearfix">
                            {!! $field['data']['text'] !!}
                        </div>
                    </div>
                </div>
                @endforeach

            </div>
            <div class="col-12">

                <div class="goblin">
                    <div class="one_rep">
                        <a href="#form_main" class="btn_grdarck">Разместить заказ</a>
                    </div>
                    <div class="two_rep">
                        <span>ИЛИ</span>
                    </div>
                    <div class="tre_rep">
                        <a href="https://studentik.online/index.php?route=support/faq" class="btn_gr">У меня другой вопрос</a>
                    </div>
                </div>

            </div>
        </div>
    </div>
</section>
