





$( document ).ready(function() {
    function getWindowWidth() {
        return window.innerWidth || document.body.clientWidth;
    }
    if (getWindowWidth() <= 767) {
    function flex3() {
            $('.unviewed').removeClass('unviewed');
        }
    }

    /*setTimeout(flex3, 2000);*/


    activateMenuItem();
});

function activateMenuItem()
{
    $('header .menu_drest li').each(function () {
        if (location.href === $(this).find('a').attr('href') || location.pathname === $(this).find('a').attr('href')) {
            $(this).find('a').addClass('active');
        }
    });

    $('header .menu_drestt li').each(function () {
        if (location.href === $(this).find('a').attr('href') || location.pathname === $(this).find('a').attr('href')) {
            $(this).find('a').addClass('active');
        }
    });
}










function givePadding(){
    console.log('givePadding()');
    var getvalue = $('#message-form.footer_chat').css("height");
  //  $('#downChat.content_chat').css("padding-bottom", getvalue );
}



$(document).ready(function(){







    $(document).on('click', '.btn_hide_submenu', function(){

        $('.btn_catcher').addClass('open');
        $('.btn_hide_submenu').addClass('open_btn');

    });
    $(document).on('click', '.btn_hide_submenu.open_btn', function(){

        $('.btn_catcher').removeClass('open');
        $('.btn_hide_submenu.open_btn').removeClass('open_btn');

    });



    $(document).mouseup(function (e){
        var menu_nav = $(".menu_nav");
        var left_content_resp = $(".left_content_resp");
        var right_content_resp = $(".right_content_resp");
        var btn_catcher = $(".btn_catcher");



        if (!menu_nav.is(e.target)
            && menu_nav.has(e.target).length === 0) {
            $('.menu_nav .toogle_menu.active').removeClass('active')
            $('.menu_nav .menu_drest.active').removeClass('active')
        }
        if (!left_content_resp.is(e.target)
            && left_content_resp.has(e.target).length === 0) {
            $('.left_content_resp.open_left').removeClass('open_left')
        }

        if (!right_content_resp.is(e.target)
            && right_content_resp.has(e.target).length === 0) {
            $('.right_content_resp.open_right').removeClass('open_right')

        }

        if (!btn_catcher.is(e.target)
            && btn_catcher.has(e.target).length === 0) {
            $('.btn_catcher .btn_hide_submenu.open_btn').removeClass('open_btn')
            $('.btn_catcher.open').removeClass('open')
        }



    });











    $('#textunsver').on('keyup', function(){
        if(this.scrollTop > 0){
            this.style.height = this.scrollHeight + "px";
        }
    });

    $("#order-search select[name=filter_section_id]").select2({
        templateResult: setCurrency,
        templateSelection: setCurrency
    });


    $(".temelect2").select2({
        templateResult: setCurrency,
        width: '100%',
        templateSelection: setCurrency
    });



    $("#order-search select[name=filter_subject_id]").select2({
        templateResult: setCurrency,
        templateSelection: setCurrency
    });

    $("#order-search select[name=filter_work_type_id]").select2({
        templateResult: setCurrency,
        templateSelection: setCurrency
    });



    $(".templatingSelect3").select2({
        templateResult: setCurrency,
        width: '95%',
        templateSelection: setCurrency
    });





    $(".templatingSelect4").select2({
        templateResult: setCurrency,
        width: '100%',
        templateSelection: setCurrency
    });



    $(".templatingSelect5").select2({
        templateResult: setCurrency,
        width: '35%',
        templateSelection: setCurrency
    });

    $(".templatingSelect8").select2({
        templateResult: setCurrency,
        width: '90%',
        templateSelection: setCurrency
    });

    $(".templatingSelect9").select2({
        templateResult: setCurrency,
        width: '100%',
        templateSelection: setCurrency
    });


    $(".wrap_user_change select.templa23").select2({
        templateResult: setCurrency,
        width: '100%',
        templateSelection: setCurrency
    });


    $("#formLand select.tempSelectLand").select2({
        templateResult: setCurrency,
        width: '100%',
        templateSelection: setCurrency
    });

   $(".filtr_search select.templa2").select2({
        templateResult: setCurrency,
        width: '100%',
        templateSelection: setCurrency
    });


   $(".giveSelect2").select2({
        templateResult: setCurrency,
        width: '100%',
        templateSelection: setCurrency
    });




    $(document).ready(function(){
        $("input#openVAr").change(function(){
            if ($(this).prop('checked')) {
                $('.variant_plag').fadeIn().show();
                $('.hide_select').fadeIn().show();
                $('.hide_select_procent').prop('disabled', false)

            } else {
                $('.variant_plag').fadeOut(300);
                $('.hide_select').fadeOut(300);
                $('.hide_select_procent').prop('disabled', true)

            }
        });
    })


});

