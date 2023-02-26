$(document).on('submit', '#form-login', function (e) {
    e.preventDefault();
    $('.alert, .error').remove();

    $.ajax({
        url: $(this).attr('action'),
        method: $(this).attr('method'),
        data: $(this).serialize(),
        dataType: 'json',
        beforeSend: function () {
            $('#form-login button[type=submit]').btnLoading('on');
        },
        success: function (json) {
            if (!json['success'] || json['errors']) {
                $('#form-login button[type=submit]').btnLoading('off');
            }

            if (json['error_warning']) {
                $('#form-login').prepend('<span class="error">' + json['error_warning'] + '</span>');
            }

            if (json['success']) {
                /*$('#form-login').prepend('<span class="error">' + json['success'] + '</span>');*/
                if (json['redirect']) {
                    location.href = json['redirect'];
                }
            }
        },
        error: function (response) {
            var json = response.responseJSON;

            if (json['error_warning']) {
                $('#form-login').prepend('<span class="error">' + json['error_warning'] + '</span>');
            } else if (json['errors']['login'][0]) {
                $('#form-login').prepend('<span class="error">' + json['errors']['login'][0] + '</span>');
            } else if (json['errors']['password'][0]) {
                $('#form-login').prepend('<span class="error">' + json['errors']['password'][0] + '</span>');
            }
            $('#form-login button[type=submit]').btnLoading('off');
        }
    });
});

$(document).on('submit', '#form-register', function (e) {
    e.preventDefault();

    $('.alert, .error').remove();

    var error = 0;

    if (!$('#form-register').find('input[name=email]').val()) {
        $('#form-register').find('input[name=email]').after('<span class="error">Неверный формат электронной почты</span>');
        error = 1;
    }

    if (!$('#form-register').find('input[name=password]').val()) {
        $('#form-register').find('input[name=password]').after('<span class="error">В пароле должно быть от 4 до 20 символов!</span>');
        error = 1;
    }

    if (!error) {
        $.ajax({
            url: $(this).attr('action'),
            method: $(this).attr('method'),
            data: $(this).serialize(),
            dataType: 'json',
            beforeSend: function () {
                $('#form-register button[type=submit]').btnLoading('on');
            },
            success: function (json) {

                if (json['error_warning']) {
                    $('#form-register').prepend('<div class="error">' + json['error_warning'] + '</div>');
                    $('#form-register button[type=submit]').btnLoading('off');
                }
                if (json['error_email']) {
                    $('#form-register').find('input[name=email]').after('<div class="error">' + json['error_email'] + '</div>');
                    $('#form-register button[type=submit]').btnLoading('off');
                }
                if (json['error_password']) {
                    $('#form-register').find('input[name=password]').after('<div class="error">' + json['error_password'] + '</div>');
                    $('#form-register button[type=submit]').btnLoading('off');
                }
                if (json['error_customer_group_id']) {
                    $('#form-register').find('input[name=customer_group_id]').parent().append('<div class="error">' + json['error_customer_group_id'] + '</div>');
                    $('#form-register button[type=submit]').btnLoading('off');
                }

                if (json['success']) {
                    /*$('#form-register').prepend('<div class="alert alert-success">' + json['success'] + '</div>');*/
                    if (json['redirect']) {
                        location.href = json['redirect'].replace('&amp;', '&');
                    }
                }
            }
        });
    }
});

$(document).on('submit', '#form-forgotten', function (e) {
    e.preventDefault();
    $('.alert, .error').remove();

    $.ajax({
        url: $(this).attr('action'),
        method: $(this).attr('method'),
        data: $(this).serialize(),
        dataType: 'json',
        beforeSend: function () {
            $('#form-forgotten button[type=submit]').btnLoading('on');
        },
        success: function (json) {
            if (json['error_warning']) {
                $('#form-forgotten').prepend('<div class="error">' + json['error_warning'] + '</div>');
                $('#form-forgotten button[type=submit]').btnLoading('off');
            }
            if (json['success']) {
                $('#form-forgotten').prepend('<div class="success">' + json['success'] + '</div>');
                if (json['redirect']) {
                    location.href = json['redirect'];
                }
            }
        }
    });
});
