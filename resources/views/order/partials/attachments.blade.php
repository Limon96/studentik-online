<div class="look_files">
    <h3>Прикрепленные файлы</h3>
    <div id="files">
        @foreach($order->attachments as $attach)
            <div class="clearfix file_item">
                <div class="logo_file">
                    <img src="{{ asset('catalog/assets/img/file/' . $attach->type . '.png') }}">
                </div>
                <div class="left_cop">
                    <span>{{ $attach->name }}</span>
                    <span class="weigt">{{ format_size($attach->size) }}</span>
                </div>
                <div class="right_cop">
                    <span>{{ format_date($attach->date_added, 'full_datetime') }}</span>
                    <p class="delete_file_cust clearfix">
                        <!-- <a href="{{ $attach->href }}">Просмотр</a>-->
                        <a href="{{ $attach->upload }}">Загрузить файл</a>
                        @if($order->isOwner())
                            <button class="delete_file" data-attachment-id="{{ $attach->attachment_id }}">
                                <svg xmlns="http://www.w3.org/2000/svg" height="329pt" viewBox="0 0 329.26933 329" width="329pt"><path d="m194.800781 164.769531 128.210938-128.214843c8.34375-8.339844 8.34375-21.824219 0-30.164063-8.339844-8.339844-21.824219-8.339844-30.164063 0l-128.214844 128.214844-128.210937-128.214844c-8.34375-8.339844-21.824219-8.339844-30.164063 0-8.34375 8.339844-8.34375 21.824219 0 30.164063l128.210938 128.214843-128.210938 128.214844c-8.34375 8.339844-8.34375 21.824219 0 30.164063 4.15625 4.160156 9.621094 6.25 15.082032 6.25 5.460937 0 10.921875-2.089844 15.082031-6.25l128.210937-128.214844 128.214844 128.214844c4.160156 4.160156 9.621094 6.25 15.082032 6.25 5.460937 0 10.921874-2.089844 15.082031-6.25 8.34375-8.339844 8.34375-21.824219 0-30.164063zm0 0"></path></svg>
                            </button>
                        @endif
                    </p>
                </div>
            </div>
        @endforeach
    </div>
    @if($order->isOwner())
        <div class="upload-file-errors errors"></div>
        <div>
            <form id="form-upload" class="file_clear">
                <label>
                    <p>
                        <svg style="position: relative;width: 10px" viewBox="0 0 384 512" xmlns="http://www.w3.org/2000/svg" class="sw-icon">
                            <path d="M288 248v28c0 6.6-5.4 12-12 12H108c-6.6 0-12-5.4-12-12v-28c0-6.6 5.4-12 12-12h168c6.6 0 12 5.4 12 12zm-12 72H108c-6.6 0-12 5.4-12 12v28c0 6.6 5.4 12 12 12h168c6.6 0 12-5.4 12-12v-28c0-6.6-5.4-12-12-12zm108-188.1V464c0 26.5-21.5 48-48 48H48c-26.5 0-48-21.5-48-48V48C0 21.5 21.5 0 48 0h204.1C264.8 0 277 5.1 286 14.1L369.9 98c9 8.9 14.1 21.2 14.1 33.9zm-128-80V128h76.1L256 51.9zM336 464V176H232c-13.3 0-24-10.7-24-24V48H48v416h288z"></path>
                        </svg>
                    </p>
                    <input type="file" name="file[]" multiple="multiple" accept=".bmp,.djvu,.doc,.docx,.dwg,.csv,.gif,.jpeg,.jpg,.odf,.odt,.pdf,.png,.ppt,.pptx,.rar,.rtf,.svg,.tga,.tiff,.txt,.xls,.xlsx,.zip,.a3d,.cdt,.cdw,.m3d,.dwf,.cdr,.ai,.ics">
                    <span> Добавить файл </span>
                </label>
            </form>
        </div>
        <script>
            $(document).on('change', '#form-upload input', function(){
                if (typeof timer != 'undefined') {
                    clearInterval(timer);
                }

                timer = setInterval(function() {
                    if ($('#form-upload input[type=file]').val() != '') {
                        clearInterval(timer);

                        $.ajax({
                            url: '../index.php?route=common/upload/upload',
                            type: 'post',
                            dataType: 'json',
                            data: new FormData($('#form-upload')[0]),
                            cache: false,
                            contentType: false,
                            processData: false,
                            beforeSend: function() {
                                $('#button-upload i').replaceWith('<i class="fa fa-circle-o-notch fa-spin"></i>');
                                $('#button-upload').prop('disabled', true);
                                $('#form-upload').btnLoading('on');
                            },
                            complete: function() {
                                $('#button-upload i').replaceWith('<i class="fa fa-upload"></i>');
                                $('#button-upload').prop('disabled', false);

                                $('#form-upload').btnLoading('off');
                            },
                            success: function(json) {
                                if (json['error']) {
                                    $('.upload-file-errors.errors').html('');
                                    if (json['error']) {
                                        for (var error of json['error']) {
                                            $('.upload-file-errors.errors').append('<div class="error">' + error + '</div>');
                                        }
                                    }
                                }

                                if (json['success']) {
                                    if (json['files']) {
                                        for (var i in json['files']) {
                                            var tmpl = renderUploadedFileWithDownload(json['files'][i]);
                                            $('#files').append(tmpl);

                                            $.ajax({
                                                url: '../index.php?route=order/order/addAttachment&order_id={{ $order->order_id }}',
                                                type: 'post',
                                                dataType: 'json',
                                                data: {
                                                    attachment_id: json['files'][i]['attachment_id']
                                                },
                                                success: function(json) {
                                                    console.log(json);
                                                }
                                            });
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
                var attachment_id = parseInt($(this).data('attachment-id'));

                if (attachment_id > 0) {
                    $(this).parents('.file_item').remove();
                    $.ajax({
                        url: '../index.php?route=order/order/deleteAttachment&order_id={{ $order->order_id }}',
                        type: 'post',
                        dataType: 'json',
                        data: {
                            attachment_id: attachment_id
                        },
                        success: function(json) {
                            console.log(json);
                        }
                    });
                }
            });
        </script>
    @endif
</div>