function setCurrency (currency) {
    if (!currency.id) { return currency.text; }
    var $currency = $('<span class="glyphicon glyphicon-' + currency.element.value + '">' + currency.text + '</span>');
    return $currency;
}

$(document).on('change', '#order-search select[name=filter_section_id]', function () {
    getSubjects($(this).val());
});

function getSubjects(section_id){
    $.ajax({
        url : 'index.php?route=order/subject/autocomplete&filter_section_id=' + section_id,
        dataType : 'json',
        success : function (json) {
            $('#order-search select[name=filter_subject_id]').prop('disabled', true);
            $('#order-search select[name=filter_subject_id]').html('<option value="0">' + json['text_all_subject'] + '</option>');
            if(json['subject'].length > 0) {
                for(var i in json['subject']) {
                    $('#order-search select[name=filter_subject_id]').append('<option value="' + json['subject'][i]['subject_id'] + '">' + json['subject'][i]['name'] + '</option>');
                }
                $('#order-search select[name=filter_subject_id]').prop('disabled', false);
            }
            $("#order-search select[name=filter_subject_id]").select2("destroy").select2({
                templateResult: setCurrency,
                templateSelection: setCurrency
            });
            $('#account-order select[name=filter_subject_id]').prop('disabled', true);
            $('#account-order select[name=filter_subject_id]').html('<option value="0">' + json['text_all_subject'] + '</option>');
            if(json['subject'].length > 0) {
                for(var i in json['subject']) {
                    $('#account-order select[name=filter_subject_id]').append('<option value="' + json['subject'][i]['subject_id'] + '">' + json['subject'][i]['name'] + '</option>');
                }
                $('#account-order select[name=filter_subject_id]').prop('disabled', false);
            }
            $("#account-order select[name=filter_subject_id]").select2("destroy").select2({
                templateResult: setCurrency,
                templateSelection: setCurrency
            });
        }
    });
}

$(document).on('change', '#account-order select[name=filter_section_id]', function () {
    getSubjects($(this).val());
});
// search in header

$(document).on('keyup', '.search_head input', function(e){
    if (e.keyCode == 13){
        var search = $(this).val();
        if (search != '') {
            location.href = 'https://studentik.online/index.php?route=search/search&search=' + encodeURI(search);
        } else {
            location.href = 'https://studentik.online/index.php?route=search/search';
        }
    }
});

// history

var historyLive;
var historyInterval = 1000;
var historyXhr;
var counterMessages = 0;
var counterNotifications = 0;

function connectLongPoll() {
    $.ajax({
        url : 'index.php?route=history/longpoll',
        method : 'GET',
        success : function(json){
            if (json['key'] && json['server'] && json['ts']) {
                connectHistory(json['server'], json['ts']);
            }
        }
    });
}

function connectHistory(server, ts) {

    if (historyXhr) {
        historyXhr.abort();
    }

    if (!ts) {
        ts = 0;
    }

    historyXhr = $.ajax({
        url : server + '&ts=' + ts,
        method : 'POST',
        data : {
            ts : ts
        },
        success : function(json){
            if (json['history']) {
                for (var h of json['history']) {
                    /*console.log(h);*/
                    /*if (h['code'] == 'notification_new') {

                    }
                    if (h['code'] == 'notification_read') {

                    }*/
                    /* Проверка является ли страница чатом */

                    if (h['code'] == 'message_read') {
                        if ($('.container_chat').length > 0) {
                            $('#message-' + h['object']['message_id']).removeClass('unviewed');
                        }
                    }

                    if (h['code'] == 'message_new') {
                        if (h['object'] && h['object']['chat_id'] && getURLVar('chat_id') != h['object']['chat_id']) {
                            beep();
                        } else {
                            setMessage(h['object']);
                            var objDiv = document.getElementById("downChat");
                            if (objDiv.scrollHeight - objDiv.offsetHeight - 250 < objDiv.scrollTop) {
                                scrollChatContentToDown();
                            }
                        }
                    }

                    if ($('.container_chat').length > 0) {
                        if (h['code'] == 'chat_viewed') {
                            $('#chat-' + h['object']['chat_id']).find('.no_reed').remove();
                        }

                        if (h['code'] == 'chat_read') {
                            $('#chat-' + h['object']['chat_id']).removeClass('new_massage');
                        }

                        if (h['code'] == 'chat_update') {
                            setChat(h['object']);
                        }
                    }
                }
            }

            if (json['ts']) {
                ts = parseInt(json['ts']);
            }

            if (json['counter']) {
                if (json['counter']['messages'] !== counterMessages) {
                    counterMessages = parseInt(json['counter']['messages']);
                    $('header .msg > a span').remove();
                    if (counterMessages > 0) {
                        $('header .msg > a').append('<span>' + counterMessages + '</span>');
                    }
                }

                if (json['counter']['notifications'] !== counterNotifications) {
                    counterNotifications = parseInt(json['counter']['notifications']);
                    $('header .cos > a span').remove();
                    if (counterNotifications > 0) {
                        $('header .cos > a').append('<span>' + counterNotifications + '</span>');
                    }
                }
            }

            if (json['error']) {
                connectLongPoll();
                return;
            }

            connectHistory(server, ts);
        },
        error : function (error) {
            console.error(error);
            connectHistory(server, ts);
        }
    });
}



