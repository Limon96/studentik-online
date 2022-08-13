var live, xhr, interval = 600;

$(document).on('click', '.assign_offer', function(){
    var order_id = parseInt($(this).data('order_id'));
    var offer_id = parseInt($(this).data('offer_id'));

    if (order_id && offer_id) {
        $.ajax({
            url : '../index.php?route=order/offer/offer&order_id=' + order_id + '&offer_id=' + offer_id,
            method : 'GET',
            success : function (html) {
                $('#take_man .modal-body').html(html);
                $('#take_man').modal('show');
            }
        });
    }
});

$(document).on('click', '#assign_offer', function(e){
    e.preventDefault();

    var order_id = parseInt($(this).data('order_id'));
    var offer_id = parseInt($(this).data('offer_id'));

    if (order_id && offer_id) {

        $('#take_man').modal('hide');

        $.ajax({
            url : '../index.php?route=order/offer/assignOffer&order_id=' + order_id + '&offer_id=' + offer_id,
            method : 'GET',
            success : function (json) {
                if (json['error']) {
                    if (json['error']['balance']) {
                        window.open(json['redirect']);
                    }
                }
                if (json['success']) {
                    //location.reload();
                    $('#content').load(location.href + ' #content > div');
                    $('header .coast').load(location.href + ' header .coast > a');
                    alertSuccess(json['success']);
                }
            }
        });
    }
});

$(document).on('click', '#button-cancel-order', function(e){
    e.preventDefault();

    var order_id = parseInt($(this).data('order_id'));

    if (order_id) {
        $.ajax({
            url : '../index.php?route=order/order/cancel&order_id=' + order_id,
            method : 'GET',
            success : function (json) {
                if (json['success']) {
                    location.reload();
                    //alertSuccess(json['success']);
                }
            }
        });
    }
});

$(document).on('click', '#button-open-order', function(e){
    e.preventDefault();

    var order_id = parseInt($(this).data('order_id'));

    if (order_id) {
        $.ajax({
            url : '../index.php?route=order/order/open&order_id=' + order_id,
            method : 'GET',
            success : function (json) {
                if (json['success']) {
                    location.reload();
                    //alertSuccess(json['success']);
                }
            }
        });
    }
});
$(document).on('click', '#accept_offer', function(e){
    e.preventDefault();

    var order_id = parseInt($(this).data('order_id'));
    var offer_id = parseInt($(this).data('offer_id'));

    if (order_id && offer_id) {
        $.ajax({
            url : '../index.php?route=order/offer/acceptOffer&order_id=' + order_id + '&offer_id=' + offer_id,
            method : 'GET',
            success : function (json) {
                if (json['error']) {
                    if (json['error']['balance']) {
                        location.href = json['redirect'];
                    }
                }
                if (json['success']) {
                    location.reload();
                    alertSuccess(json['success']);
                }
            }
        });
    }
});

$(document).on('click', '.cancel_offer', function(e){
    e.preventDefault();
    var order_id = parseInt($(this).data('order_id'));
    var offer_id = parseInt($(this).data('offer_id'));

    if (order_id && offer_id) {
        $.ajax({
            url : '../index.php?route=order/offer/cancelOffer&order_id=' + order_id + '&offer_id=' + offer_id,
            method : 'GET',
            success : function (json) {
                if (json['success']) {
                    $('#content').load(location.href + ' #content > div');
                    $('header .coast').load(location.href + ' header .coast > a');
                    alertSuccess(json['success']);
                }
            }
        });
    }
});

