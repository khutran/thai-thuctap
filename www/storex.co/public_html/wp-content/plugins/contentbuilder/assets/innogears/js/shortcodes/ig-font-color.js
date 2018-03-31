/**
 * @version    $Id$
 * @package    IGPGBLDR
 * @author     InnoGears Team <support@www.innogears.com>
 * @copyright  Copyright (C) 2012 InnoGears.com. All Rights Reserved.
 * @license    GNU/GPL v2 or later http://www.gnu.org/licenses/gpl-2.0.html
 *
 * Websites: http://www.www.innogears.com
 * Technical Support: Feedback - http://www.www.innogears.com/contact-us/get-support.html
 */

( function ($) {
	"use strict";

	$.IGSelectFonts	= $.IGSelectFonts || {};

    $.IGColorPicker = $.IGColorPicker || {};

    $.IG_Font_Color = $.IG_Font_Color || {};

	$.IG_Font_Color = function () {
		new $.IGSelectFonts();
        new $.IGColorPicker();
	}

	$(document).ready(function () {
		$('body').bind('ig_after_popover', function (e) {
			$.IG_Font_Color();
		});
	});

})(jQuery);