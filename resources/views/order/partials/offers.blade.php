@if($order->isOwner() or auth()->user()->isAuthor() or auth()->user()->isAdmin())
    <div class="unswer">
        <h3>Ответы</h3>
        <div class="wrap_ofers">
            @if($item->offers)
                @foreach($item->offers as $offer)
                    <div class="item_unswer clearfix @if($offer->assigned) assigned @endif">
                        <div class="lefter clearfix">
                            <div class="item_worker clearfix">
                                @include('order.partials.customer', ['customer' => $offer->customer])
                            </div>
                        </div>
                        <div class="righter clearfix">
                            @if($offer->isOwner())
                            @if($offer->earned)
                            <div class="amount">
                                <img src="{{ asset('catalog/assets/img/icons/eye_ser.svg') }}">
                                <span>{{ $offer->earned }}</span>
                            </div>
                            @endif
                            @endif
                            <div class="amount">
                                <img src="{{ asset('catalog/assets/img/icons/eye_ser.svg') }}">
                                <span>{{ $offer->bet }}</span>
                            </div>
                        </div>
                        <div class="unswer_text">
                            <p>{{ $offer->text }}</p>
                        </div>
                        <div class="btn_uprava clearfix">
                            {% if is_owner or offer.is_owner or auth()->user()->isAdmin() %}<a class="send_wopr" data-offer_id="{{ $offer->offer_id }}">Обсудить заказ</a>{% endif %}
                            {% if is_owner %}<a href="offer.chat }}" class="go_chat">Перейти в чат</a>{% endif %}
                            {% if is_owner %}{% if order_status_id == config_open_order_status_id %}<a class="go_worck assign_offer" data-order_id="{{ $offer->order_id }}" data-offer_id="{{ $offer->offer_id }}">Выбрать исполнителем</a>{% endif %}{% endif %}
                            {% if order_status_id == config_pending_order_status_id and offer.is_owner and offer.assigned %}<a href="#" id="accept_offer" class="accepted" data-order_id="{{ $offer->order_id }}" data-offer_id="{{ $offer->offer_id }}">Принять</a>{% endif %}
                            {% if order_status_id == config_pending_order_status_id and (offer.is_owner or is_owner) and offer.assigned %}<a href="#" class="blocked cancel_offer" data-order_id="{{ $offer->order_id }}" data-offer_id="{{ $offer->offer_id }}">Отклонить предложение</a>{% endif %}
                            {% if order_status_id == config_open_order_status_id and offer.is_owner %}<a class="edit_my_ofer" data-order_id="{{ $offer->order_id }}" data-offer_id="{{ $offer->offer_id }}">Редактировать</a>{% endif %}
                        </div>
                        @if(auth()->check() and ($order->isOwner() or $offer->isOwner() or auth()->user()->isAdmin()))
                        <div id="message-form-unswer-offer{{ $offer->offer_id }}" class="unswer_com footer_chat clearfix">
                            <div id="smallChat{{ $offer->offer_id }}" class="chat_pole content_chat"></div>
                            <div class="text_gg clearfix">
                                <div class="text_wrap clearfix">
                                    <textarea name="text" placeholder="Введите ваше сообщение"></textarea>
                                </div>
                                <div class="btn_send clearfix">
                                    <button data-offer_send="{{ $offer->offer_id }}">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 20 20" fill="none">
                                            <path d="M8 0H12C14.1217 0 16.1566 0.842855 17.6569 2.34315C19.1571 3.84344 20 5.87827 20 8C20 10.1217 19.1571 12.1566 17.6569 13.6569C16.1566 15.1571 14.1217 16 12 16V19.5C7 17.5 0 14.5 0 8C0 5.87827 0.842855 3.84344 2.34315 2.34315C3.84344 0.842855 5.87827 0 8 0ZM10 14H12C12.7879 14 13.5681 13.8448 14.2961 13.5433C15.0241 13.2417 15.6855 12.7998 16.2426 12.2426C16.7998 11.6855 17.2417 11.0241 17.5433 10.2961C17.8448 9.56815 18 8.78793 18 8C18 7.21207 17.8448 6.43185 17.5433 5.7039C17.2417 4.97595 16.7998 4.31451 16.2426 3.75736C15.6855 3.20021 15.0241 2.75825 14.2961 2.45672C13.5681 2.15519 12.7879 2 12 2H8C6.4087 2 4.88258 2.63214 3.75736 3.75736C2.63214 4.88258 2 6.4087 2 8C2 11.61 4.462 13.966 10 16.48V14Z" fill="#999999"></path>
                                        </svg>
                                    </button>
                                </div>
                            </div>
                        </div>
                        @endif
                    </div>
                @endforeach
            @else
                @if(auth()->check() and auth()->user()->isAuthor())
                <p>На это задание вы не откликнулись</p>
                @else
                <p>Ожидайте откликов от экспертов</p>
                @endif
            @endif
        </div>
    </div>
