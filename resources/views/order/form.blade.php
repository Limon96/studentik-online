@extends('layouts.base')

@section('title')
    Разместить заказ
@endsection
@section('description')
    Все заказы по гуманитарным, экономическим, техническим и другим дисциплинам. Воспользуйтесь фильтром для поиска подходящего заказа.
@endsection
@section('keywords')@endsection

@section('scripts')

    <script>
        $(document).ready(function () {
            showBanner();
        });
        $(document).on('change', '#select-work-type', function () {
            showBanner();
        });

        function showBanner() {

            if ($('#select-work-type').val() == 20) {
                $('#banner-speaker .baner_micro').show();
            } else {
                $('#banner-speaker .baner_micro').hide();
            }
        }
    </script>

    <script>
        $(document).on('change', '.date_file input[name=date_unknown]', function () {
            $('.date_file input[name=date_end]').prop('disabled', $(this).prop('checked'));
        });

        $(document).on('keydown', '#order-create input[name=price]', function () {
            var price = parseFloat($(this).val());
            $(this).val(price > 0 ? price: '');
        });
        $(document).on('change', '#order-create input[name=price]', function () {
            var price = parseFloat($(this).val());
            $(this).val(price > 0 ? price: '');
        });

        $(document).on('click', '#form-submit', function (e) {
            if ($('#form-submit').prop('disabled') == true) return;

            $('.has-error, .alert').remove();

            //$('#order-create textarea').val($('#textarea-description').html());

            $.ajax({
                url : "/create-order",
                data : $('#order-create input[type=date], #order-create input[type=text], #order-create input[type=hidden], #order-create input[type=checkbox]:checked, #order-create select, #order-create textarea'),
                method : 'POST',
                dataType : 'json',
                beforeSend : function() {
                    $('#form-submit').prop('disabled', true);
                    $('#form-submit').btnLoading('on');
                },
                success : function (json) {
                    $('#form-submit').prop('disabled', false);

                    if (!json['success']) {
                        $('#form-submit').btnLoading('off');
                    }

                    if (json['redirect']) {
                        location.href = json['redirect'].replace('&amp;','&');
                    }
                },
                error: function (response) {
                    $('#form-submit').btnLoading('off');

                    if (response.status === 422) {

                        var json = response.responseJSON;

                        if (json['errors']['subject']) {
                            $('#order-create select[name=subject]').after('<div class="has-error">' + json['errors']['subject'] + '</div>');
                        }
                        if (json['errors']['work_type']) {
                            $('#order-create select[name=work_type]').after('<div class="has-error">' + json['errors']['work_type'] + '</div>');
                        }
                        if (json['errors']['date_end']) {
                            $('#order-create input#input-date_end').after('<div class="has-error">' + json['errors']['date_end'] + '</div>');
                        }
                        if (json['errors']['description']) {
                            $('#order-create textarea[name=description]').after('<div class="has-error">' + json['errors']['description'] + '</div>');
                        }
                        if (json['errors']['title']) {
                            $('#order-create input[name=title]').after('<div class="has-error">' + json['errors']['title'] + '</div>');
                        }

                        if (json['errors']['title']) {
                            $('#order-create input[name=title]').focus();
                            $('html, body').animate({
                                scrollTop: $('#order-create input[name=title]').offset().top - 250  // класс объекта к которому приезжаем
                            }, 1000);
                        } else if (json['errors']['subject']) {
                            $('#order-create select[name=subject]').focus();
                            $('html, body').animate({
                                scrollTop: $('#order-create select[name=subject]').offset().top - 250  // класс объекта к которому приезжаем
                            }, 1000);
                        } else if (json['errors']['work_type']) {
                            $('#order-create select[name=work_type]').focus();
                            $('html, body').animate({
                                scrollTop: $('#order-create select[name=work_type]').offset().top - 250  // класс объекта к которому приезжаем
                            }, 1000);
                        } else if (json['errors']['date_end']) {
                            $('#order-create input[name=date_end]').focus();
                            $('html, body').animate({
                                scrollTop: $('#order-create input[name=date_end]').offset().top - 250  // класс объекта к которому приезжаем
                            }, 1000);
                        } else if (json['errors']['description']) {
                            $('#order-create textarea[name=description]').focus();
                            $('html, body').animate({
                                scrollTop: $('#order-create textarea[name=description]').offset().top - 250  // класс объекта к которому приезжаем
                            }, 1000);
                        }


                        if (json['errors']['balance']) {
                            window.open(json['balance_redirect'].replace('&amp;','&'));
                        }

                    }
                }
            });
        });
    </script>
