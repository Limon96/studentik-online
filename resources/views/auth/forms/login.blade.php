<form id="form-login" action="{{ route('sign_in') }}" method="post">
    @csrf
    <div class="includ clearfix">
        <i class="fas fa-user"></i>
        <input type="text" name="login" placeholder="Логин или email">
    </div>
    <div class="includ clearfix">
        <i class="fas fa-lock"></i>
        <input type="password" name="password" placeholder="Пароль">
    </div>
    <div class="includ clearfix btn-loader-wrapper">
        <button type="submit">Войти</button>
    </div>
</form>
