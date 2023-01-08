@extends('layouts.base')

@section('title')
    {{ $item->title }}
@endsection
@section('description')
    {{ $item->description }}
@endsection
@section('keywords')@endsection

@section('scripts')
    <script src="{{ asset('catalog/assets/js/custom.js') }}"></script>
    <script src="{{ asset('catalog/assets/js/offer.js') }}"></script>
    <script>
        $(document).ready(function () {

            var $headers = $('.table_of_contents h2, .table_of_contents h3');

            if ($headers.length < 1) {
                $('#content-list').remove();
                return;
            }

            $headers.each(function (i, e) {
                var tagName = $(e).prop("tagName");
                var nav_id = tagName.toLowerCase() + '-' + i;
                var name = (tagName === 'H3' ? "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;" : '') + $(e).text();

                $(e).attr('id', nav_id);

                $('#content-list ul').append("<li><a href='#" + nav_id + "'>" + name + "</a></li>");
            });
        });

        $(document).on('click', '#button-complete-order', function() {
            $.ajax({
                url: '../index.php?route=order/offer/completeOffer&order_id={{ $item->order_id }}',
                type: 'get',
                dataType: 'json',
                success: function(json) {
                    if (json['success']) {
                        $('#content').load(location.href + ' #content > div');

                        alertSuccess(json['success']);
                    }
                }
            });
        });

        $(document).on('click', '#button-revision-order', function() {
            $.ajax({
                url: '../index.php?route=order/offer/revisionOffer&order_id={{ $item->order_id }}',
                type: 'get',
                dataType: 'json',
                success: function(json) {
                    if (json['success']) {
                        $('#content').load(location.href + ' #content > div');

                        alertSuccess(json['success']);
                    }
                }
            });
        });
    </script>
@endsection

