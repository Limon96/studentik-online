<div class="logof clearfix">
    <div class="img_face">
        <img src="{{ thumbnail($customer->getImage(), 45) }}" alt="{{ $customer->login }}">
        @if($customer->isOnline())<span></span>@endif
    </div>
    <div class="login">
        <a href="{{ route('account.profile', $customer->login) }}">{{ $customer->login }}</a>
    </div>
</div>
