/**
 * @version    $Id$
 * @package    IG PageBuilder
 * @author     InnoGears Team <support@innogears.com>
 * @copyright  Copyright (C) 2012 innogears.com. All Rights Reserved.
 * @license    GNU/GPL v2 or later http://www.gnu.org/licenses/gpl-2.0.html
 *
 * Websites: http://www.innogears.com
 * Technical Support:  Feedback - http://www.innogears.com
 */

(function ($) {
	
	$(document).ready(function () {
		if ( typeof($.fn.lazyload) == "function" ) {
			$(".image-scroll-fade").lazyload({
				effect       : "fadeIn"
			});	
		}
		if (typeof($.fancybox) == "function") {
			$(".ig-image-fancy").fancybox({
				"autoScale"	: true,
				"transitionIn"	: "elastic",
				"transitionOut"	: "elastic",
				"type"		: "iframe"
			});
		}
	});
	
})(jQuery);