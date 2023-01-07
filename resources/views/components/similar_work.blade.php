<div class="need_this_order clearfix">
    <form id="guest-order-form">
        <div class="integer clearfix">
            <h4>Нужна аналогичная работа?</h4>
            <p>Оформи быстрый заказ и узнай стоимость</p>
            <input type="text" name="title" placeholder="Введите название работы" class="name_worck">
            <div class="razdel_wrap">
                <select name="work_type" class="giveSelect2">
                    <option value="0">Все типы работ</option>
                    @foreach(app(\App\Repositories\WorkTypeRepository::class)->getForSelect() as $work_type)
                        @if($work_type->work_type_id == $work_type_id ?? 0)
                        <option value="{{ $work_type->work_type_id }}" selected>{{ $work_type->name }}</option>
                        @else
                        <option value="{{ $work_type->work_type_id }}">{{ $work_type->name }}</option>
                        @endif
                    @endforeach
                </select>
            </div>
            <input type="email" name="email" placeholder="E-mail"  class="name_email">
            <a href="#" id="guest-order" class="go_me_order">Разместить заказ</a>
        </div>
    </form>
    <script>
        $(document).on('click', '#guest-order', function (e) {
            e.preventDefault();
            $('#guest-order-form').submit();
        })

        $(document).on('submit', '#guest-order-form', function (e) {
            e.preventDefault();
            $('.has-error').remove();
            $.ajax({
                url : '../index.php?route=account/register/guestOrder',
                method : 'post',
                data : $('#guest-order-form input, #guest-order-form select'),
                dataType : 'json',
                success : function (json) {
                    if (json['error_email']) {
                        $('#guest-order-form input[name=email]').before('<div class="has-error">' + json['error_email'] + '</div>');
                    }

                    if (json['error_subject']) {
                        $('#guest-order-form select[name=subject]').before('<div class="has-error">' + json['error_subject'] + '</div>');
                    }

                    if (json['error_work_type']) {
                        $('#guest-order-form select[name=work_type]').before('<div class="has-error">' + json['error_work_type'] + '</div>');
                    }

                    if (json['error_title']) {
                        $('#guest-order-form input[name=title]').before('<div class="has-error">' + json['error_title'] + '</div>');
                    }

                    if (json['error_warning']) {
                        $('#guest-order-form').before('<div class="has-error">' + json['error_warning'] + '</div>');
                    }

                    if (json['redirect']) {
                        location.href = json['redirect'];
                    }
                }
            });
        });
    </script>
