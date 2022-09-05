<div class="left_content_resp">

    {{--<button class="open_left_colmn">
        <svg class="op" enable-background="new 0 0 24 24" height="512" viewBox="0 0 24 24" width="512"><path d="m5 0h-4c-.552 0-1 .448-1 1v4c0 .552.448 1 1 1h4c.552 0 1-.448 1-1v-4c0-.552-.448-1-1-1z"></path><path d="m23 0h-14c-.552 0-1 .448-1 1v4c0 .552.448 1 1 1h14c.552 0 1-.448 1-1v-4c0-.552-.448-1-1-1z"></path><path d="m5 9h-4c-.552 0-1 .448-1 1v4c0 .552.448 1 1 1h4c.552 0 1-.448 1-1v-4c0-.552-.448-1-1-1z"></path><path d="m23 9h-14c-.552 0-1 .448-1 1v4c0 .552.448 1 1 1h14c.552 0 1-.448 1-1v-4c0-.552-.448-1-1-1z"></path><path d="m5 18h-4c-.552 0-1 .448-1 1v4c0 .552.448 1 1 1h4c.552 0 1-.448 1-1v-4c0-.552-.448-1-1-1z"></path><path d="m23 18h-14c-.552 0-1 .448-1 1v4c0 .552.448 1 1 1h14c.552 0 1-.448 1-1v-4c0-.552-.448-1-1-1z"></path></svg>
        <svg class="cl" xmlns:xlink="http://www.w3.org/1999/xlink" version="1.1" id="Layer_1" x="0px" y="0px" viewBox="0 0 492 492" style="enable-background:new 0 0 492 492;" xml:space="preserve"><path d="M300.188,246L484.14,62.04c5.06-5.064,7.852-11.82,7.86-19.024c0-7.208-2.792-13.972-7.86-19.028L468.02,7.872    c-5.068-5.076-11.824-7.856-19.036-7.856c-7.2,0-13.956,2.78-19.024,7.856L246.008,191.82L62.048,7.872    c-5.06-5.076-11.82-7.856-19.028-7.856c-7.2,0-13.96,2.78-19.02,7.856L7.872,23.988c-10.496,10.496-10.496,27.568,0,38.052    L191.828,246L7.872,429.952c-5.064,5.072-7.852,11.828-7.852,19.032c0,7.204,2.788,13.96,7.852,19.028l16.124,16.116    c5.06,5.072,11.824,7.856,19.02,7.856c7.208,0,13.968-2.784,19.028-7.856l183.96-183.952l183.952,183.952    c5.068,5.072,11.824,7.856,19.024,7.856h0.008c7.204,0,13.96-2.784,19.028-7.856l16.12-16.116    c5.06-5.064,7.852-11.824,7.852-19.028c0-7.204-2.792-13.96-7.852-19.028L300.188,246z"></path></svg>

    </button>
    <aside id="column-left" class="left_content_resp_fix">

        <div>
<ul>
<li><a href="https://studentik.online/user/user1457">Профиль</a></li>
<li><a href="https://studentik.online/setting">Настройки</a></li>
<li><a href="https://studentik.online/index.php?route=account/finance">Финансы</a></li>
</ul>
</div>





        <div class="wrap_left">
            <ul class="instruction">
                <li>
                    <button class="accordion_one clearfix">
                        <img class="img_title" src="../../../../catalog/assets/img/icons/massage_green.svg">
                        <span class="name_k">Мои отклики</span>
                        <span class="how_k">2</span>
                        <img class="arrow_tr" src="../../../../catalog/assets/img/icons/arrow.svg">

                    </button>
                    <div class="panel_clim">
                        <ul>
                            <li><a href="https://studentik.online/my-orders?filter_assigned=1">Выбран <span>1</span></a></li>
                            <li><a href="https://studentik.online/my-orders?filter_assigned=0">Не выбран <span>1</span></a></li>
                        </ul>
                    </div>
                </li>

                <li>
                    <button class="accordion_one clearfix">
                        <img class="img_title" src="../../../../catalog/assets/img/icons/book_warning.svg">
                        <span class="name_k">Мои претензии</span>
                        <span class="how_k">1</span>
                        <img class="arrow_tr" src="../../../../catalog/assets/img/icons/arrow.svg">

                    </button>
                    <div class="panel_clim">
                        <ul>
                            <li><a href="https://studentik.online/claims?filter_status_on=1">Открытые <span>0</span></a></li>
                            <li><a href="https://studentik.online/claims?filter_status_off=1">Закрытые <span>1</span></a></li>
                        </ul>
                    </div>

                </li>
            </ul>
        </div>


    </aside>
    <div class="hide_instr">
        скрытый блок - не видимый блок
    </div>--}}
</div>

<script>
    $('.left_content_resp').load('/orders .left_content_resp > *', function () {
        var acc = document.getElementsByClassName("accordion_one");
        var i;

        for (i = 0; i < acc.length; i++) {
            acc[i].addEventListener("click", function() {
                this.classList.toggle("active");
                var panel = this.nextElementSibling;
                if (panel.style.maxHeight){
                    panel.style.maxHeight = null;
                } else {
                    panel.style.maxHeight = panel.scrollHeight + "px";
                }
            });
        }


        var acc = document.getElementsByClassName("accordion_history");
        var i;

        for (i = 0; i < acc.length; i++) {
            acc[i].addEventListener("click", function() {
                this.classList.toggle("active");
                var panel = this.nextElementSibling;
                if (panel.style.maxHeight){
                    panel.style.maxHeight = null;
                } else {
                    panel.style.maxHeight = panel.scrollHeight + "px";
                }
            });
        }
    });

</script>
