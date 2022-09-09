@if($customers)
<div class="clearfix worker_right">
    <h3>Топ экспертов за неделю</h3>
    @foreach($customers as $customer)
    <div class="item_worker clearfix">
        <div class="img_face clearfix">
            <a href="/user/{{ $customer->login }}"><img src="{{ thumbnail($customer->getImage(), 80) }}" alt="{{ $customer->login }}"></a>
        </div>
        <div class="info_worker clearfix">
            <div class="lit_hed clearfix">
                <a href="/user/{{ $customer->login }}">{{ $customer->login }}</a>
            </div>
            <div class="lit_foot clearfix">
                <div class="enter clearfix">
                    <span class="name">Рейтинг:</span>
                    <span class="rating">{{ $customer->rating()->sum('rating') }} @if($customer->new_rating)<span class="bonus">+{{ $customer->new_rating }}</span>@endif</span>
                </div>
                <div class="enter_like clearfix">
                    <img src="{{ asset('catalog/assets/img/icons/like.svg') }}">
                    <span class="like">{{ $customer->getCountPositiveReviews() }}</span>
                    <img src="{{ asset('catalog/assets/img/icons/dislike.svg') }}">
                    <span class="dislike">{{ $customer->getCountNegativeReviews() }}</span>
                </div>
            </div>
        </div>
    </div>
    @endforeach

    <div class="foot_worker clearfix">
        <a href="/experts">
            <img src="{{ asset('catalog/assets/img/icons/eye.svg') }}">
            <span>Смотреть все</span>
        </a>
    </div>
</div>
@endif
