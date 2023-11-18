$(document).ready(function(){


    /**************************Выбор аватарки***********************/

    $(document).on('click', '.open_avatar_modal', function(){
        $('.shadow_modal_avatar').show();
    });
    $(document).on('click', '.upload_my_avatar', function(){
        $('.shadow_modal_avatar2').show();
        $('.shadow_modal_avatar').hide();
    });


    $(document).on('click', '.close_m_a, .close_m_a2', function(){
        $('.shadow_modal_avatar').hide();
        $('.shadow_modal_avatar2').hide();
    });

    $(document).mouseup( function(e){
        var div = $( ".modal_for_avatere" );
        if ( !div.is(e.target)
            && div.has(e.target).length === 0 ) {
            $('.shadow_modal_avatar').hide();
        }
    });

    $(document).mouseup( function(e){
        var div = $( ".modal_for_avatere2" );
        if ( !div.is(e.target)
            && div.has(e.target).length === 0 ) {
            $('.shadow_modal_avatar2').hide();
        }
    });

    /*$('.input-file input[type=file].cropsim').on('change', function(){
        let file = this.files[0];
        $(this).next().html(file.name);
    });*/




/*************************************************/

    var resize = $('#upload-demo').croppie({
        enableExif: true,
        enableOrientation: true,
        viewport: { // Default { width: 100, height: 100, type: 'square' }
            width: 220,
            height: 220,
            type: 'circle' //square
        },
        boundary: {
            width: 250,
            height: 250
        }
    });


    $('#image').on('change', function () {
        var reader = new FileReader();
        reader.onload = function (e) {
            resize.croppie('bind',{
                url: e.target.result
            }).then(function(){
                console.log('jQuery bind complete');
            });
        }
        reader.readAsDataURL(this.files[0]);
    });


    $('.btn-upload-image').on('click', function (ev) {
        resize.croppie('result', {
            type: 'canvas',
            size: 'viewport'
        }).then(function (img) {
            $.ajax({
               url: "/index.php?route=account/edit/uploadAvatar",
               type: "POST",
               data: {"image":img},
               success: function (data) {
                   console.log(data);

                   if (data.success) {
                       html = '<img src="' + img + '" />';
                       $("#preview-crop-image").html(html);

                       $('img.avatarka').attr('src', data.image);
                       $('img.img_user').attr('src', data.image);

                       $('.shadow_modal_avatar').hide();
                       $('.shadow_modal_avatar2').hide();
                   } else if (data.error) {
                       console.error(data.error);
                   }
               }
            });
        });
    });

    $('.btn-select-image').on('click', function (ev) {
        $.ajax({
           url: "/index.php?route=account/edit/saveAvatar",
           type: "POST",
           data: $('#modAvat').serialize(),
           success: function (data) {
               console.log(data);

               if (data.success) {
                   $('img.avatarka').attr('src', data.image);
                   $('img.img_user').attr('src', data.image);

                   $('.shadow_modal_avatar').hide();
                   $('.shadow_modal_avatar2').hide();
               } else if (data.error) {
                   console.error(data.error);
               }
           }
        });

    });





    /**************************Подсказка***********************/

    let tooltipElem;

    document.onmouseover = function(event) {
        let target = event.target;

        // если у нас есть подсказка...
        let tooltipHtml = target.dataset.tooltip;
        if (!tooltipHtml) return;

        // ...создадим элемент для подсказки

        tooltipElem = document.createElement('div');
        tooltipElem.className = 'tooltip';
        tooltipElem.innerHTML = tooltipHtml;
        document.body.append(tooltipElem);

        // спозиционируем его сверху от аннотируемого элемента (top-center)
        let coords = target.getBoundingClientRect();

        let left = coords.left + (target.offsetWidth - tooltipElem.offsetWidth) / 2;
        if (left < 0) left = 0; // не заезжать за левый край окна

        let top = coords.top - tooltipElem.offsetHeight - 5;
        if (top < 0) { // если подсказка не помещается сверху, то отображать её снизу
            top = coords.top + target.offsetHeight + 5;
        }

        tooltipElem.style.left = left + 'px';
        tooltipElem.style.top = top + 'px';
    };

    document.onmouseout = function(e) {

        if (tooltipElem) {
            tooltipElem.remove();
            tooltipElem = null;
        }

    };

    /*************************************************/






    $(document).on('focus', '.textt_input_go .textarea', function(){
        $( ".textt_input_go .placeholder" ).hide();
    });

    $(document).on('blur', '.textt_input_go .textarea', function(){
        if ($('.textt_input_go .textarea').html().length == 0 ){
            $( ".textt_input_go .placeholder" ).show();
        }
    });

    /*************************************************/








    $(document).on('click', '.open_left_colmn', function(){
        $( ".left_content_resp" ).toggleClass('open_left');

    });





    $(document).on('click', '.open_right_colmn', function(){
        $( "#column-right" ).toggleClass('open_right');

    });



    $(document).on('click', '.open_left_colmn_flex', function(){
        $( ".col_inner_flex3" ).toggleClass('open_l');

    });

    $(document).on('click', '.list_speeker .speeker_numb', function(){
        $( ".col_inner_flex3" ).toggleClass('open_l');

    });







    $(document).on('click', '.toogle_menu', function(){
        $( ".toogle_menu" ).toggleClass('active');
        $( ".menu_drestt" ).toggleClass('active');

    });




    $('ul.tabs li').click(function(){
        var tab_id = $(this).attr('data-tab');
        $('ul.tabs li').removeClass('current');
        $('.tab-content').removeClass('current');
        $(this).addClass('current');
        $("#"+tab_id).addClass('current');
    })


    $('ul.tabs li').click(function(){
        var tab_class = $(this).attr('data-tab');
        $('.register .content_f').removeClass('current');
        $(this).addClass('current');
        $("."+tab_class).addClass('current');
    })





    $('ul.tabs_spec li').click(function(){
        var tab_id = $(this).attr('data-tab');
        $('ul.tabs_spec li').removeClass('current');
        $('.tab-content_spec').removeClass('current');
        $(this).addClass('current');
        $("#"+tab_id).addClass('current');
    })




    var acc = document.getElementsByClassName("accordion_one");
    var i;

    for (i = 0; i < acc.length; i++) {
        acc[i].addEventListener("click", function() {
            this.classList.toggle("active");
            var panel = this.nextElementSibling;
            if (panel.style.maxHeight){
                panel.style.maxHeight = null;
            } else {
                panel.style.maxHeight = panel.scrollHeight + "px";
            }
        });
    };


  var acc = document.getElementsByClassName("accordion_history");
    var i;

    for (i = 0; i < acc.length; i++) {
        acc[i].addEventListener("click", function() {
            this.classList.toggle("active");
            var panel = this.nextElementSibling;
            if (panel.style.maxHeight){
                panel.style.maxHeight = null;
            } else {
                panel.style.maxHeight = panel.scrollHeight + "px";
            }
        });
    };





    $(document).on('click', '.open_promod', function(){
        $( ".promo .hiden_promo" ).toggle( );
    });



    $(document).on('click', '.open_promo', function(){
        $( ".promo_code .hidden_promo" ).toggle( );
    });



    $('.otziv_sl').slick({
        dots: true,
        infinite: false,
        speed: 1000,
        arrows: false,
        autoplay: true,
        autoplaySpeed: 5000,
        slidesToShow: 3,
        slidesToScroll: 3,
        responsive: [

            {
                breakpoint: 992,
                settings: {
                    slidesToShow: 2,
                    slidesToScroll: 2
                }
            },
            {
                breakpoint: 768,
                settings: {
                    slidesToShow: 1,
                    slidesToScroll: 1
                }
            }
        ]
    });
    $('.news_slider').slick({
        dots: true,
        infinite: false,
        speed: 1000,
        arrows: false,
        autoplay: true,
        autoplaySpeed: 5000,
        slidesToShow: 4,
        slidesToScroll: 1,
        responsive: [

            {
                breakpoint: 992,
                settings: {
                    slidesToShow: 2,
                    slidesToScroll: 2
                }
            },
            {
                breakpoint: 768,
                settings: {
                    slidesToShow: 1,
                    slidesToScroll: 1
                }
            }
        ]
    });




});


