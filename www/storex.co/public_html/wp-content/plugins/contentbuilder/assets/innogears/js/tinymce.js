(function ($) {
	if (typeof(Ig_Translate) != 'undefined') {

        $(document).ready(function () {
        	$('#ig_pb_button').on('click', function () {
        		 // triggers the thickbox
                tb_show( Ig_Translate.inno_shortcode, '#TB_inline?width=' + 100 + '&height=' + 100 + '&inlineId=ig_pb-form' );
                // custom style
                $('#TB_window').css({'overflow-y' : 'auto', 'overflow-x' : 'hidden', 'max-height' : '385px'});
        	});
        });
        
        // executes this when the DOM is ready
        jQuery(function(){
            // creates a form to be displayed everytime the button is clicked
            // you should achieve this using AJAX instead of direct html code like this
        	var html_classic_popover = window.parent.jQuery.noConflict()('.jsn-elementselector').clone();
            var form = $("<div/>", {
                            "id":"ig_pb-form"
                        }).append(
                            $("<div />").append('<div id="ig-shortcodes" class="ig-add-element add-field-dialog jsn-bootstrap3">' + html_classic_popover.html() + '</div>').html()
                        );
            form.appendTo('body').hide();
            form.find('#ig-shortcodes').fadeIn(500);
            
            $.HandleCommon.setFilterFields('#ig-shortcodes');
            $.HandleCommon.setQuickSearchFields('#ig-shortcodes');
        });
	}
})(jQuery)