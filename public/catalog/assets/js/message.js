var live = '';
var xhr = '';
var interval = 1000;
$(document).ready(function(){
    scrollChatContentToDown();
    givePadding();
});

$(document).on('focus', '#message-form textarea', function(e){
    var chat_id = parseInt($('#message-form button[data-chat_send]').data('chat_send'));
    viewedChat(chat_id);
});

$(document).on('focusin', '#message-form textarea', function(e){
    var chat_id = parseInt($('#message-form button[data-chat_send]').data('chat_send'));
    viewedChat(chat_id);
});

$(document).on('keyup', function(e){
    if (e.keyCode == 27) {
        $('.speeker_numb').removeClass('active');
        changeChatURL(0);
        getChatContent(0);
    }
});

$(document).on('keyup', '#message-form textarea', function(e){
    if (e.keyCode == 13 && e.ctrlKey) {
        e.preventDefault();
        $('#message-form button[data-chat_send]').trigger('click');
    }
});

$(document).on('click', 'button.all_chat', function (e) {
    e.preventDefault();
    $.ajax({
        url : '../index.php?route=message/chat/searchChats',
        method : 'POST',
        data : {
            search : ''
        },
        success : function (json) {
            if (json['chats']) {
                $('.list_speeker').html('');

                json['chats'].reverse();

                for (var chat of json['chats']) {
                    setChat(chat);
                }
            }
        }
    });
});

$(document).on('click', 'button.no_read', function (e) {
    e.preventDefault();
    $.ajax({
        url : '../index.php?route=message/chat/searchChats',
        method : 'POST',
        data : {
            search : '',
            unviewed : 1
        },
        success : function (json) {
            if (json['chats']) {
                $('.list_speeker').html('');

                json['chats'].reverse();

                for (var chat of json['chats']) {
                    setChat(chat);
                }
            }
        }
    });
});

$(document).on('keyup', '#chat-search input[name=search]', function (e) {
    var search = $(this).val();

    getChats(1, search, function (json) {
        if (json['chats']) {
            $('.list_speeker').html('');

            for (var chat of json['chats']) {
                setChat(chat);
            }
        }
    })
});

function loadChats(page, button) {
    var search = $('#chat-search input[name=search]').val();
    $(button).remove();

    getChats(page, search, function (json) {
        if (json['chats']) {

            for (var chat of json['chats']) {
                appendChat(chat);
            }

            if (json['chats'].length === json['limit']) {
                $('.list_speeker').append('<button class="load-chat" onclick="loadChats(' + (parseInt(page) + 1) +  ', this);">Показать еще</button>');
            }
        }
    })
}

function getChats(page, search, callback){
    $.ajax({
        url : '../index.php?route=message/chat/searchChats',
        method : 'POST',
        data : {
            search : search,
            page : page
        },
        success : function (json) {
            callback(json);
        }
    });
}

$('#downChat').scroll(function (e) {
    e.preventDefault();
    if (e.target.scrollTop < 250) {
        $('.load-message').click();
    }
});