$(document).ready(function(){


    var accr = document.getElementsByClassName("accordion_faq");
    var h;

    for (h = 0; h < accr.length; h++) {
        accr[h].addEventListener("click", function() {
            this.classList.toggle("active");
            var panelclim = this.nextElementSibling;
            if (panelclim.style.maxHeight){
                panelclim.style.maxHeight = null;
            } else {
                panelclim.style.maxHeight = panelclim.scrollHeight + "px";
            }
        });
    };



    var quest = document.getElementsByClassName("ac_faq");
    var z;

    for (z = 0; z < quest.length; z++) {
        quest[z].addEventListener("click", function() {
            this.classList.toggle("active");
            var panelclim = this.nextElementSibling;
            if (panelclim.style.maxHeight){
                panelclim.style.maxHeight = null;
            } else {
                panelclim.style.maxHeight = panelclim.scrollHeight + "px";
            }
        });
    };





    /* ========================================================================
     * Bootstrap: modal.js v3.4.1
     * https://getbootstrap.com/docs/3.4/javascript/#modals
     * ========================================================================
     * Copyright 2011-2019 Twitter, Inc.
     * Licensed under MIT (https://github.com/twbs/bootstrap/blob/master/LICENSE)
     * ======================================================================== */


    +function ($) {
        'use strict';

        // MODAL CLASS DEFINITION
        // ======================

        var Modal = function (element, options) {
            this.options = options
            this.$body = $(document.body)
            this.$element = $(element)
            this.$dialog = this.$element.find('.modal-dialog')
            this.$backdrop = null
            this.isShown = null
            this.originalBodyPad = null
            this.scrollbarWidth = 0
            this.ignoreBackdropClick = false
            this.fixedContent = '.navbar-fixed-top, .navbar-fixed-bottom'

            if (this.options.remote) {
                this.$element
                    .find('.modal-content')
                    .load(this.options.remote, $.proxy(function () {
                        this.$element.trigger('loaded.bs.modal')
                    }, this))
            }
        }

        Modal.VERSION = '3.4.1'

        Modal.TRANSITION_DURATION = 300
        Modal.BACKDROP_TRANSITION_DURATION = 150

        Modal.DEFAULTS = {
            backdrop: true,
            keyboard: true,
            show: true
        }

        Modal.prototype.toggle = function (_relatedTarget) {
            return this.isShown ? this.hide() : this.show(_relatedTarget)
        }

        Modal.prototype.show = function (_relatedTarget) {
            var that = this
            var e = $.Event('show.bs.modal', { relatedTarget: _relatedTarget })

            this.$element.trigger(e)

            if (this.isShown || e.isDefaultPrevented()) return

            this.isShown = true

            this.checkScrollbar()
            this.setScrollbar()
            this.$body.addClass('modal-open')

            this.escape()
            this.resize()

            this.$element.on('click.dismiss.bs.modal', '[data-dismiss="modal"]', $.proxy(this.hide, this))

            this.$dialog.on('mousedown.dismiss.bs.modal', function () {
                that.$element.one('mouseup.dismiss.bs.modal', function (e) {
                    if ($(e.target).is(that.$element)) that.ignoreBackdropClick = true
                })
            })

            this.backdrop(function () {
                var transition = $.support.transition && that.$element.hasClass('fade')

                if (!that.$element.parent().length) {
                    that.$element.appendTo(that.$body) // don't move modals dom position
                }

                that.$element
                    .show()
                    .scrollTop(0)

                that.adjustDialog()

                if (transition) {
                    that.$element[0].offsetWidth // force reflow
                }

                that.$element.addClass('in')

                that.enforceFocus()

                var e = $.Event('shown.bs.modal', { relatedTarget: _relatedTarget })

                transition ?
                    that.$dialog // wait for modal to slide in
                        .one('bsTransitionEnd', function () {
                            that.$element.trigger('focus').trigger(e)
                        })
                        .emulateTransitionEnd(Modal.TRANSITION_DURATION) :
                    that.$element.trigger('focus').trigger(e)
            })
        }

        Modal.prototype.hide = function (e) {
            if (e) e.preventDefault()

            e = $.Event('hide.bs.modal')

            this.$element.trigger(e)

            if (!this.isShown || e.isDefaultPrevented()) return

            this.isShown = false

            this.escape()
            this.resize()

            $(document).off('focusin.bs.modal')

            this.$element
                .removeClass('in')
                .off('click.dismiss.bs.modal')
                .off('mouseup.dismiss.bs.modal')

            this.$dialog.off('mousedown.dismiss.bs.modal')

            $.support.transition && this.$element.hasClass('fade') ?
                this.$element
                    .one('bsTransitionEnd', $.proxy(this.hideModal, this))
                    .emulateTransitionEnd(Modal.TRANSITION_DURATION) :
                this.hideModal()
        }

        Modal.prototype.enforceFocus = function () {
            $(document)
                .off('focusin.bs.modal') // guard against infinite focus loop
                .on('focusin.bs.modal', $.proxy(function (e) {
                    if (document !== e.target &&
                        this.$element[0] !== e.target &&
                        !this.$element.has(e.target).length) {
                        this.$element.trigger('focus')
                    }
                }, this))
        }

        Modal.prototype.escape = function () {
            if (this.isShown && this.options.keyboard) {
                this.$element.on('keydown.dismiss.bs.modal', $.proxy(function (e) {
                    e.which == 27 && this.hide()
                }, this))
            } else if (!this.isShown) {
                this.$element.off('keydown.dismiss.bs.modal')
            }
        }

        Modal.prototype.resize = function () {
            if (this.isShown) {
                $(window).on('resize.bs.modal', $.proxy(this.handleUpdate, this))
            } else {
                $(window).off('resize.bs.modal')
            }
        }

        Modal.prototype.hideModal = function () {
            var that = this
            this.$element.hide()
            this.backdrop(function () {
                that.$body.removeClass('modal-open')
                that.resetAdjustments()
                that.resetScrollbar()
                that.$element.trigger('hidden.bs.modal')
            })
        }

        Modal.prototype.removeBackdrop = function () {
            this.$backdrop && this.$backdrop.remove()
            this.$backdrop = null
        }

        Modal.prototype.backdrop = function (callback) {
            var that = this
            var animate = this.$element.hasClass('fade') ? 'fade' : ''

            if (this.isShown && this.options.backdrop) {
                var doAnimate = $.support.transition && animate

                this.$backdrop = $(document.createElement('div'))
                    .addClass('modal-backdrop ' + animate)
                    .appendTo(this.$body)

                this.$element.on('click.dismiss.bs.modal', $.proxy(function (e) {
                    if (this.ignoreBackdropClick) {
                        this.ignoreBackdropClick = false
                        return
                    }
                    if (e.target !== e.currentTarget) return
                    this.options.backdrop == 'static'
                        ? this.$element[0].focus()
                        : this.hide()
                }, this))

                if (doAnimate) this.$backdrop[0].offsetWidth // force reflow

                this.$backdrop.addClass('in')

                if (!callback) return

                doAnimate ?
                    this.$backdrop
                        .one('bsTransitionEnd', callback)
                        .emulateTransitionEnd(Modal.BACKDROP_TRANSITION_DURATION) :
                    callback()

            } else if (!this.isShown && this.$backdrop) {
                this.$backdrop.removeClass('in')

                var callbackRemove = function () {
                    that.removeBackdrop()
                    callback && callback()
                }
                $.support.transition && this.$element.hasClass('fade') ?
                    this.$backdrop
                        .one('bsTransitionEnd', callbackRemove)
                        .emulateTransitionEnd(Modal.BACKDROP_TRANSITION_DURATION) :
                    callbackRemove()

            } else if (callback) {
                callback()
            }
        }

        // these following methods are used to handle overflowing modals

        Modal.prototype.handleUpdate = function () {
            this.adjustDialog()
        }

        Modal.prototype.adjustDialog = function () {
            var modalIsOverflowing = this.$element[0].scrollHeight > document.documentElement.clientHeight

            this.$element.css({
                paddingLeft: !this.bodyIsOverflowing && modalIsOverflowing ? this.scrollbarWidth : '',
                paddingRight: this.bodyIsOverflowing && !modalIsOverflowing ? this.scrollbarWidth : ''
            })
        }

        Modal.prototype.resetAdjustments = function () {
            this.$element.css({
                paddingLeft: '',
                paddingRight: ''
            })
        }

        Modal.prototype.checkScrollbar = function () {
            var fullWindowWidth = window.innerWidth
            if (!fullWindowWidth) { // workaround for missing window.innerWidth in IE8
                var documentElementRect = document.documentElement.getBoundingClientRect()
                fullWindowWidth = documentElementRect.right - Math.abs(documentElementRect.left)
            }
            this.bodyIsOverflowing = document.body.clientWidth < fullWindowWidth
            this.scrollbarWidth = this.measureScrollbar()
        }

        Modal.prototype.setScrollbar = function () {
            var bodyPad = parseInt((this.$body.css('padding-right') || 0), 10)
            this.originalBodyPad = document.body.style.paddingRight || ''
            var scrollbarWidth = this.scrollbarWidth
            if (this.bodyIsOverflowing) {
                this.$body.css('padding-right', bodyPad + scrollbarWidth)
                $(this.fixedContent).each(function (index, element) {
                    var actualPadding = element.style.paddingRight
                    var calculatedPadding = $(element).css('padding-right')
                    $(element)
                        .data('padding-right', actualPadding)
                        .css('padding-right', parseFloat(calculatedPadding) + scrollbarWidth + 'px')
                })
            }
        }

        Modal.prototype.resetScrollbar = function () {
            this.$body.css('padding-right', this.originalBodyPad)
            $(this.fixedContent).each(function (index, element) {
                var padding = $(element).data('padding-right')
                $(element).removeData('padding-right')
                element.style.paddingRight = padding ? padding : ''
            })
        }

        Modal.prototype.measureScrollbar = function () { // thx walsh
            var scrollDiv = document.createElement('div')
            scrollDiv.className = 'modal-scrollbar-measure'
            this.$body.append(scrollDiv)
            var scrollbarWidth = scrollDiv.offsetWidth - scrollDiv.clientWidth
            this.$body[0].removeChild(scrollDiv)
            return scrollbarWidth
        }


        // MODAL PLUGIN DEFINITION
        // =======================

        function Plugin(option, _relatedTarget) {
            return this.each(function () {
                var $this = $(this)
                var data = $this.data('bs.modal')
                var options = $.extend({}, Modal.DEFAULTS, $this.data(), typeof option == 'object' && option)

                if (!data) $this.data('bs.modal', (data = new Modal(this, options)))
                if (typeof option == 'string') data[option](_relatedTarget)
                else if (options.show) data.show(_relatedTarget)
            })
        }

        var old = $.fn.modal

        $.fn.modal = Plugin
        $.fn.modal.Constructor = Modal


        // MODAL NO CONFLICT
        // =================

        $.fn.modal.noConflict = function () {
            $.fn.modal = old
            return this
        }


        // MODAL DATA-API
        // ==============

        $(document).on('click.bs.modal.data-api', '[data-toggle="modal"]', function (e) {
            var $this = $(this)
            var href = $this.attr('href')
            var target = $this.attr('data-target') ||
                (href && href.replace(/.*(?=#[^\s]+$)/, '')) // strip for ie7

            var $target = $(document).find(target)
            var option = $target.data('bs.modal') ? 'toggle' : $.extend({ remote: !/#/.test(href) && href }, $target.data(), $this.data())

            if ($this.is('a')) e.preventDefault()

            $target.one('show.bs.modal', function (showEvent) {
                if (showEvent.isDefaultPrevented()) return // only register focus restorer if modal will actually get shown
                $target.one('hidden.bs.modal', function () {
                    $this.is(':visible') && $this.trigger('focus')
                })
            })
            Plugin.call($target, option, this)
        })

    }(jQuery);



});

$.fn.btnLoader = function(stage) {
    var btn_loader = $('<div class="btn-loader"></div>');

    if (stage == 'on') {
        $(this).prop('disabled', true);
        $(this).hide();
        $(this).after(btn_loader);
    } else {
        $(this).prop('disabled', false);
        $(this).show();
        $(this).next('.btn-loader').remove();
    }
};

$.fn.btnLoading = function(stage) {
    var btn_loader = $('<div class="btn-loading"><div class="lds-ellipsis"><div></div><div></div><div></div><div></div></div></div>');

    if (stage == 'on') {
        $(this).prop('disabled', true);
        $(this).hide();
        $(this).after(btn_loader);
    } else {
        $(this).prop('disabled', false);
        $(this).show();
        $(this).next('.btn-loading').remove();
    }
};


