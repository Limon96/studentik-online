var separator = '----';

$(document).on('click', '#page-builder .card-header', function () {
    $(this).parent().find('.card-body').toggle();
});

function page_builder_submit(url, method)
{
    var tree = [];

    var cards = $('#page-builder .content').children('.card');

    $.each(cards, function () {
        tree.push(
            treeCard(this)
        );
    });

    renderTree(tree, separator);

    var csrf = $('input[name=_token]').val();

    $.ajax({
        url: url,
        method: 'POST',
        data: {
            '_method': method,
            '_token': csrf,
            'components': tree,
            'title': $('#input-title').val(),
            'menu_title': $('#input-menu_title').val(),
            'slug': $('#input-slug').val(),
            'meta_title': $('#input-meta_title').val(),
            'meta_description': $('#input-meta_description').val(),
            'meta_keywords': $('#input-meta_keywords').val(),
            'type_page': $('#select-type_page').val(),
            'status': $('#select-status').val(),
        },
        success: function (response) {
            /*console.log(response);*/

            if (response['redirect']) {
                location.href = response['redirect'];
            }
        }
    });
}

function treeCard(element)
{
    console.log(element);
    var component = {
        'title' : $(element).children('.card-header').text(),
        'fields' : [],
        'type' : $(element).data('type'),
    };

    var fields = $(element).children('.card-body').children('.form-group');

    if (fields.length) {
        $.each(fields, function () {
            var el = parseElement(this);

            component.fields.push(
                el
            );
        });
    }

    if ($(element).find('.card').length) {

        $.each($(element).children('.card-body').children('.card'), function () {
            component['fields'].push(
                treeCard(this)
            );
        });

        return component;
    } else {
        return component;
    }
}

function parseElement (elem) {

    var label = $(elem).children('label').text().trim();

    if ($(elem).children('input').length) {
        return parseInput($(elem).children('input'), label);
    } else if ($(elem).children('textarea').length) {
        return parseTextarea($(elem).children('textarea'), label);
    } else if ($(elem).children('select').length) {
        return parseSelect($(elem).children('select'), label);
    }

    return null;
}

function parseInput(elem, label)
{
    var type = $(elem).attr('type'),
        name = $(elem).attr('name'),
        value = $(elem).val();

    switch (type) {
        case 'file':
            if (name == 'image') {
                name = 'image';
                type = 'image';
            } else {
                name = 'file';
                type = 'file';
            }
            break;
        default:
            type = 'string';
    }

    return {
        'label': label,
        'type': type,
        'name': name,
        'value': value,
    };
}

function parseTextarea(elem, label){
    var type = 'textarea',
        name = $(elem).attr('name'),
        value = $(elem).val();

    if ($(elem).hasClass('summernote')) {
        type = 'summernote';
    }

    return {
        'label': label,
        'type': type,
        'name': name,
        'value': value,
    };
}

function parseSelect(elem, label){
    var type = 'select',
        name = $(elem).attr('name'),
        value = $(elem).val();

    return {
        'label': label,
        'type': type,
        'name': name,
        'value': value,
    };
}

function renderTree(arr, t){
    $.each(arr, function (i, o) {

        if (o.fields) {
            sendTree(o.title, t);
            renderTree(o.fields, t + separator)
        } else {
            sendTree(o.label, t);
        }

        /*$.each(o, function (k, v) {
            if (k === 'fields') {
                renderTree(v, t + separator);
            } else {
                sendTree(v, t)
            }
        });*/
    })
}

function sendTree(title, t){
    $('.tree p').html(
        $('.tree p').html() + '<br>' +
        '|' + t + ' ' + title
    );
}

$(document).on('click', '#page-builder .new-block .add-block', function () {
    var that = this,
        block = $(that).parent().parent().find('select').val();

    if (block) {
        $.ajax({
            url: getUrl() + 'pagebuilder/' + block,
            method: 'GET',
            success: function (html) {
                $(that).parent().parent().parent().before(html);
                initPageBuilder();
            }
        });
    }
});

function getUrl()
{
    return location.origin + '/new-order/_manager/';

}

function removeBlock(elem) {
    $(elem).parent().parent().prev('.new-block').remove();
    $(elem).parent().parent().remove();
}

function upBlock(elem) {
    var current_block = $(elem).parent().parent().clone();
    var replacement_block = $(elem).parent().parent().prev().prev('.card').clone();

    $(elem).parent().parent().prev().prev('.card').before(current_block);
    $(elem).parent().parent().prev().prev('.card').remove();

    $(elem).parent().parent().before(replacement_block);
    $(elem).parent().parent().remove();

    toggleControlBlock();
}

function downBlock(elem) {
    var current_block = $(elem).parent().parent().clone();
    var replacement_block = $(elem).parent().parent().next().next('.card').clone();

    $(elem).parent().parent().next().next('.card').after(current_block);
    $(elem).parent().parent().next().next('.card').remove();

    $(elem).parent().parent().after(replacement_block);
    $(elem).parent().parent().remove();

    toggleControlBlock();
}

function toggleControlBlock() {

    $('#page-builder > .content > .card')
        .children('.card-header')
        .find('.up-block').show();

    $('#page-builder > .content > .card')
        .children('.card-header')
        .find('.down-block').show();

    $('#page-builder > .content > .card')
        .first()
        .children('.card-header')
        .find('.up-block')
        .hide();

    $('#page-builder > .content > .card')
        .last()
        .children('.card-header')
        .find('.down-block')
        .hide();

    $('.card').each(function(){
        $(this).children('.card-body')
            .children('.card')
            .children('.card-header')
            .find('.up-block').show();

        $(this).children('.card-body')
            .children('.card')
            .children('.card-header')
            .find('.down-block').show();

        $(this).children('.card-body')
            .children('.card')
            .first()
            .children('.card-header')
            .find('.up-block')
            .hide();

        $(this).children('.card-body')
            .children('.card')
            .last()
            .children('.card-header')
            .find('.down-block')
            .hide();
    });

}

function initPageBuilder(){

    toggleControlBlock();

    initSummernote();
}

function initSummernote()
{
    $('.summernote').summernote();
}