function setMessage(message){

    var text = observeText(message.text);

    var temp = '<div id="message-' + message.message_id + '" class="massage user clearfix ' + (message.viewed == 0 ? 'unviewed' : '') + '" ' + (message.is_sender == 0 && message.viewed == 0 ? 'onmouseover="viewedMessage(' + message.message_id + ', this);"' : '') + ' data-message_id="' + message.message_id + '">\n' +
        '            <div class="logo_my">\n' +
        '                <img src="' + message.image + '">\n' +
        (message.online ? '<span></span>\n' : '') +
        '            </div>\n' +
        '            <div class="login_name">\n' +
        '                <a href="' + message.href + '">' + message.name + '</a>\n' +
        '            </div>\n' +
        '            <div class="time_my">\n' +
        '                <span>' + message.date_added + '</span>\n' +
        '            </div>\n' +
        '            <div class="massage_txt">\n' +
        '                <span>' + text + '</span>\n' +
        '            </div>\n' +
        '            <div class="massage_attachments">\n';
    if (message.attachment) {
        for (var i in message.attachment) {
            temp += '<div class="clearfix file_item">\n' +
                '    <div class="logo_file">\n' +
                '       <img src="catalog/assets/img/file/' + message.attachment[i].type + '.png">\n' +
                '    </div>\n' +
                '    <div class="left_cop">' +
                '       <span>' + message.attachment[i].name + '</span>\n' +
                '       <span class="weigt">' + message.attachment[i].size + '</span>\n' +
                '    </div>\n' +
                '    <div class="right_cop">\n' +
                '       <span>' + message.attachment[i].date_added + '</span>\n' +
                '       <p>\n' +
                '           <a href="' + message.attachment[i].upload + '">Загрузить файл</a>\n' +
                '       </p>\n' +
                '     </div>\n' +
                '</div>\n';
        }
    }
    temp +=  '</div></div>';
    if ($('#message-' + message.message_id).length > 0) {
        $('#message-' + message.message_id).replaceWith(temp);
    } else {
        $('#downChat').append(temp);
    }
}

function setChat(chat) {
    if(!chat['chat_id']) return;

    var temp = '<div id="chat-' + chat['chat_id'] + '" class="speeker_numb clearfix '  + (getURLVar('chat_id') == chat['chat_id'] ? ' active': '')  + (parseInt(chat.unviewed) > 0 ? ' new_massage': '') + '" data-chat_id="' + chat.chat_id + '">\n' +
        '    <div class="logo">\n' +
        '        <img src="' + chat.image + '">\n' +
        (chat.online == 1 ? '<span></span>\n' : '') +
        '    </div>\n' +
        '    <div class="name clearfix">\n' +
        '        <a href="' + chat.href + '">' + chat.name + '</a>\n' +
        '        <span>' + chat.date_added + '</span>\n' +
        '        <p>' + chat.text + '</p>\n' +
        (chat['unread'] ? '<i class="no_reed"></i>' : '') +
        '    </div>\n' +
        '    <div class="delete">\n' +
        '        <button class="delete_file">\n' +
        '            <span>x</span>\n' +
        '        </button>\n' +
        '    </div>\n' +
        '</div>';

    $('#chat-' + chat.chat_id).remove();
    $('.list_speeker').prepend(temp);
}