@endsection
@section("content")
    <div class="wrap_content">

        <div id="order-order-add" class="container">
            <div class="row">
                @include("components.left_sidebar")

                <div id="order-create" class="center_content_resp">
                    <div class="clearfix search_panel">
                        <div class="heads">
                            <h2>Разместить заказ</h2>
                        </div>
                        <form action="{{ route('order.create') }}" method="POST">
                            @csrf
                            <input type="hidden" name="payment_blocking_id" value="3">

                            <div class="clearfix brathers">
                                <input type="text" name="title" placeholder="Введите название работы" value="{{ old('title', session('order.title', '')) }}">
                            </div>

                            <div class="parametrs clearfix">
                                <div class="balitarda">
                                    <div class="razdel_wrap first">
                                        <select name="work_type" id="select-work-type" class="templatingSelect3">
                                            <option value="0">Все типы работ</option>
                                            @foreach($work_types as $option)
                                                @if($option->work_type_id == old('work_type', session('order.work_type', 0)))
                                                    <option value="{{ $option->work_type_id }}"
                                                            selected>{{ $option->name }}</option>
                                                @else
                                                    <option
                                                        value="{{ $option->work_type_id }}">{{ $option->name }}</option>
                                                @endif
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="razdel_wrap thecond">
                                        <select name="subject" id="select-section-subject" class="templatingSelect4">
                                            <option value="0">Все </option>
                                            @foreach($sections as $optgroup)
                                            <optgroup label="{{ $optgroup->name }}">
                                                @foreach($optgroup->subjects as $option)
                                                    @if($option->id == old('subject', session('order.subject', 0)))
                                                        <option value="{{ $option->id }}" selected>{{ $option->name }}</option>
                                                    @else
                                                        <option value="{{ $option->id }}">{{ $option->name }}</option>
                                                    @endif
                                                @endforeach
                                            </optgroup>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <div class="date_file clearfix">
                                <div class="errors"></div>
                                <div class="add_file">
                                    <div id="form-upload">
                                        <label class="add_works">
                                            <span>
                                                <svg style="position: relative;width: 10px" viewBox="0 0 384 512" xmlns="http://www.w3.org/2000/svg" class="sw-icon">
                                                    <path d="M288 248v28c0 6.6-5.4 12-12 12H108c-6.6 0-12-5.4-12-12v-28c0-6.6 5.4-12 12-12h168c6.6 0 12 5.4 12 12zm-12 72H108c-6.6 0-12 5.4-12 12v28c0 6.6 5.4 12 12 12h168c6.6 0 12-5.4 12-12v-28c0-6.6-5.4-12-12-12zm108-188.1V464c0 26.5-21.5 48-48 48H48c-26.5 0-48-21.5-48-48V48C0 21.5 21.5 0 48 0h204.1C264.8 0 277 5.1 286 14.1L369.9 98c9 8.9 14.1 21.2 14.1 33.9zm-128-80V128h76.1L256 51.9zM336 464V176H232c-13.3 0-24-10.7-24-24V48H48v416h288z"></path>
                                                </svg>
                                            </span>
                                            <input type="file" name="file[]" multiple="multiple" accept=".bmp,.djvu,.doc,.docx,.dwg,.csv,.gif,.jpeg,.jpg,.odf,.odt,.pdf,.png,.ppt,.pptx,.rar,.rtf,.svg,.tga,.tiff,.txt,.xls,.xlsx,.zip,.a3d,.cdt,.cdw,.m3d,.dwf,.cdr,.ai,.ics">
                                            Добавить файл
                                        </label>
                                    </div>
                                </div>
                                <div class="date_rools">
                                    <input type="date" id="input-date_end" name="date_end" value="{{ old('date_end', session('order.date_end', '')) }}">
                                </div>
                                <div class="know_date">
                                    <label class="container_ch">Срок неизвестен
                                        <input type="checkbox" name="date_end" value="0000-00-00" @if(old('date_end', session('order.date_end', 0)) == '0000-00-00') checked @endif>
                                        <span class="checkmark"></span>
                                    </label>
                                </div>
                                <div id="files" class="files_rools"></div>

                                <script>
                                    $(document).on('change', '#form-upload input', function(){
                                        if (typeof timer != 'undefined') {
                                            clearInterval(timer);
                                        }

                                        timer = setInterval(function() {
                                            if ($('#form-upload input[type=file]').val() != '') {
                                                clearInterval(timer);
                                                var formData = new FormData();
                                                formData.append('file[]', $('#form-upload input').prop('files')[0]);
                                                $.ajax({
                                                    url: 'index.php?route=common/upload/upload',
                                                    type: 'post',
                                                    dataType: 'json',
                                                    data: formData,
                                                    cache: false,
                                                    contentType: false,
                                                    processData: false,
                                                    beforeSend: function() {

                                                        $('#form-upload').btnLoading('on');
                                                    },
                                                    complete: function() {

                                                        $('#form-upload').btnLoading('off');
                                                    },
                                                    success: function(json) {
                                                        $('.date_file .errors').html('');
                                                        if (json['error']) {
                                                            for (var error of json['error']) {
                                                                $('.date_file .errors').append('<div class="error">' + error + '</div>');
                                                            }
                                                        }

                                                        if (json['success']) {

                                                            if (json['files']) {
                                                                for (var i in json['files']) {
                                                                    var tmpl = renderUploadedFile(json['files'][i]);
                                                                    $('#files').append(tmpl);
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

                                    $(document).on('click', '.file_item .delete_file', function(){
                                        $(this).parents('.file_item').remove();
                                    });
                                </script>
                            </div>

                            <div id="banner-speaker">
                                <div class="baner_micro" style="display: none">
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
                            </div>

                            <div class="opis_works clearfix">
                                <div class="textt_input_go clearfix">
                                    <textarea class="textarea" name="description" id="textarea-description" placeholder="Описание работы" wrap="hard">{{ old('description', session('order.description', '')) }}</textarea>
                                </div>
                            </div>

                            <div class="uvel_st clearfix">
                                <h4>Увеличить количество откликов в 2 раза</h4>
                                <div class="point_c clearfix">
                                    <label class="container_ch clearfix">
                                        <div class="img_wrap">
                                            <svg xmlns="http://www.w3.org/2000/svg" id="Capa_1" enable-background="new 0 0 491.203 491.203" height="512" viewBox="0 0 491.203 491.203" width="512"><g><path d="m326.309 128.277-39.769-120h-82.175l-39.532 120z"/><path d="m88 8.277-88 120h133.248l39.53-120z"/><path d="m491.203 128.277-88-120h-85.058l39.769 120z"/><path d="m365.07 158.277-73.522 263.709 199.54-263.709z"/><path d="m245.602 482.926 87.52-324.078h-176.253z"/><path d="m.154 158.277 198.415 262.267-72.436-262.267z"/></g></svg>
                                        </div>
                                        <div class="text_wrap">
                                            <p>Важный заказ</p>
                                            <span>Приоритетное размещение в ленте на 24 часа</span>
                                        </div>
                                        <div class="price_w"><span> 100 ₽</span></div>
                                        <input type="checkbox" name="premium" id="uvel1" value="1" @if(old('premium', session('order.premium', 0)) == 1) checked @endif>
                                        <span class="checkmark"></span>
                                    </label>
                                </div>
                                <div class="point_c clearfix">
                                    <label class="container_ch clearfix">
                                        <div class="img_wrap">
                                            <svg xmlns="http://www.w3.org/2000/svg" height="512pt" viewBox="-91 0 512 512.00141" width="512pt"><path d="m315 211h-124.144531l107.167969-188.558594c2.652343-4.644531 2.636718-10.34375-.042969-14.972656-2.695313-4.625-7.632813-7.46875-12.980469-7.46875h-180c-6.457031 0-12.1875 4.132812-14.222656 10.253906l-90 271c-1.539063 4.570313-.761719 9.609375 2.050781 13.519532 2.828125 3.914062 7.355469 6.226562 12.171875 6.226562h127.253906l-81.035156 190.097656c-2.902344 6.753906-.46875 14.621094 5.742188 18.558594 6.109374 3.90625 14.316406 2.878906 19.246093-2.691406l240-271c3.925781-4.410156 4.894531-10.726563 2.476563-16.101563-2.417969-5.390625-7.777344-8.863281-13.683594-8.863281zm0 0"/></svg>
                                        </div>
                                        <div class="text_wrap">
                                            <p>Срочный заказ</p>
                                            <span>Яркое выделение заказа в ленте</span>
                                        </div>
                                        <div class="price_w"><span> 100 ₽</span></div>
                                        <input type="checkbox" name="hot" id="uvel2" value="1" @if(old('hot', session('order.hot', 0)) == 1) checked @endif>
                                        <span class="checkmark"></span>
                                    </label>
                                </div>
                            </div>

                            <div class="plagiat clearfix">
                                <div class="left_legart">
                                    <label class="container_ch">Проверка на плагиат
                                        <input type="checkbox" name="plagiarism_check_unknown" value="1" id="openVAr">
                                        <span class="checkmark"></span>
                                    </label>
                                </div>
                                <div class="right_legart dub clearfix hide_select">
                                    <select class="templatingSelect5 hide_select_procent" name="plagiarism_check_id" disabled>
                                        <option value="0">Не указан</option>
                                        @foreach($plagiarism_checks as $option)
                                            @if($option->plagiarism_check_id == old('plagiarism_check_id', session('order.plagiarism_check_id', 0)))
                                                <option value="{{ $option->plagiarism_check_id }}" selected>{{ $option->name }}</option>
                                            @else
                                                <option value="{{ $option->plagiarism_check_id }}">{{ $option->name }}</option>
                                            @endif
                                        @endforeach
                                    </select>
                                    <p class="ribde">уникальность текста</p>
                                </div>
                            </div>

                            <div class="variant_plag clearfix">
                                <label class="container_ch"><img src="catalog/assets/img/antiplagiat.svg"> Антиплагиат
                                    <input type="checkbox" name="plagiarism[]" value="Антиплагиат">
                                    <span class="checkmark"></span>
                                </label>
                                <label class="container_ch"><img src="catalog/assets/img/eTXT.svg"> eTXT
                                    <input type="checkbox" name="plagiarism[]" value="eTXT">
                                    <span class="checkmark"></span>
                                </label>
                                <label class="container_ch"><img src="catalog/assets/img/logo-ap-vuz.svg"> Антиплагиат.ВУЗ <a data-toggle="modal" data-target="#plag1"><span>?</span></a>
                                    <input type="checkbox" name="plagiarism[]" value="Антиплагиат.ВУЗ">
                                    <span class="checkmark"></span>
                                </label>
                                <label class="container_ch"><img src="catalog/assets/img/rukon.png"> Руконтекст <a data-toggle="modal" data-target="#plag2"><span>?</span></a>
                                    <input type="checkbox" name="plagiarism[]" value="Руконтекст">
                                    <span class="checkmark"></span>
                                </label>
                            </div>

                            <div class="coast_pan clearfix">
                                <div class="wrap_price">
                                    <input type="text" name="price" placeholder="стоимость" value="{{ old('price', session('order.price', '')) }}">
                                    <p>Сумма, которую вы готовы заплатить эксперту</p>
                                </div>
                                <div class="rigt_go btn-loader-wrapper">
                                    <button id="form-submit">Разместить заказ</button>
                                </div>
                            </div>

                        </form>
                    </div>
                </div>

                @include("components.right_sidebar")
            </div>
        </div>
    </div>



    <!-- plag1 -->
    <div class="modal fade" id="plag1" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="myModalLabel">Антиплагиат.ВУЗ </h4>
                </div>
                <div class="modal-body">
                    <p>Необходимо предоставить доступ к системе Антиплагиат.ВУЗ</p>
                </div>
            </div>
        </div>
    </div>



    <!-- plag2 -->
    <div class="modal fade" id="plag2" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="myModalLabel">Руконтекст</h4>
                </div>
                <div class="modal-body">
                    <p>Необходимо предоставить доступ к системе Руконтекст</p>
                </div>
            </div>
        </div>
    </div>
@endsection