@section("content")
    <div class="wrap_content">

        <div id="order-info" class="container">
            <div class="row">

                @include("components.left_sidebar")

                <div id="content" class="center_content_resp">


                    <div class="clearfix wrap_zakaz">
                        <div class="info_work clearfix">
                            <div class="head">
                                <h1>@yield('title')</h1>
                                <ul class="breadcrumb clearfix">
                                    <li><a href="https://studentik.online/"><i class="fa fa-home"></i></a></li>
                                    <li><a href="{{ route('order.index') }}">Лента заказов</a></li>
                                    <li><a href="{{ route('order.show', $item->getSeoUrl()) }}">@yield('title')</a></li>
                                </ul>
                            </div>
                            <div class="head_inf clearfix">
                                <div class="master clearfix">
                                    @include('components.customer.info', ['customer' => $item->customer])
                                </div>
                                <div class="lable_dop clearfix">
                                    @if($item->hot)
                                    <div class=" wraps_warning red">
                                        <img src="{{ asset('catalog/assets/img/icons/ogony.svg') }}">
                                        <span>срочно</span>
                                    </div>
                                    @endif
                                    @if($item->premium)
                                    <div class=" wraps_warning yellow">
                                        <img src="{{ asset('catalog/assets/img/icons/screpka.svg') }}">
                                        <span>важно</span>
                                    </div>
                                    @endif
                                    <div class=" wraps_warning status">
                                        <span>{{ $item->order_status->name }}</span>
                                    </div>
                                </div>
                            </div>
                            <div class="about_zak">
                                <div class="clearfix zak_fom">
                                    <div class="col-6">
                                        <div class="toopfert"># Заказа: <span> {{ $item->order_id }}</span></div>
                                        <div class="toopfert">Раздел: <span> {{ $item->section->name }}</span></div>
                                        <div class="toopfert">Предмет: <span> {{ $item->subject->name }}</span></div>
                                        <div class="toopfert">Тип работы: <span> {{ $item->work_type->name }}</span></div>
                                        <div class="toopfert">Антиплагиат: <span> {{ $item->getPlagiarismCheck() }}</span>
                                            <div class="my_plag clearfix">
                                                @if($item->getPlagiarism())
                                                    @foreach($item->getPlagiarism() as $plagiarism)
                                                        @if($plagiarism == 'Антиплагиат')
                                                            <div class="rors">
                                                                <img src="{{ asset('catalog/assets/img/antiplagiat.svg') }}" class="all_tooltyp4" alt="{{ $plagiarism }}" data-tooltip="{{ $plagiarism }}">
                                                            </div>
                                                        @endif
                                                        @if($plagiarism == 'eTXT')
                                                            <div class="rors">
                                                                <img src="{{ asset('catalog/assets/img/eTXT.svg') }}" class="all_tooltyp4" alt="{{ $plagiarism }}" data-tooltip="{{ $plagiarism }}">
                                                            </div>
                                                        @endif
                                                        @if($plagiarism == 'Антиплагиат.ВУЗ')
                                                            <div class="rors">
                                                                <img src="{{ asset('catalog/assets/img/logo-ap-vuz.svg') }}" class="all_tooltyp4" alt="{{ $plagiarism }}" data-tooltip="{{ $plagiarism }}">
                                                            </div>
                                                        @endif
                                                        @if($plagiarism == 'Руконтекст')
                                                            <div class="rors">
                                                                <img src="{{ asset('catalog/assets/img/rukon.svg') }}" class="all_tooltyp4" alt="{{ $plagiarism }}" data-tooltip="{{ $plagiarism }}">
                                                            </div>
                                                        @endif
                                                    @endforeach
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="toopfert">Срок сдачи: <span> {{ $item->getDateEnd() }}</span></div>
                                        <div class="toopfert">Цена: <span> {{ $item->getPrice() }}</span></div>
                                        <div class="toopfert">Размещен: <span> {{ format_date($item->date_added, 'full_datetime') }}</span></div>
                                        <div class="toopfert">Просмотров: <span> {{ $item->viewed }}</span></div>
                                        <div class="toopfert">Срок блокировки: <span data-tooltip="После скачивания работы должно пройти 15
                                        суток, для того чтобы начислить средства на счёт исполнителя"> 15 дней </span></div>


                                    </div>
                                </div>
                                @if(auth()->check())
                                    @if($item->isOwner() or ($item->offerAssigned and $item->offerAssigned->isOwner()) or auth()->user()->isAuthor())
                                    <div class="order-control clearfix">
                                        @if(auth()->user()->isAuthor())
                                        <a href="{{ route('messages', ['chat_id' => $item->customer->customer_id]) }}" class="review_add">Написать сообщение</a>
                                        @endif

                                        @if($item->isOwner() and $item->isOrderStatusInArray([1]))
                                        <button id="button-cancel-order" class="button-cancel-order" data-order_id="{{ $item->order_id }}">Отменить заказ</button>
                                        @endif

                                        @if($item->isOwner() and $item->isOrderStatusInArray([8]))
                                        <button id="button-open-order" class="button-open-order" data-order_id="{{ $item->order_id }}">Открыть заказ</button>
                                        @endif

                                        @if($item->isOwner() and $item->isOrderStatusInArray([5, 7]))
                                        <button id="button-complete-order" class="button-complete-order" data-order_id="{{ $item->order_id }}">Завершить заказ</button>
                                        @endif
    
                                        @if($item->isOwner() and $item->isOrderStatusInArray([5, 6]))
                                        <button id="button-revision-order"  class="button-revision-order" data-order_id="{{ $item->order_id }}">Отправить на доработку</button>
                                        @endif

                                        @if($item->isNotExistsReview() and $item->isOrderStatusInArray([5, 6]))
                                        <a href="{{ __('review_add') }}" class="review_add">Оставить отзыв</a>
                                        @endif

                                        @if($item->offerAssigned and $item->isOrderStatusInArray([3, 4, 5, 6, 7]))
                                            @if($item->offerAssigned->isOwner())
                                                <a href="{{ url('index.php?route=claim/claim/add&type=order&object_id=' . $item->order_id . '&defendant_id=' . $item->customer_id) }}" class="claim">Подать претензию</a>
                                            @elseif($item->isOwner())
                                                <a href="{{ url('index.php?route=claim/claim/add&type=order&object_id=' . $item->order_id . '&defendant_id=' . $item->offerAssigned->customer_id) }}" class="claim">Подать претензию</a>
                                            @endif
                                        @endif
                                    </div>
                                    @endif
                                @endif
                                <div class="clearfix zak_descr">
                                    <h3>Описание</h3>
                                    <p>{!! $item->description !!}</p>
                                    @if($item->isOwner() and $item->isOrderStatusInArray([1]))
                                    <a href="{{ route('order.edit', $item->getSeoUrl()) }}">Редактировать</a>
                                    @endif
                                </div>
                            </div>



                            @if(auth()->check())
                                @if($item->work_type_id == 20)
                                    @if($item->customer->isAuthor())

                                        <div class="baner_micro">
                                            <div class="left_svg_r">
                                                <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" version="1.1" id="Layer_1" x="0px" y="0px" viewBox="0 0 512.002 512.002" style="enable-background:new 0 0 512.002 512.002;" xml:space="preserve">
                                                    <path style="fill:#47CEAC;" d="M512.001,256.006c0,141.395-114.606,255.998-255.996,255.994  C114.607,512.004,0.001,397.402,0.001,256.006C-0.006,114.61,114.607,0,256.005,0C397.395,0,512.001,114.614,512.001,256.006z"/>
                                                    <path style="fill:#36BB9A;" d="M508.39,298.698c-0.098-0.096-0.191-0.194-0.29-0.29c-0.518-0.527-151.485-151.494-152.016-152.016  c-24.049-24.479-59.728-39.771-100.302-39.771c-71.374,0-127.716,47.228-131.955,108.778c-15.756,4.127-25.585,22.403-25.585,48.901  v0.661c-26.306,2.876-41.332,20.011-41.332,48.292c0,15.522,4.355,27.677,12.277,36.217c0.474,0.572,30.164,30.166,30.346,30.377  c1.335,6.571,4.073,13.421,9.735,18.379c0.509,0.531,109.134,109.204,109.7,109.7c0.509,0.531,1.002,1.072,1.577,1.577  c11.597,1.607,23.421,2.5,35.46,2.5C382.843,512.003,488.061,419.757,508.39,298.698z"/>
                                                    <path style="fill:#F6F6F6;" d="M455.197,313.253c0-28.282-15.025-45.417-41.332-48.292V264.3c0-26.73-10.021-45.043-26.025-48.956  c-4.269-61.522-60.601-108.722-131.953-108.722c-71.374,0-127.716,47.228-131.955,108.777  c-15.756,4.127-25.585,22.403-25.585,48.901v0.661c-26.306,2.876-41.332,20.011-41.332,48.292c0,29.215,15.026,46.915,41.332,49.886  v0.746c0,11.35,0,41.494,33.196,41.494h33.196c4.587,0,8.299-3.716,8.299-8.299V222.804c0-4.583-3.712-8.299-8.299-8.299h-24.165  c4.606-52.582,52.581-91.287,115.315-91.287s110.707,38.706,115.313,91.287h-23.727c-4.587,0-8.299,3.716-8.299,8.299v174.275  c0,4.583,3.712,8.299,8.299,8.299h33.196c33.196,0,33.196-30.144,33.196-41.494v-0.746  C440.171,360.169,455.197,342.467,455.197,313.253z M73.612,313.253c0-18.91,8.122-29.343,24.735-31.735v65.091  C81.524,344.18,73.612,333.422,73.612,313.253z M156.438,388.782h-24.896c-11.249,0-16.597-3.724-16.597-24.896v-99.587  c0-12.331,3.372-33.196,16.022-33.196h25.472v157.679H156.438z M397.266,363.885c0,21.172-5.349,24.896-16.597,24.896h-24.896  V231.104h25.464c12.651,0,16.03,20.865,16.03,33.196L397.266,363.885L397.266,363.885z M413.865,346.608v-65.091  c16.612,2.393,24.735,12.826,24.735,31.735C438.598,333.422,430.686,344.18,413.865,346.608z"/>
                                                </svg>
                                            </div>
                                            <div class="int_j">
                                                <h2>Тип работы  <span>«Диктовка в микронаушник»</span> подразумевает онлайн-помощь студенту во время экзамена через микронаушник. </h2>
                                                <p>Уточните все подробности у студента перед выставлением ставки. </p>
                                                <h3>Передача контактных данных должна произойти <span>ТОЛЬКО ПОСЛЕ ОПЛАТЫ ЗАКАЗА!</span> До оплаты используйте встроенные на сайт личные сообщения или комментарии к ставке!</h3>

                                            </div>
                                        </div>

                                    @elseif($item->isOwner() and $item->customer->isCustomer())

                                        <div class="baner_micro">
                                            <div class="left_svg_r">
                                                <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" version="1.1" id="Layer_1" x="0px" y="0px" viewBox="0 0 512.002 512.002" style="enable-background:new 0 0 512.002 512.002;" xml:space="preserve">
                                                <path style="fill:#47CEAC;" d="M512.001,256.006c0,141.395-114.606,255.998-255.996,255.994  C114.607,512.004,0.001,397.402,0.001,256.006C-0.006,114.61,114.607,0,256.005,0C397.395,0,512.001,114.614,512.001,256.006z"/>
                                                    <path style="fill:#36BB9A;" d="M508.39,298.698c-0.098-0.096-0.191-0.194-0.29-0.29c-0.518-0.527-151.485-151.494-152.016-152.016  c-24.049-24.479-59.728-39.771-100.302-39.771c-71.374,0-127.716,47.228-131.955,108.778c-15.756,4.127-25.585,22.403-25.585,48.901  v0.661c-26.306,2.876-41.332,20.011-41.332,48.292c0,15.522,4.355,27.677,12.277,36.217c0.474,0.572,30.164,30.166,30.346,30.377  c1.335,6.571,4.073,13.421,9.735,18.379c0.509,0.531,109.134,109.204,109.7,109.7c0.509,0.531,1.002,1.072,1.577,1.577  c11.597,1.607,23.421,2.5,35.46,2.5C382.843,512.003,488.061,419.757,508.39,298.698z"/>
                                                    <path style="fill:#F6F6F6;" d="M455.197,313.253c0-28.282-15.025-45.417-41.332-48.292V264.3c0-26.73-10.021-45.043-26.025-48.956  c-4.269-61.522-60.601-108.722-131.953-108.722c-71.374,0-127.716,47.228-131.955,108.777  c-15.756,4.127-25.585,22.403-25.585,48.901v0.661c-26.306,2.876-41.332,20.011-41.332,48.292c0,29.215,15.026,46.915,41.332,49.886  v0.746c0,11.35,0,41.494,33.196,41.494h33.196c4.587,0,8.299-3.716,8.299-8.299V222.804c0-4.583-3.712-8.299-8.299-8.299h-24.165  c4.606-52.582,52.581-91.287,115.315-91.287s110.707,38.706,115.313,91.287h-23.727c-4.587,0-8.299,3.716-8.299,8.299v174.275  c0,4.583,3.712,8.299,8.299,8.299h33.196c33.196,0,33.196-30.144,33.196-41.494v-0.746  C440.171,360.169,455.197,342.467,455.197,313.253z M73.612,313.253c0-18.91,8.122-29.343,24.735-31.735v65.091  C81.524,344.18,73.612,333.422,73.612,313.253z M156.438,388.782h-24.896c-11.249,0-16.597-3.724-16.597-24.896v-99.587  c0-12.331,3.372-33.196,16.022-33.196h25.472v157.679H156.438z M397.266,363.885c0,21.172-5.349,24.896-16.597,24.896h-24.896  V231.104h25.464c12.651,0,16.03,20.865,16.03,33.196L397.266,363.885L397.266,363.885z M413.865,346.608v-65.091  c16.612,2.393,24.735,12.826,24.735,31.735C438.598,333.422,430.686,344.18,413.865,346.608z"/>
                                            </svg>
                                            </div>
                                            <div class="int_j">
                                                <h2>Вы выбрали тип работы  <span>«Диктовка в микронаушник»</span>, который подразумевает онлайн-помощь голосом эксперта в микронаушник.</h2>
                                                <p><span>Важно:</span> опишите максимально подробно поставленную задачу перед экспертом (у вас уже есть заготовленные ответы или нужно решить задачу и т.д.)</p>
                                                <h3><span>ПЕРЕДАЧА КОНТАКТНЫХ ДАННЫХ ПРОИСХОДИТ СТРОГО ПОСЛЕ ОПЛАТЫ СТАВКИ.</span></h3>
                                            </div>
                                        </div>

                                    @endif
                                @endif
                            @endif

                            @if(auth()->check() === false)
                                @include('components.similar_work', ['work_type_id' => $item->work_type_id])
                            @endif

                            @if(auth()->check())
                                @if($item->attachments or $item->isOwner() or auth()->user()->isAdmin())
                                    @include('order.partials.attachments', ['order' => $item])
                                @endif

                                {% if offer_attachments and $item->isOwner() or offer_assigned_is_owner or auth()->user()->isAdmin() %}
                                <div class="look_files">
                                    <h3>Готовая работа</h3>
                                    <div id="files-offer">
                                        {% if offer_attachments %}
                                        {% for attachment in offer_attachments %}
                                        {{--<div class="clearfix file_item">
                                            <div class="logo_file">
                                                <img src="../catalog/assets/img/file/{{ attachment.type }}.png">
                                            </div>
                                            <div class="left_cop">
                                                <span>{{ attachment.name }}</span>
                                                <span class="weigt">{{ attachment.size }}</span>
                                            </div>
                                            <div class="right_cop">
                                                <span>{{ attachment.date_added }}</span>
                                                <p class="delete_file_cust clearfix">
                                                    <a href="{{ attachment.upload }}">{{ button_upload }}</a>
                                                    {% if offer_assigned_is_owner %}
                                                    <button class="delete_file delete_offer_file" data-attachment-id="{{ attachment.attachment_id }}">
                                                        <svg xmlns="http://www.w3.org/2000/svg" height="329pt" viewBox="0 0 329.26933 329" width="329pt"><path d="m194.800781 164.769531 128.210938-128.214843c8.34375-8.339844 8.34375-21.824219 0-30.164063-8.339844-8.339844-21.824219-8.339844-30.164063 0l-128.214844 128.214844-128.210937-128.214844c-8.34375-8.339844-21.824219-8.339844-30.164063 0-8.34375 8.339844-8.34375 21.824219 0 30.164063l128.210938 128.214843-128.210938 128.214844c-8.34375 8.339844-8.34375 21.824219 0 30.164063 4.15625 4.160156 9.621094 6.25 15.082032 6.25 5.460937 0 10.921875-2.089844 15.082031-6.25l128.210937-128.214844 128.214844 128.214844c4.160156 4.160156 9.621094 6.25 15.082032 6.25 5.460937 0 10.921874-2.089844 15.082031-6.25 8.34375-8.339844 8.34375-21.824219 0-30.164063zm0 0"></path></svg>
                                                    </button>
                                                    {% endif %}
                                                </p>
                                            </div>
                                        </div>--}}
                                        {% endfor %}
                                        {% endif %}
                                    </div>
                                    {% if offer_assigned_is_owner %}
                                    <script>
                                        $(document).on('click', '#files-offer .file_item .delete_offer_file', function(){
                                            var attachment_id = parseInt($(this).data('attachment-id'));
                                            console.log($(this), attachment_id);
                                            if (attachment_id > 0) {
                                                $(this).parents('.file_item').remove();
                                                $.ajax({
                                                    url: '../index.php?route=order/order/deleteOfferAttachment&order_id={{ $item->order_id }}',
                                                    type: 'post',
                                                    dataType: 'json',
                                                    data: {
                                                        attachment_id: attachment_id
                                                    },
                                                    success: function(json) {
                                                        console.log(json);
                                                        $('#files-offer').load(location.href + ' #files-offer > div');
                                                    }
                                                });
                                            }
                                        });
                                    </script>
                                    {% endif %}
                                    {% if offer_assigned_is_owner and (order_status_id == config_progress_order_status_id or order_status_id == config_awaiting_order_status_id or order_status_id == config_verification_order_status_id or order_status_id == config_revision_order_status_id) %}

                                    <div>
                                        <form id="form-upload-complete" class="file_clear">
                                            <label>
                                                <p>
                                                    <svg style="position: relative;width: 10px" viewBox="0 0 384 512" xmlns="http://www.w3.org/2000/svg" class="sw-icon">
                                                        <path d="M288 248v28c0 6.6-5.4 12-12 12H108c-6.6 0-12-5.4-12-12v-28c0-6.6 5.4-12 12-12h168c6.6 0 12 5.4 12 12zm-12 72H108c-6.6 0-12 5.4-12 12v28c0 6.6 5.4 12 12 12h168c6.6 0 12-5.4 12-12v-28c0-6.6-5.4-12-12-12zm108-188.1V464c0 26.5-21.5 48-48 48H48c-26.5 0-48-21.5-48-48V48C0 21.5 21.5 0 48 0h204.1C264.8 0 277 5.1 286 14.1L369.9 98c9 8.9 14.1 21.2 14.1 33.9zm-128-80V128h76.1L256 51.9zM336 464V176H232c-13.3 0-24-10.7-24-24V48H48v416h288z"></path>
                                                    </svg>
                                                </p>
                                                <input type="file" name="file[]" multiple="multiple" accept=".bmp,.djvu,.doc,.docx,.dwg,.csv,.gif,.jpeg,.jpg,.odf,.odt,.pdf,.png,.ppt,.pptx,.rar,.rtf,.svg,.tga,.tiff,.txt,.xls,.xlsx,.zip,.a3d,.cdt,.cdw,.m3d,.dwf,.cdr,.ai,.ics">
                                                <span>Добавить файл</span>
                                            </label>
                                        </form>
                                    </div>
                                    <script>
                                        $(document).on('change', '#form-upload-complete input', function(){
                                            if (typeof timer != 'undefined') {
                                                clearInterval(timer);
                                            }

                                            timer = setInterval(function() {
                                                if ($('#form-upload-complete input[type=file]').val() != '') {
                                                    clearInterval(timer);

                                                    $.ajax({
                                                        url: '../index.php?route=common/upload/upload',
                                                        type: 'post',
                                                        dataType: 'json',
                                                        data: new FormData($('#form-upload-complete')[0]),
                                                        cache: false,
                                                        contentType: false,
                                                        processData: false,
                                                        beforeSend: function() {
                                                            $('#button-upload-complete i').replaceWith('<i class="fa fa-circle-o-notch fa-spin"></i>');
                                                            $('#button-upload-complete').prop('disabled', true);
                                                            $('#form-upload-complete').btnLoading('on');
                                                        },
                                                        complete: function() {
                                                            $('#button-upload-complete i').replaceWith('<i class="fa fa-upload"></i>');
                                                            $('#button-upload-complete').prop('disabled', false);
                                                            $('#form-upload-complete').btnLoading('off');
                                                        },
                                                        success: function(json) {
                                                            if (json['error']) {
                                                                console.log(json['error']);
                                                            }

                                                            if (json['success']) {
                                                                if (json['files']) {
                                                                    for (var i in json['files']) {
                                                                        var tmpl = '<div class="clearfix file_item">\n' +
                                                                            '    <div class="logo_file">\n' +
                                                                            '       <img src="../catalog/assets/img/file/' + json['files'][i]['type'] + '.png">\n' +
                                                                            '    </div>' +
                                                                            '    <div class="left_cop">\n' +
                                                                            '        <span>' + json['files'][i]['name'] + '</span>\n' +
                                                                            '        <span class="weigt">' + json['files'][i]['size'] + '</span>\n' +
                                                                            '    </div>\n' +
                                                                            '    <div class="right_cop">\n' +
                                                                            '        <span>' + json['files'][i]['date_added'] + '</span>\n' +
                                                                            '        <p class="delete_file_cust clearfix">\n' +
                                                                            '            <a href="' + json['files'][i]['upload'] + '">Скачать</a>\n' +
                                                                            '        </p>\n' +
                                                                            '    </div>\n' +
                                                                            '</div>';
                                                                        $('#files-offer').append(tmpl);

                                                                        $.ajax({
                                                                            url: '../index.php?route=order/order/addOfferAttachment&order_id={{ $item->order_id }}',
                                                                            type: 'post',
                                                                            dataType: 'json',
                                                                            data: {
                                                                                attachment_id: json['files'][i]['attachment_id']
                                                                            },
                                                                            success: function(json) {
                                                                                $('#files-offer').load(location.href + ' #files-offer > div');
                                                                            }
                                                                        });
                                                                        {% if order_status_id == config_progress_order_status_id or  order_status_id == config_revision_order_status_id %}
                                                                        $.ajax({
                                                                            url : '../index.php?route=order/offer/awaitingOffer&order_id={{ $item->order_id }}',
                                                                            method : 'GET',
                                                                            success : function (json) {
                                                                                if (json['success']) {
                                                                                    //location.reload();
                                                                                    $('#content').load(location.href + ' #content > div');
                                                                                    alertSuccess(json['success']);
                                                                                }
                                                                            }
                                                                        });
                                                                        {% endif %}
                                                                    }
                                                                }
                                                            }
                                                        },
                                                        error: function(xhr, ajaxOptions, thrownError) {
                                                            console.log(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
                                                        }
                                                    });
                                                }
                                            }, 500);
                                        });

                                    </script>
                                    {% endif %}
                                </div>
                                {% endif %}

                                @if($item->isOwner() or auth()->user()->isAuthor() or auth()->user()->isAdmin())
                                    @include('order.partials.history', ['order' => $item])
                                @endif

                            @endif
                        </div>
                        @if(auth()->check())
                            @include('order.partials.offers', ['order' => $item])
                        @endif
                    </div>
                </div>

                @include("components.right_sidebar")
            </div>
        </div>
    </div>

@endsection