$(document).on('click', '.edit_my_ofer', function(e){
    e.preventDefault();
    var order_id = parseInt($(this).data('order_id'));
    var offer_id = parseInt($(this).data('offer_id'));

    if (order_id && offer_id) {
        $.ajax({
            url : '../index.php?route=order/offer/info&order_id=' + order_id + '&offer_id=' + offer_id,
            method : 'GET',
            success : function (json) {
                if (json['offer']) {
                    var temp =  '<div class="block_offer_form clearfix">\n' +
                        '                        <div class="block_my_u clearfix">\n' +
                        '                            <input type="hidden" name="order_id" value="' + json['offer']['order_id'] + '">\n' +
                        '                            <input type="hidden" name="offer_id" value="' + json['offer']['offer_id'] + '">\n' +
                        '                            <textarea name="text" id="textunsver" placeholder="Комментарий к заказу без ставки (Необязательное поле)">' + json['offer']['text'] + '</textarea>\n' +
                        '                        </div>\n' +
                        '                        <div class="wrap_paranetrs clearfix">\n' +
                        '                            <div class="lop_lert">\n' +
                        '                                <label for="c1">Ваша ставка</label>\n' +
                        '                                <input type="number" name="bet" placeholder="0" value="' + json['offer']['bet'] + '" id="c1" >\n' +
                        '                                <span class="symbol">\n' +
                        '                                    <svg data-v-597fc010="" viewBox="0 0 384 512" xmlns="http://www.w3.org/2000/svg" class="sw-icon" data-v-79b0c030=""><path d="M243.128 314.38C324.987 314.38 384 257.269 384 172.238S324.987 32 243.128 32H76c-6.627 0-12 5.373-12 12v215.807H12c-6.627 0-12 5.373-12 12v30.572c0 6.627 5.373 12 12 12h52V352H12c-6.627 0-12 5.373-12 12v24c0 6.627 5.373 12 12 12h52v68c0 6.627 5.373 12 12 12h40c6.627 0 12-5.373 12-12v-68h180c6.627 0 12-5.373 12-12v-24c0-6.627-5.373-12-12-12H128v-37.62h115.128zM128 86.572h105.61c53.303 0 86.301 31.728 86.301 85.666 0 53.938-32.998 87.569-86.935 87.569H128V86.572z"></path></svg>\n' +
                        '                                </span>\n' +
                        '                            </div>\n' +
                        '                            <div class="lop_lert">\n' +
                        '                                <label for="c2">Вы получаете</label>\n' +
                        '                                <input type="number" name="earned" placeholder="0" value="' + json['offer']['earned'] + '" id="c2">\n' +
                        '                                <span class="symbol">\n' +
                        '                                    <svg data-v-597fc010="" viewBox="0 0 384 512" xmlns="http://www.w3.org/2000/svg" class="sw-icon" data-v-79b0c030=""><path d="M243.128 314.38C324.987 314.38 384 257.269 384 172.238S324.987 32 243.128 32H76c-6.627 0-12 5.373-12 12v215.807H12c-6.627 0-12 5.373-12 12v30.572c0 6.627 5.373 12 12 12h52V352H12c-6.627 0-12 5.373-12 12v24c0 6.627 5.373 12 12 12h52v68c0 6.627 5.373 12 12 12h40c6.627 0 12-5.373 12-12v-68h180c6.627 0 12-5.373 12-12v-24c0-6.627-5.373-12-12-12H128v-37.62h115.128zM128 86.572h105.61c53.303 0 86.301 31.728 86.301 85.666 0 53.938-32.998 87.569-86.935 87.569H128V86.572z"></path></svg>\n' +
                        '                                </span>\n' +
                        '                            </div>\n' +
                        '                            <div class="lop_lert">\n' +
                        '                                <button class="edit">Отправить</button>\n' +
                        '                            </div>\n' +
                        '                        </div>\n' +
                        '                    </div>';

                    $('#offer .block_my_u').hide();
                    $('#offer .block_offer_form').remove();
                    $('#offer').append(temp);
                }
            }
        });

    }
});

$(document).on('click', '.item_unswer .send_wopr', function(){
    $(".unswer_com.footer_chat").hide();
    $(this).parents('.item_unswer').find('.unswer_com.footer_chat').show();

    var offer_id = parseInt($(this).data('offer_id'));

    if (offer_id) {
        $.ajax({
            url : '../index.php?route=order/message&offer_id=' + offer_id,
            method : 'GET',
            success : function (html) {
                $('#smallChat' + offer_id).html(html);
                connectLive(offer_id);
                scrollOfferContentToDown(offer_id);
            }
        });
    }
});

$(document).on('click', '.item_unswer .btn_send > button', function(e){
    e.preventDefault();
    var textarea = $(this).parents('.text_gg').find('textarea');
    var offer_id = parseInt($(this).data('offer_send'));
    var text = $(textarea).val();
    var $that = $(this);

    if (offer_id && text) {
        $.ajax({
            url : '../index.php?route=order/message/send&offer_id=' + offer_id,
            method : 'POST',
            data : {
                offer_id : offer_id,
                text : text,
            },
            beforeSend : function(){
                $that.btnLoader('on');
            },
            success : function (json) {
                if (json['success']) {
                    connectLive(offer_id);
                    $(textarea).val('');
                }
                $that.btnLoader('off');
            }
        });
    }
});


function connectLive(offer_id) {
    clearInterval(live);
    if (offer_id) {
        var last_offer_message_id = $('#smallChat' + offer_id).children().last().data('offer_message_id');
        if (!last_offer_message_id) {
            last_offer_message_id = 0;
        }
        live = setInterval(function(){
            if (xhr) xhr.abort();

            xhr = $.ajax({
                url : '../index.php?route=order/message/live',
                method : 'POST',
                data : {
                    last_offer_message_id : last_offer_message_id,
                    offer_id : offer_id
                },
                success : function(json){
                    if (json['messages'].length > 0) {
                        for (var i in json['messages']) {
                            setOfferMessage(offer_id, json['messages'][i]);
                        }
                        var objDiv = document.getElementById('smallChat' + offer_id);
                        if (objDiv.scrollHeight - objDiv.offsetHeight - 250 < objDiv.scrollTop) {
                            scrollOfferContentToDown(offer_id);
                        }
                    }
                    connectLive(offer_id);
                }
            });
        }, interval);
    }
}

