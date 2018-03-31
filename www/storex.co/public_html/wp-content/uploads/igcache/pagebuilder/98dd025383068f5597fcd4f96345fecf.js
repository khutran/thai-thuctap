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
		if (typeof($.fancybox) == "function") {
			$(".ig-pb-button-fancy").fancybox({
				"width"		: "75%",
				"height"	: "75%",
				"autoScale"	: false,
				"transitionIn"	: "elastic",
				"transitionOut"	: "elastic",
				"type"		: "iframe"
			});
		}
	});
	
})(jQuery);