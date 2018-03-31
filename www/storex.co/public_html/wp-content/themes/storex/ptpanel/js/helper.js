jQuery(document).ready(function($) {
	"use strict";
	$('#ptpanel-tabs a').on('shown', function (e) {
    	$.cookie('ptpanel_tab_selected', $(e.target).attr('href'));
    })
    
    if ($.cookie('ptpanel_tab_selected') != null){
	    $('#ptpanel-tabs a[href="'+$.cookie('ptpanel_tab_selected')+'"]').tab('show');
    } else {
	    $('#ptpanel-tabs a:first-child').tab('show');
    }
    
    $('#ptpanel-tabs a').click(function (e) {
    	e.preventDefault();
    	$(this).tab('show');
    });
    
    $(function () { $("input,select,textarea,radio").not("[type=submit]").jqBootstrapValidation(); } );
     
    $("select:not('.google-fonts-select'), input[type=checkbox], input[type=radio], input[type=file]").styler();
     
    $(".close_picker").each(function(){
	    $(this).click(function(){
		    $(this).parent().fadeOut('fast');
		});
	});

    $(".color-picker").each(function(){
        $(this).on('click', function(){
            $(".picker-container").each(function(){
                var visibility = $(this).css('display');
                if (visibility == 'block'){
                    $(this).fadeOut('fast');
                } 
            });
        })
    });

});

(function( $ ){ 

$.fn.onoffswitcher = function( options ) {
	jQuery.onoffswitcher(this, options);
  return this;
}

jQuery.onoffswitcher = function (container, inpt) {
	var container = jQuery(container).get(0);
	var on = false;
	if ($(container).hasClass('on')) on = true;
	else on = false; 
	$(container).click(function(){
		if (on) {
			left = '0';
			on = false;
			$(inpt).attr('value', 'off');
		} else {
			left = '-37px';
			on = true;
			$(inpt).attr('value', 'on');
		}
		$(this).find('.icon-toggler').animate({
			'left' : left,
		});
	});
}

})( jQuery );


	    