function setOfferMessage(offer_id, message){
    var temp = '<div id="offer-message-' + message.offer_message_id +'" class="massage user clearfix" data-offer_message_id="' + message.offer_message_id + '">\n' +
        '        <div class="logo_my">\n' +
        '            <img src="' + message.image + '">\n' +
        '        </div>\n' +
        '        <div class="login_name">\n' +
        '            <a href="' + message.href + '">' + message.login + '</a>\n' +
        '        </div>\n' +
        '        <div class="time_my">\n' +
        '            <span>' + message.date_added + '</span>\n' +
        '        </div>\n' +
        '        <div class="massage_txt">\n' +
        '            <span>' + message.text + '</span>\n' +
        '        </div>\n' +
        '        <div class="massage_attachments">\n' +
        '        </div>\n' +
        '    </div>';
    if ($('#offer-message-' + message.offer_message_id).length > 0) {
        $('#offer-message-' + message.offer_message_id).replaceWith(temp);
    } else {
        $('#smallChat' + offer_id).append(temp);
    }
}

function scrollOfferContentToDown(offer_id){
    var objDiv = document.getElementById('smallChat' + offer_id);
    if (objDiv) {
        objDiv.scrollTop = objDiv.scrollHeight;
    }
}

$(document).on('click', '.change_history .accordion_history', function(){
    this.classList.toggle("active");
    var panel = $(this).next();
    if (panel.css('max-height') == '0px'){
        panel.css('max-height', "250px");
    } else {
        panel.css('max-height', '0px');
    }
});


// order/order search
$(document).on('click', '#order-search button', function (e) {
    var url = [];

    var search = $('#order-search input[name=search]').val();
    if (search != '') {
        url.push('search=' + search);
    }

    var filter_section_id = $('#order-search select[name=filter_section_id]').val();
    if (filter_section_id != 0) {
        url.push('filter_section_id=' + filter_section_id);
    }

    var filter_subject_id = $('#order-search select[name=filter_subject_id]').val();
    if (filter_subject_id != 0) {
        url.push('filter_subject_id=' + filter_subject_id);
    }

    var filter_work_type_id = $('#order-search select[name=filter_work_type_id]').val();
    if (filter_work_type_id != 0) {
        url.push('filter_work_type_id=' + filter_work_type_id);
    }

    if ($('#order-search input[name=filter_no_offer]').prop('checked')) {
        url.push('filter_no_offer=1');
    }

    if ($('#order-search input[name=filter_my_specialization]').prop('checked')) {
        url.push('filter_my_specialization=1');
    }

    if ($('#order-search input[name=filter_my_work_type]').prop('checked')) {
        url.push('filter_my_work_type=1');
    }

    if (url.length > 0) {
        location.href = '../index.php?route=order/order&' + url.join('&');
    } else {
        location.href = '../index.php?route=order/order'
    }
});

// order/order/info offer

$(document).on('keyup', '#c1', function(e){
    var c1 = parseInt($(this).val());
    var c2 = c1 - c1 * (commission / 100);
    $('#c2').val(c2);
});
$(document).on('keyup', '#c2', function(e){
    var c2 = parseInt($(this).val());
    var c1 = c2 * 100 / (100 - commission);
    $('#c1').val(c1);
});

$(document).on('click', '#offer button.add', function() {
    $('#offer .alert').remove();
    $.ajax({
        url : '../index.php?route=order/offer/add',
        method : 'POST',
        data : $('#offer input, #offer textarea'),
        beforeSend : function(){
            $('#offer button.add').prop('disabled', true);
        },
        success : function(json) {
            if (json['error_auth']) {
                location.href = '../index.php?route=account/login';
            }

            if (json['error_order']) {
                $('#offer h3').after('<div class="alert alert-danger">' + json['error_order'] + '</div>');
            }

            if (json['error_access_denied']) {
                $('#offer h3').after('<div class="alert alert-danger">' + json['error_access_denied'] + '</div>');
            }

            if (json['error_bet']) {
                $('#offer h3').after('<div class="alert alert-danger">' + json['error_bet'] + '</div>');
            } else if (json['error_earned']) {
                $('#offer h3').after('<div class="alert alert-danger">' + json['error_earned'] + '</div>');
            }

            if (json['success']) {

                $('#content').load(location.href + ' #content > div');

                alertSuccess(json['success']);
            }

            $('#offer button.add').prop('disabled', false);
        }
    });
});

$(document).on('click', '#offer button.edit', function() {
    $('#offer .alert').remove();
    $.ajax({
        url : '../index.php?route=order/offer/edit',
        method : 'POST',
        data : $('#offer input, #offer textarea'),
        success : function(json) {
            if (json['error_auth']) {
                location.href = '../index.php?route=account/login';
            }

            if (json['error_order']) {
                $('#offer h3').after('<div class="alert alert-danger">' + json['error_order'] + '</div>');
            }

            if (json['error_access_denied']) {
                $('#offer h3').after('<div class="alert alert-danger">' + json['error_access_denied'] + '</div>');
            }

            if (json['error_bet']) {
                $('#offer h3').after('<div class="alert alert-danger">' + json['error_bet'] + '</div>');
            }

            if (json['error_earned']) {
                $('#offer h3').after('<div class="alert alert-danger">' + json['error_earned'] + '</div>');
            }

            if (json['success']) {

                $('#content').load(location.href + ' #content > div');

                alertSuccess(json['success']);
            }
        }
    });
});