@endif
@if(auth()->check() and auth()->user()->isAuthor())
    <div id="offer" class="my_unswer">
        <h3>Моё предложение</h3>
        {% if exist_offer %}
        <div class="block_my_u clearfix">
            Вы уже ответили на заказ
        </div>
        {% else %}
        <div class="block_offer_form clearfix">
            <div class="block_my_u clearfix">
                <input type="hidden" name="order_id" value="{{ $item->order_id }}">
                <textarea name="text" id="textunsver" placeholder="Комментарий к заказу без ставки (Необязательное поле)"></textarea>
            </div>
            <div class="wrap_paranetrs clearfix">
                <div class="lop_lert">
                    <label for="c1">Ваша ставка</label>
                    <input type="number" name="bet" placeholder="0" id="c1" >
                    <span class="symbol">
                                    <svg data-v-597fc010="" viewBox="0 0 384 512" xmlns="http://www.w3.org/2000/svg" class="sw-icon" data-v-79b0c030=""><path d="M243.128 314.38C324.987 314.38 384 257.269 384 172.238S324.987 32 243.128 32H76c-6.627 0-12 5.373-12 12v215.807H12c-6.627 0-12 5.373-12 12v30.572c0 6.627 5.373 12 12 12h52V352H12c-6.627 0-12 5.373-12 12v24c0 6.627 5.373 12 12 12h52v68c0 6.627 5.373 12 12 12h40c6.627 0 12-5.373 12-12v-68h180c6.627 0 12-5.373 12-12v-24c0-6.627-5.373-12-12-12H128v-37.62h115.128zM128 86.572h105.61c53.303 0 86.301 31.728 86.301 85.666 0 53.938-32.998 87.569-86.935 87.569H128V86.572z"></path></svg>
                                </span>
                </div>
                <div class="lop_lert">
                    <label for="c2">Вы получаете</label>
                    <input type="number" name="earned" placeholder="0" id="c2">
                    <span class="symbol">
                                    <svg data-v-597fc010="" viewBox="0 0 384 512" xmlns="http://www.w3.org/2000/svg" class="sw-icon" data-v-79b0c030=""><path d="M243.128 314.38C324.987 314.38 384 257.269 384 172.238S324.987 32 243.128 32H76c-6.627 0-12 5.373-12 12v215.807H12c-6.627 0-12 5.373-12 12v30.572c0 6.627 5.373 12 12 12h52V352H12c-6.627 0-12 5.373-12 12v24c0 6.627 5.373 12 12 12h52v68c0 6.627 5.373 12 12 12h40c6.627 0 12-5.373 12-12v-68h180c6.627 0 12-5.373 12-12v-24c0-6.627-5.373-12-12-12H128v-37.62h115.128zM128 86.572h105.61c53.303 0 86.301 31.728 86.301 85.666 0 53.938-32.998 87.569-86.935 87.569H128V86.572z"></path></svg>
                                </span>
                </div>
                <div class="lop_lert">
                    <button class="add">Отправить</button>
                </div>
            </div>
        </div>
        {% endif %}
    </div>
@endif
