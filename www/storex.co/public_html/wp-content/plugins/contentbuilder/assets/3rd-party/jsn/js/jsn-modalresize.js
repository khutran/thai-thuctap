(function ($) {
    var JSNModalResize = function () {}
    JSNModalResize.prototype = {
        resize:function(windowWidth, width, frameId){
            if(windowWidth < 800){
                window.parent.jQuery.noConflict()( frameId).contents().find('body').css('overflow-x', 'hidden')
                window.parent.jQuery.noConflict()( frameId).contents().find('#wpwrap').css('width', width * 0.9);
                //window.parent.jQuery.noConflict()( frameId).contents().find('.jsn-bootstrap3 .form-horizontal .control-label').css('width', '60px');
                //window.parent.jQuery.noConflict()( frameId).contents().find('.jsn-bootstrap3 .form-horizontal .controls').css('margin-left', '80px');
            }
            else{
                window.parent.jQuery.noConflict()( frameId).contents().find('body').css('overflow-x', 'auto')
                window.parent.jQuery.noConflict()( frameId).contents().find('#wpwrap').css('width', '100%');
                //window.parent.jQuery.noConflict()( frameId).contents().find('.jsn-bootstrap3 .form-horizontal .control-label').css('width', '160px');
                //window.parent.jQuery.noConflict()( frameId).contents().find('.jsn-bootstrap3 .form-horizontal .controls').css('margin-left', '180px');
            }
        }
    }

    $(document).ready(function() {
        $(window).resize(function() {
            var modalResize = new JSNModalResize()
            var width = 0.9 * ($(window).width() > 800 ? 800 : $(window).width());
            width = (width >= 720) ? 750 : width;
            var height = 0.95 * $(window).height();
            $(".ui-dialog").css('width', width + 'px')
            $(".ui-dialog").css('height', height + 'px')
            $('.ui-dialog').css({
                top:'50%',
                left:'50%',
                margin:'-'+($('.ui-dialog').height() / 2)+'px 0 0 -'+($('.ui-dialog').width() / 2)+'px'
            });
            $('.ui-dialog .ui-dialog-content').css('height', height - 110)

            // adjust some elements
            modalResize.resize($(window).width(), width, '#jsn_view_modal');
        })
    })
})(jQuery)