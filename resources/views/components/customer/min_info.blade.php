@if($customer)
    <a href="{{ route('account.profile', $customer->login) }}">
        <img src="{{ thumbnail($customer->getImage(), 45) }}" alt="{{ $customer->login }}">
        {{ $customer->login }}
    </a>
@else
    <a>
        Unknown
    </a>
@endif
