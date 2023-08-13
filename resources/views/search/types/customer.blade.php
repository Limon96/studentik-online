<div class="item_s user_p">
    <div class="img_face clearfix">
        <a href="{{ route('account.profile', $item->login) }}"><img src="{{ $item->model->getImage() }}" alt="{{ $item->login }}"></a>
        @if($item->model->isOnline())<span></span>@endif
    </div>
    <div class="info_worker clearfix">
        <div class="lit_hed clearfix">
            <a href="{{ route('account.profile', $item->login) }}">{{ $item->login }}</a>
            @if($item->model->isPro())<span class="premium">PREMIUM</span>@endif
        </div>
        <div class="lit_foot ">
            <div class="enter ">
                <span class="name">Рейтинг</span>
                <span class="rating">{{ $item->rating }}</span>
                @if($item->model->getNewRating())<span class="bonus">+{{ $item->model->getNewRating() }}</span>@endif
            </div>
            <div class="enter_like ">
                <img src="/catalog/assets/img/icons/like.svg">
                <span class="like">{{ $item->model->getCountPositiveReviews() }}</span>
                <img src="/catalog/assets/img/icons/dislike.svg">
                <span class="dislike">{{ $item->model->getCountNegativeReviews() }}</span>
            </div>
        </div>
    </div>
</div>