function beep()
{
    var audio = new Audio();
    audio.src = "../catalog/assets/sound/s2.wav";
    audio.autoplay = true;
}

function observeText(text)
{
    var found = /~[a-z]+:\/\/\S+~/ig.exec(text);

    if (found) {
        return text.replace(found[0], '<a href="' + found[0] + '" target="_blank">' + found[0] + '</a>');
    }

    return text;
}

function renderUploadedFile(file){

    return '<div class="clearfix file_item">\n' +
        '<div class="logo_file"><img src="catalog/assets/img/file/' + file['type'] + '.png"></div>\n' +
        '    <div class="left_cop">\n' +
        '        <span>' + file['name'] + '</span>\n' +
        '        <span class="weigt">' + file['size'] + '</span>\n' +
        '        <input type="hidden" name="attachment[]" value="' + file['attachment_id'] + '">\n' +
        '    </div>\n' +
        '    <div class="right_cop">\n' +
        '        <span>' + file['date_added'] + '</span>\n' +
        '        <p class="delete_file_cust clearfix">\n' +
        '            <button class="delete_file">\n' +
        '               <svg xmlns="http://www.w3.org/2000/svg" height="329pt" viewBox="0 0 329.26933 329" width="329pt"><path d="m194.800781 164.769531 128.210938-128.214843c8.34375-8.339844 8.34375-21.824219 0-30.164063-8.339844-8.339844-21.824219-8.339844-30.164063 0l-128.214844 128.214844-128.210937-128.214844c-8.34375-8.339844-21.824219-8.339844-30.164063 0-8.34375 8.339844-8.34375 21.824219 0 30.164063l128.210938 128.214843-128.210938 128.214844c-8.34375 8.339844-8.34375 21.824219 0 30.164063 4.15625 4.160156 9.621094 6.25 15.082032 6.25 5.460937 0 10.921875-2.089844 15.082031-6.25l128.210937-128.214844 128.214844 128.214844c4.160156 4.160156 9.621094 6.25 15.082032 6.25 5.460937 0 10.921874-2.089844 15.082031-6.25 8.34375-8.339844 8.34375-21.824219 0-30.164063zm0 0"/></svg>\n' +
        '            </button>\n' +
        '        </p>\n' +
        '    </div>\n' +
        '</div>';
}

function renderUploadedFileWithDownload(file){

    return '<div class="clearfix file_item">\n' +
        '    <div class="logo_file">\n' +
        '       <img src="../catalog/assets/img/file/' + file['type'] + '.png">\n' +
        '    </div>' +
        '    <div class="left_cop">\n' +
        '        <span>' + file['name'] + '</span>\n' +
        '        <span class="weigt">' + file['size'] + '</span>\n' +
        '    </div>\n' +
        '    <div class="right_cop">\n' +
        '        <span>' + file['date_added'] + '</span>\n' +
        '        <p class="delete_file_cust clearfix">\n' +
        '            <!--<a href="' + file['href'] + '">{{ button_view }}</a>\n-->' +
        '            <a href="' + file['upload'] + '">Загрузить файл</a>\n' +
        '            <button class="delete_file" data-attachment-id="' + file['attachment_id'] + '">\n' +
        '                 <svg xmlns="http://www.w3.org/2000/svg" height="329pt" viewBox="0 0 329.26933 329" width="329pt"><path d="m194.800781 164.769531 128.210938-128.214843c8.34375-8.339844 8.34375-21.824219 0-30.164063-8.339844-8.339844-21.824219-8.339844-30.164063 0l-128.214844 128.214844-128.210937-128.214844c-8.34375-8.339844-21.824219-8.339844-30.164063 0-8.34375 8.339844-8.34375 21.824219 0 30.164063l128.210938 128.214843-128.210938 128.214844c-8.34375 8.339844-8.34375 21.824219 0 30.164063 4.15625 4.160156 9.621094 6.25 15.082032 6.25 5.460937 0 10.921875-2.089844 15.082031-6.25l128.210937-128.214844 128.214844 128.214844c4.160156 4.160156 9.621094 6.25 15.082032 6.25 5.460937 0 10.921874-2.089844 15.082031-6.25 8.34375-8.339844 8.34375-21.824219 0-30.164063zm0 0"></path></svg>\n' +
        '            </button>' +
        '        </p>\n' +
        '    </div>\n' +
        '</div>';
}
