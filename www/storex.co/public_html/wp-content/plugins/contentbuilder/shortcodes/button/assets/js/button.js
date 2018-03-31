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

( function ($) {
	"use strict";
	
	$(document).ready(function () {
		// Update preview when select icon
		$( '#modalOptions' ).delegate( '.jsn-iconselector .jsn-items-list .jsn-item', 'click', function () {
			var parent_tab = $(this).parents('.ig-pb-setting-tab');
            var stop_reload_iframe = ((parent_tab.length > 0 && parent_tab.is("#styling")) || (parent_tab.length > 0 && parent_tab.is("#modalAction"))) ? 0 : 1;

            $.HandleSetting.shortcodePreview(null, null, null, null, stop_reload_iframe);
		});
	});

})(jQuery);
