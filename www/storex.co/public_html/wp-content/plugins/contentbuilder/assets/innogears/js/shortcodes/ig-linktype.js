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

/**
 * Custom script for List element
 */
( function ($)
{
	"use strict";

	$.IG_LTSelect = $.IG_LTSelect || {};

	$.IG_LTSelect = function () {
		$('#param-link_type').on('change', function () {
            var option_text = $("#param-link_type option:selected").text();
            var option_val = $("#param-link_type option:selected").val();
            var label = $("#parent-param-single_item").children('.control-label');
            label.html(Ig_Translate.singleEntry.replace('%s', option_text));

            var controls = $("#parent-param-single_item").children('.controls');
            var visibleChild = controls.children("[data-depend-value='"+option_val+"']");
            if($.trim(visibleChild.html()) == ''){
                visibleChild.html('<label style="margin-top: 6px;">'+Ig_Translate.noItem.replace('%s', option_text.toLowerCase())+'</label>');
            }
		});
	}

	$(document).ready(function () {
		$.IG_LTSelect();
	});

})(jQuery);