$(document).on('submit', '#chat-search', function (e) {
    e.preventDefault();
});

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
                },
                complete: function() {
                    $('#button-upload i').replaceWith('<i class="fa fa-upload"></i>');
                    $('#button-upload').prop('disabled', false);
                },
                success: function(json) {
                    if (json['error']) {
                        console.log(json['error']);
                    }

                    if (json['success']) {
                        if (json['files']) {
                            for (var i in json['files']) {
                                var tmpl = '<div class="clearfix file_item">\n' +
                                    '    <div class="logo_file">' +
                                    '       <img src="../catalog/assets/img/file/' + json['files'][i]['type'] + '.png">' +
                                    '    </div>\n' +
                                    '    <div class="left_cop">\n' +
                                    '        <span>' + json['files'][i]['name'] + '</span>\n' +
                                    '        <span class="weigt">' + json['files'][i]['size'] + '</span>\n' +
                                    '        <input type="hidden" name="attachment[]" value="' + json['files'][i]['attachment_id'] + '">\n' +
                                    '    </div>\n' +
                                    '    <div class="right_cop">\n' +
                                    '        <p class="delete_file_cust clearfix">\n' +
                                    '            <button class="delete_file"><svg xmlns="http://www.w3.org/2000/svg" id="Layer_1" enable-background="new 0 0 512 512" height="512" viewBox="0 0 512 512" width="512"><g><path d="m424 64h-88v-16c0-26.51-21.49-48-48-48h-64c-26.51 0-48 21.49-48 48v16h-88c-22.091 0-40 17.909-40 40v32c0 8.837 7.163 16 16 16h384c8.837 0 16-7.163 16-16v-32c0-22.091-17.909-40-40-40zm-216-16c0-8.82 7.18-16 16-16h64c8.82 0 16 7.18 16 16v16h-96z"/><path d="m78.364 184c-2.855 0-5.13 2.386-4.994 5.238l13.2 277.042c1.22 25.64 22.28 45.72 47.94 45.72h242.98c25.66 0 46.72-20.08 47.94-45.72l13.2-277.042c.136-2.852-2.139-5.238-4.994-5.238zm241.636 40c0-8.84 7.16-16 16-16s16 7.16 16 16v208c0 8.84-7.16 16-16 16s-16-7.16-16-16zm-80 0c0-8.84 7.16-16 16-16s16 7.16 16 16v208c0 8.84-7.16 16-16 16s-16-7.16-16-16zm-80 0c0-8.84 7.16-16 16-16s16 7.16 16 16v208c0 8.84-7.16 16-16 16s-16-7.16-16-16z"/></g></svg></button>\n' +
                                    '        </p>\n' +
                                    '    </div>\n' +
                                    '</div>';
                                $('#files').append(tmpl);

                                givePadding();
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

$(document).on('click', '*[data-chat_id]', function(e){
    e.preventDefault();
    if (!$(this).hasClass('active')) {
        $('.speeker_numb').removeClass('active');
        $(this).addClass('active');
        var chat_id = parseInt($(this).data('chat_id'));
        changeChatURL(chat_id);
        getChatContent(chat_id);
    }
});

$(document).on('click', '#message-form button[data-chat_send]', function (e) {
    e.preventDefault();
    var $that = $(this);

    var text = $('#message-form').find('textarea').val().trim();
    var files = $('#files').find('input[name="attachment[]"]').val();

    var chat_id = parseInt($(this).data('chat_send'));
    if (!chat_id) chat_id = 0;

    if (chat_id && (text != '' || files)) {

        viewedChat(chat_id);

        $.ajax({
            url : '../index.php?route=message/chat/send&chat_id=' + chat_id,
            method : 'POST',
            data : $('#message-form textarea, #files input[type=hidden]'),
            beforeSend : function (){
                $that.btnLoader('on');
            },
            success : function (json) {
                if (json['error']) {
                    console.log(json['error']);
                }

                if (json['message']) {
                    $('#message-form').find('textarea').val('');
                    $('#files').html('');
                    setMessage(json['message']);
                    scrollChatContentToDown();
                }

                $that.btnLoader('off');
            }
        });
    } else {
        $('#message-form').find('textarea').focus();
    }
});

function getChatContent(chat_id){
    $.ajax({
        url : '../index.php?route=message/chat/chat' + (chat_id ? '&chat_id=' + chat_id: ''),
        method : 'GET',
        success : function (html) {
            $('.chat_content').replaceWith(html);
            if (chat_id) {
                scrollChatContentToDown();
                givePadding();
                //connectLive(chat_id);
            } else {
                $('.speeker_numb').removeClass('active');
                //connectLive(0);
            }
        }
    });
}


function getChatContentPage(chat_id, page = 1){
    var temp = $('#downChat .massage');
    $('#downChat').load('../index.php?route=message/chat/chat' + (chat_id ? '&chat_id=' + chat_id: '') + (page ? '&page=' + page: '') + ' #downChat > *', function(response, status, xhr) {
        scrollChatContentToDown();
        $('#downChat').append(temp);
    });
}

function changeChatURL(chat_id){
    //var href = '../index.php?route=message/chat';
    var href = '../messages';
    if (chat_id) {
        href += '?chat_id=' + chat_id;
    }
    history.pushState(null, null, href);
}

function scrollChatContentToDown(){
    var objDiv = document.getElementById("downChat");
    if (objDiv) {
        objDiv.scrollTop = objDiv.scrollHeight;
    }
}

window.addEventListener("popstate", function(e) {
    var chat_id = parseInt(getURLVar('chat_id'));
    if (!chat_id) chat_id = 0;
    getChatContent(chat_id);
}, false);

function viewedMessage(message_id, that) {
    $(that).removeClass('unviewed');
    $(that).removeAttr('onmouseover');

    $.ajax({
        url : '../index.php?route=message/chat/viewedMessage&message_id=' + message_id,
        method : 'GET',
        dataType : 'json',
        success : function (json) {

        }
    });
}

function viewedChat(chat_id) {
    $.ajax({
        url : '../index.php?route=message/chat/viewedChat&chat_id=' + chat_id,
        method : 'GET',
        dataType : 'json',
        success : function (json) {

        }
    });
}