</div>
<div class="reclama_wrap clearfix">
    <div class="remastr clearfix">
        <div class="img_alaby">
            <svg xmlns="http://www.w3.org/2000/svg" height="479pt" viewBox="0 0 479 479.84995" width="479pt"><path d="m293.773438 195.351562-72.097657 68.097657-34.601562-35.597657c-3.835938-3.949218-10.152344-4.039062-14.097657-.203124-3.949218 3.839843-4.039062 10.152343-.203124 14.101562l41.5 42.601562c3.855468 3.878907 10.085937 4.011719 14.101562.296876l79.300781-74.796876c1.925781-1.816406 3.050781-4.324218 3.125-6.96875.074219-2.644531-.90625-5.210937-2.726562-7.132812-3.859375-4.011719-10.222657-4.1875-14.300781-.398438zm0 0"/><path d="m240.273438 90.550781c-82.398438 0-149.398438 67-149.398438 149.398438 0 82.402343 67 149.402343 149.398438 149.402343 82.402343 0 149.402343-67 149.402343-149.402343 0-82.398438-67-149.398438-149.402343-149.398438zm0 278.800781c-71.398438 0-129.398438-58.101562-129.398438-129.402343s58-129.398438 129.398438-129.398438c71.46875 0 129.402343 57.933594 129.402343 129.398438 0 71.464843-57.933593 129.402343-129.402343 129.402343zm0 0"/><path d="m445.875 184.949219 3.300781-59.300781c.230469-3.980469-1.933593-7.71875-5.5-9.5l-52.800781-26.699219-26.699219-52.800781c-1.816406-3.535157-5.53125-5.6875-9.5-5.5l-59.402343 3.203124-49.5-32.703124c-3.335938-2.199219-7.660157-2.199219-11 0l-49.5 32.703124-59.296876-3.300781c-3.984374-.234375-7.71875 1.929688-9.5 5.5l-26.703124 52.800781-52.796876 26.699219c-3.539062 1.8125-5.6875 5.527344-5.5 9.5l3.296876 59.300781-32.699219 49.5c-2.199219 3.335938-2.199219 7.660157 0 11l32.601562 49.597657-3.300781 59.300781c-.230469 3.980469 1.929688 7.71875 5.5 9.5l52.800781 26.699219 26.699219 52.800781c1.8125 3.539062 5.527344 5.6875 9.5 5.5l59.300781-3.300781 49.5 32.699219c3.316407 2.269531 7.683594 2.269531 11 0l49.5-32.699219 59.300781 3.300781c3.980469.230469 7.714844-1.933594 9.5-5.5l26.699219-52.800781 52.800781-26.699219c3.535157-1.816406 5.6875-5.527344 5.5-9.5l-3.300781-59.300781 32.699219-49.5c2.199219-3.335938 2.199219-7.664063 0-11zm-18.601562 101.800781c-1.21875 1.789062-1.785157 3.941406-1.597657 6.101562l3.097657 56.097657-49.898438 25.199219c-1.902344.953124-3.445312 2.5-4.398438 4.402343l-25.203124 49.898438-56.097657-3.097657c-2.148437-.078124-4.269531.476563-6.101562 1.597657l-46.800781 30.902343-46.796876-30.902343c-1.625-1.097657-3.539062-1.691407-5.5-1.699219h-.5l-56.101562 3.101562-25.199219-49.902343c-.953125-1.902344-2.496093-3.445313-4.402343-4.398438l-50-25.101562 3.101562-56.097657c.078125-2.148437-.480469-4.269531-1.601562-6.101562l-30.898438-46.800781 30.898438-46.800781c1.222656-1.785157 1.789062-3.941407 1.601562-6.097657l-3.101562-56.101562 49.902343-25.199219c1.902344-.953125 3.445313-2.496094 4.398438-4.398438l25.199219-49.902343 56.101562 3.101562c2.144531.074219 4.265625-.480469 6.101562-1.601562l46.796876-30.898438 46.800781 30.898438c1.789062 1.222656 3.945312 1.789062 6.101562 1.601562l56.097657-3.101562 25.203124 49.902343c.953126 1.902344 2.496094 3.445313 4.398438 4.398438l49.898438 25.199219-3.097657 56.101562c-.078125 2.144531.480469 4.265625 1.597657 6.097657l30.902343 46.800781zm0 0"/></svg>
        </div>
        <div class="text_alaby">
            <span>Гарантированные бесплатные доработки</span>
        </div>
    </div>
    <div class="remastr clearfix">
        <div class="img_alaby">
            <svg xmlns="http://www.w3.org/2000/svg" id="Capa_1" enable-background="new 0 0 443.294 443.294" height="512" viewBox="0 0 443.294 443.294" width="512"><path d="m221.647 0c-122.214 0-221.647 99.433-221.647 221.647s99.433 221.647 221.647 221.647 221.647-99.433 221.647-221.647-99.433-221.647-221.647-221.647zm0 415.588c-106.941 0-193.941-87-193.941-193.941s87-193.941 193.941-193.941 193.941 87 193.941 193.941-87 193.941-193.941 193.941z"/><path d="m235.5 83.118h-27.706v144.265l87.176 87.176 19.589-19.589-79.059-79.059z"/></svg>
        </div>
        <div class="text_alaby">
            <span>Быстрое выполнение от 2-х часов</span>
        </div>
    </div>
    <div class="remastr clearfix">
        <div class="img_alaby">
            <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" version="1.1" id="Capa_1" x="0px" y="0px" viewBox="0 0 214.27 214.27" style="enable-background:new 0 0 214.27 214.27;" xml:space="preserve">
<g>
    <path d="M196.926,55.171c-0.11-5.785-0.215-11.25-0.215-16.537c0-4.142-3.357-7.5-7.5-7.5c-32.075,0-56.496-9.218-76.852-29.01   c-2.912-2.832-7.546-2.831-10.457,0c-20.354,19.792-44.771,29.01-76.844,29.01c-4.142,0-7.5,3.358-7.5,7.5   c0,5.288-0.104,10.755-0.215,16.541c-1.028,53.836-2.436,127.567,87.331,158.682c0.796,0.276,1.626,0.414,2.456,0.414   c0.83,0,1.661-0.138,2.456-0.414C199.36,182.741,197.954,109.008,196.926,55.171z M107.131,198.812   c-76.987-27.967-75.823-89.232-74.79-143.351c0.062-3.248,0.122-6.396,0.164-9.482c30.04-1.268,54.062-10.371,74.626-28.285   c20.566,17.914,44.592,27.018,74.634,28.285c0.042,3.085,0.102,6.231,0.164,9.477C182.961,109.577,184.124,170.844,107.131,198.812   z"/>
    <path d="M132.958,81.082l-36.199,36.197l-15.447-15.447c-2.929-2.928-7.678-2.928-10.606,0c-2.929,2.93-2.929,7.678,0,10.607   l20.75,20.75c1.464,1.464,3.384,2.196,5.303,2.196c1.919,0,3.839-0.732,5.303-2.196l41.501-41.5   c2.93-2.929,2.93-7.678,0.001-10.606C140.636,78.154,135.887,78.153,132.958,81.082z"/>
</g>
</svg>
        </div>
        <div class="text_alaby">
            <span>Проверка работы на плагиат</span>
        </div>
    </div>
</div>
