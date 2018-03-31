/**
 * @version    $Id$
 * @package    IGPGBLDR
 * @author     InnoGears Team <support@innogears.com>
 * @copyright  Copyright (C) 2012 InnoGears.com. All Rights Reserved.
 * @license    GNU/GPL v2 or later http://www.gnu.org/licenses/gpl-2.0.html
 *
 * Websites: http://www.innogears.com
 * Technical Support: Feedback - http://www.innogears.com/contact-us/get-support.html
 */

/**
 * Custom script for Heading element
 */
( function ($)
{
	"use strict";

	$.IG_Heading = $.IG_Heading || {};

	$.IGSelectFonts	= $.IGSelectFonts || {};

	$.IG_Heading = function () {
		new $.IGSelectFonts();

		$('#param-font').on('change', function () {
			if ($(this).val() == 'inherit') {
				$('#param-font_face_type').val('standard fonts');
				$('.jsn-fontFaceType').trigger('change');
				$('#param-font_size_value_').val('');
				$('#param-font_style').val('bold');
				$('#param-color').val('#000000');
				$('#color-picker-param-color').ColorPickerSetColor('#000000');
				$('#color-picker-param-color div').css('background-color', '#000000');
			}
		});
	}

	$(document).ready(function () {
		$.IG_Heading();
	});

})(jQuery);