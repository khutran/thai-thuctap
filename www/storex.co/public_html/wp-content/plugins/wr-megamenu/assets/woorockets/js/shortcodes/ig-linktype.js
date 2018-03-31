/**
 * @version    $Id$
 * @package    WR MegaMenu
 * @author     WooRockets Team <support@woorockets.com>
 * @copyright  Copyright (C) 2014 WooRockets.com All Rights Reserved.
 * @license    GNU/GPL v2 or later http://www.gnu.org/licenses/gpl-2.0.html
 *
 * Websites: http://www.woorockets.com
 * Technical Support:  Feedback - http://www.woorockets.com
 */

/**
 * Custom script for List element
 */
( function ($)
{
	"use strict";

	$.WR_LTSelect = $.WR_LTSelect || {};

	$.WR_LTSelect = function () {
		$('#param-link_type').on('change', function () {
            var option_text = $("#param-link_type option:selected").text();
            var option_val = $("#param-link_type option:selected").val();
            var label = $("#parent-param-single_item").children('.control-label');
            label.html(Wr_Megamenu_Translate.singleEntry.replace('%s', option_text));

            var controls = $("#parent-param-single_item").children('.controls');
            var visibleChild = controls.children("[data-depend-value='"+option_val+"']");
            if($.trim(visibleChild.html()) == ''){
                visibleChild.html('<label style="margin-top: 6px;">'+Wr_Megamenu_Translate.noItem.replace('%s', option_text.toLowerCase())+'</label>');
            }
		});
	}

	$(document).ready(function () {
		$.WR_LTSelect();
	});

})(jQuery);