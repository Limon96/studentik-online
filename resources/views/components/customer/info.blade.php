<div class="logof clearfix">
    <div class="img_face">
        <img src="{{ thumbnail($customer->getImage(), 80) }}" alt="{{ $customer->login }}">
        @if($customer->isOnline())<span class="{{ $customer->isOnline() }}"></span>@endif
    </div>
    <div class="login">
        <a href="{{ route('account.profile', $customer->login) }}">{{ $customer->login }}</a>
        <span class="last_time">{{ format_date($customer->last_seen) }}</span>
    </div>
</div>
