$(document).ready(function () {
    $('.tab-link').eq(0).addClass('current');
    $('.tab-content_spec').eq(0).addClass('current');
    recalcSelectedSubjects();
});

$(document).on('click', '.general-checkbox', function(){
    $(this).parents('.tab-content_spec').find('input[type=checkbox]').prop('checked', $(this).prop('checked'));
    recalcSelectedSubjects();
});

$(document).on('change', '.tabs_spec_ast input[type=checkbox]', function (e) {
    saveSpecializations();
});

$(document).on('click', 'button.btn-save', function(e) {
    saveSpecializations();
    alertSuccess('Изменения сохранены');
});

function recalcSelectedSubjects(){
    $('.tab-content_spec').each(function(){
        var count = $(this).find('input[type=checkbox]:checked').length;
        $('li.tab-link[data-tab=' + $(this).attr('id') + ']').find('span').remove();
        if (count > 0) {
            $('li.tab-link[data-tab=' + $(this).attr('id') + ']').append('<span>' + count + '</span>');
        }
    });
}

function saveSpecializations(){
    $.ajax({
        url : 'index.php?route=account/specialization/save',
        method : "POST",
        data : $('.tabs_spec_ast input[type=checkbox]:checked'),
        dataType : 'json',
        success : function (json) {
            recalcSelectedSubjects();
        }
    });
}