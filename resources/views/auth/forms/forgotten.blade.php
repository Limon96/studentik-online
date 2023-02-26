<form id="form-forgotten" action="{{ asset('index.php?route=account/forgotten') }}" method="post">
    @csrf
    <div class="includ clearfix">
        <i class="fas fa-envelope"></i>
        <input type="text" name="email" placeholder="E-Mail">
    </div>
    <div class="includ clearfix btn-loader-wrapper">
        <button type="submit">Восстановить</button>
    </div>
</form>
