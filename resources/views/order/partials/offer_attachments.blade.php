<div class="look_files">
    <h3>Готовая работа</h3>
    <div id="files-offer">
        @if($order->offerAttachments)
            @foreach($order->offerAttachments as $attachment)
                <div class="clearfix file_item">
                    <div class="logo_file">
                        <img src="{{ asset('catalog/assets/img/file/' . $attachment->type .'.png') }}">
                    </div>
                    <div class="left_cop">
                        <span>{{ $attachment->name }}</span>
                        <span class="weigt">{{ $attachment->size }}</span>
                    </div>
                    <div class="right_cop">
                        <span>{{ $attachment->date_added }}</span>
                        <p class="delete_file_cust clearfix">
                            <a href="{{ $attachment->upload }}">Загрузить файл</a>
                            @if($order->offerAssigned->isOwner())
                                <button class="delete_file delete_offer_file" data-attachment-id="{{ $attachment->attachment_id }}">
                                    <svg xmlns="http://www.w3.org/2000/svg" height="329pt" viewBox="0 0 329.26933 329" width="329pt"><path d="m194.800781 164.769531 128.210938-128.214843c8.34375-8.339844 8.34375-21.824219 0-30.164063-8.339844-8.339844-21.824219-8.339844-30.164063 0l-128.214844 128.214844-128.210937-128.214844c-8.34375-8.339844-21.824219-8.339844-30.164063 0-8.34375 8.339844-8.34375 21.824219 0 30.164063l128.210938 128.214843-128.210938 128.214844c-8.34375 8.339844-8.34375 21.824219 0 30.164063 4.15625 4.160156 9.621094 6.25 15.082032 6.25 5.460937 0 10.921875-2.089844 15.082031-6.25l128.210937-128.214844 128.214844 128.214844c4.160156 4.160156 9.621094 6.25 15.082032 6.25 5.460937 0 10.921874-2.089844 15.082031-6.25 8.34375-8.339844 8.34375-21.824219 0-30.164063zm0 0"></path></svg>
                                </button>
                            @endif
                        </p>
                    </div>
                </div>
            @endforeach
        @endif
    </div>
    @if($order->offerAssigned and $order->offerAssigned->isOwner())
        <script>
            $(document).on('click', '#files-offer .file_item .delete_offer_file', function(){
                var attachment_id = parseInt($(this).data('attachment-id'));

                if (attachment_id > 0) {
                    $(this).parents('.file_item').remove();
                    $.ajax({
                        url: '../index.php?route=order/order/deleteOfferAttachment&order_id={{ $order->order_id }}',
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
    @endif

    @if($order->offerAssigned and $order->offerAssigned->isOwner() and $order->isOrderStatusInArray([3, 4, 5, 6, 7]))
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
                            url: '{{ asset('index.php?route=common/upload/upload') }}',
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
                                                '            <a href="' + json['files'][i]['upload'] + '">Загрузить файл</a>\n' +
                                                '        </p>\n' +
                                                '    </div>\n' +
                                                '</div>';
                                            $('#files-offer').append(tmpl);

                                            $.ajax({
                                                url: '{{ asset('index.php?route=order/order/addOfferAttachment&order_id=' .$order->order_id) }}',
                                                type: 'post',
                                                dataType: 'json',
                                                data: {
                                                    attachment_id: json['files'][i]['attachment_id']
                                                },
                                                success: function(json) {
                                                    $('#files-offer').load(location.href + ' #files-offer > div');
                                                }
                                            });
                                            @if($order->isOrderStatusInArray([3, 7]))
                                            $.ajax({
                                                url : '{{ asset("index.php?route=order/offer/awaitingOffer&order_id=". $order->order_id) }}',
                                                method : 'GET',
                                                success : function (json) {
                                                    if (json['success']) {
                                                        //location.reload();
                                                        $('#content').load(location.href + ' #content > div');
                                                        alertSuccess(json['success']);
                                                    }
                                                }
                                            });
                                            @endif
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
    @endif
</div>
