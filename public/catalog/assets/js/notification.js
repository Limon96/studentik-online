function viewedNotification(notification_id, that) {
    $(that).addClass('read');
    $(that).removeAttr('onmouseover');

    $.ajax({
        url : 'index.php?route=account/event/viewedNotification&notification_id=' + notification_id,
        method : 'GET',
        dataType : 'json',
        success : function (json) {

        }
    });
}