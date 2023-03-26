<div class="lst_of_us">
    <div class="in_last">
        @foreach($lastOrders as $item)
        <div class="item">
            <div class="name"><a href="/order/{{ $item->getSlug() }}">{{ $item->title }}</a></div>
            <div class="param">{{ $item->work_type->name }}, {{ mb_strtolower($item->subject->name) }}</div>
            <div class="grops">
                <div class="date">
                    <div class="svg">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="48px" height="48px">
                            <path fill="#1CB7AD" d="M12,2C6.477,2,2,6.477,2,12c0,5.523,4.477,10,10,10s10-4.477,10-10C22,6.477,17.523,2,12,2z M14.586,16l-3.293-3.293 C11.105,12.519,11,12.265,11,12V7c0-0.552,0.448-1,1-1h0c0.552,0,1,0.448,1,1v4.586l3,3c0.39,0.39,0.39,1.024,0,1.414l0,0 C15.61,16.39,14.976,16.39,14.586,16z"/>
                        </svg>
                    </div>
                    @if($item->completed_at)
                        <div class="txt">Завершён {{ format_date($item->completed_at, 'dMt') }}</div>
                    @elseif($item->date_end != '0000-00-00')
                        <div class="txt">Завершён {{ format_date($item->date_end, 'dM') }}</div>
                    @else
                        <div class="txt">Завершён {{ format_date($item->date_modified, 'dMt') }}</div>
                    @endif
                </div>
                <div class="money">
                    <div class="clue">
                        Стоимость выполнения
                    </div>
                    <div class="svg">
                        <img src="../image/landing/ruble.png" alt="">
                    </div>
                    <div class="txt">{{ $item->offerAssigned->bet }}р</div>
                </div>
                <div class="uspeh">
                    <div class="clue">
                        Уникальность работы
                    </div>
                    <div class="svg">
                        <img src="../image/landing/shield.png" alt="">
                    </div>
                    @if($item->plagiarism_check_id)
                        <div class="txt">{{ $item->plagiarism_check->name }}</div>
                    @else
                        <div class="txt">75%</div>
                    @endif
                </div>
            </div>
        </div>
        @endforeach
    </div>
</div>
