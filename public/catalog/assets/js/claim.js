var live, xhr, interval = 600;


$(document).on('click', '.text_claim button.add_claim', function(e){
    e.preventDefault();
    var textarea = $(this).parents('.text_claim').find('.textarea');
    var claim_id = parseInt($(this).data('claim_send'));
    var text = $(textarea).text();

    if (claim_id && text) {
        $.ajax({
            url : 'index.php?route=claim/message/send&claim_id=' + claim_id,
            method : 'POST',
            data : {
                claim_id : claim_id,
                text : text,
            },
            beforeSend : function() {
                $('.text_claim button.add_claim').prop('disabled', true);
                console.log($('.text_claim button.add_claim').prop('disabled'));
            },
            success : function (json) {
                $('.text_claim button.add_claim').prop('disabled', false);
                console.log($('.text_claim button.add_claim').prop('disabled'));
                if (json['success']) {
                    connectLive(claim_id);
                    $(textarea).text('');
                }
            }
        });
    }
});

function connectLive(claim_id) {
    clearInterval(live);
    if (claim_id) {
        var last_claim_message_id = $('#smallChat' + claim_id).children().last().data('claim_message_id');
        if (!last_claim_message_id) {
            last_claim_message_id = 0;
        }
        live = setInterval(function(){
            if (xhr) xhr.abort();

            xhr = $.ajax({
                url : 'index.php?route=claim/message/live',
                method : 'POST',
                data : {
                    last_claim_message_id : last_claim_message_id,
                    claim_id : claim_id
                },
                success : function(json){
                    if (json['messages'].length > 0) {
                        for (var i in json['messages']) {
                            setClaimMessage(claim_id, json['messages'][i]);
                        }
                        var objDiv = document.getElementById('smallChat' + claim_id);
                        if (objDiv.scrollHeight - objDiv.offsetHeight - 250 < objDiv.scrollTop) {
                            scrollClaimContentToDown(claim_id);
                        }
                    }
                    connectLive(claim_id);
                }
            });
        }, interval);
    }
}

function setClaimMessage(claim_id, message){
    var temp = '<div id="claim-message-' + message.claim_message_id +'" class="massage user clearfix" data-claim_message_id="' + message.claim_message_id + '">\n' +
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
    if ($('#claim-message-' + message.claim_message_id).length > 0) {
        $('#claim-message-' + message.claim_message_id).replaceWith(temp);
    } else {
        $('#smallChat' + claim_id).append(temp);
    }
}

function scrollClaimContentToDown(claim_id){
    var objDiv = document.getElementById('smallChat' + claim_id);
    if (objDiv) {
        objDiv.scrollTop = objDiv.scrollHeight;
    }
}