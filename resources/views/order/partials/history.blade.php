<div class="change_history">

    <button class="accordion_history clearfix">
        <span class="name_k">История изменений</span>
        <img class="arrow_tr" src="{{ asset('catalog/assets/img/icons/arrow.svg') }}">
    </button>
    <div class="panel_climd">
        <div class="change_wrap clearfix">

            @if($order->history)
                @foreach($order->history as $history)
                    <div class="item_history">
                        <span class="date_root">{{ format_date($history->date_added, 'd.m.Y H:i') }}</span>
                        <p class="wincent">{!! $history->render() !!}</p>
                    </div>
                @endforeach
           @endif

        </div>
    </div>

</div>
