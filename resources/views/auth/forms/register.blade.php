<form id="form-register" action="{{ asset('index.php?route=account/register') }}" method="post">
    @csrf
    <div class="includ clearfix">
        <i class="fas fa-envelope"></i>
        <input type="text" name="email" placeholder="E-Mail">
    </div>
    <div class="includ clearfix">
        <i class="fas fa-lock"></i>
        <input type="password" name="password" placeholder="Пароль">
    </div>
    @if($customer_groups)
    <div class="includ clearfix radio">
        @foreach($customer_groups as $customer_group)

        <label class="container_lan" for="radio_customer_group-{{ $customer_group->customer_group_id }}">
            {{ $customer_group->name }}
            <input type="radio"
                   id="radio_customer_group-{{ $customer_group->customer_group_id }}"
                   name="customer_group_id"
                   value="{{ $customer_group->customer_group_id }}"
                   @if($customer_group->customer_group_id == old('customer_group_id')) checked @endif>
            <span class="checkmark_lan"></span>
        </label>

        @endforeach
    </div>
    @endif
    <div class="includ clearfix btn-loader-wrapper">
        <input type="hidden" name="agree" value="1">
        <button type="submit">Зарегистрироваться</button>
    </div>
    <span class="igree">Нажимая кнопку «Регистрация», я принимаю <a href="%s" target="_blank"> пользовательское соглашение </a> и <a href="%s" target="_blank"> политику конфиденциальности </a></span>
</form>
