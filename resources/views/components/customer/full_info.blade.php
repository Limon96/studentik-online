<div class="img_face clearfix">
    <img src="offer.image">
    @if($customer->isOnline())<span></span>@endif
</div>
<div class="info_worker clearfix">
    <div class="lit_hed clearfix">
        <a href="{{ route('account.profile', $customer->login) }}">{{ $customer->login }} @if($offer->assigned) <span class="uor_like">Выбран исполнителем</span> @endif</a>

        @if($customer->isPro())<span class="premium">PREMIUM</span>@endif
    </div>
    <div class="lit_foot clearfix">
        <div class="enter clearfix">
            <span class="name">Рейтинг:</span>
            <span class="rating">{{ $customer->rating }}</span>
            {% if offer.new_rating %}<span class="bonus">+ $offer.new_rating </span>{% endif %}
        </div>
        <div class="enter_like clearfix">
            <img src="{{ asset('catalog/assets/img/icons/like.svg') }}">
            <span class="like"> offer.total_reviews_positive }}</span>
            <img src="{{ asset('catalog/assets/img/icons/dislike.svg') }}">
            <span class="dislike"> offer.total_reviews_negative }}</span>
        </div>
    </div>
</div>
