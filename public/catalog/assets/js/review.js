$(document).on('click', '#review_add .sub_rev', function() {
    $('.warning').remove();

    var positive = $('#review_add input[name=positive]:checked').val(),
        text = $('#review_add .textarea').text(),
        time = $('#review_add input[name=time]:checked').val(),
        customer_id = $('#review_add input[name=customer_id]').val(),
        order_id = $('#review_add input[name=order_id]').val(),
        error = false;


    if (positive == undefined) {
        $('#review_add input[name=positive]').parents('.wrap_user_change').append('<p class="warning">Выберите один из вариантов</p>');
        error = true;
    }

    if (text == '' || text.length < 20) {
        $('#review_add .wrap_otziv').append('<p class="warning">Текст отзыва должен содержать от 20 символов</p>');
        error = true;
    }

    /*if (time == undefined) {
        $('#review_add input[name=time]').parents('.wrap_user_change').append('<p class="warning">Выберите один из вариантов</p>');
        error = true;
    }*/

    if (!error) {
        $.ajax({
            url : 'index.php?route=account/review/create',
            method : 'POST',
            data : {
                positive : positive,
                text : text,
                time : time,
                customer_id : customer_id,
                order_id : order_id
            },
            dataType : 'json',
            beforeSend : function (){
                $('#review_add .sub_rev').btnLoading('on');
            },
            success : function(json) {
                if (json['error_warning']) {
                    $('#review_add').prepend('<p class="warning">' + json['error_warning'] + '</p>');
                }

                if (json['error_positive']) {
                    $('#review_add input[name=positive]').parents('.wrap_user_change').append('<p class="warning">Выберите один из вариантов</p>');
                }

                if (json['error_text']) {
                    $('#review_add textarea[name=text]').parents('.wrap_otziv').append('<p class="warning">Текст отзыва должен содержать от 3 символов</p>');
                }

                if (json['error_time']) {
                    $('#review_add input[name=time]').parents('.wrap_user_change').append('<p class="warning">Выберите один из вариантов</p>');
                }

                if (!json['success']) {
                    $('#review_add .sub_rev').btnLoading('off');
                }

                if (json['success']) {
                    console.log('ss');
                    console.log(json['redirect']);
                    location.href = json['redirect'].replace('&amp;', '&');
                }
            }
        });
    }
});
