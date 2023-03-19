@if($customer)
    <a href="{{ route('account.profile', $customer->login) }}">
        {{ $customer->login }}
    </a>
@else
    <a>
        Unknown
    </a>
@endif
