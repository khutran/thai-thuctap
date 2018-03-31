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

/**
 * Custom script for Textbox element
 */
( function ($) {
    "use strict";

    $.IGSelectFonts = $.IGSelectFonts || {};

    $.IGColorPicker = $.IGColorPicker || {};

    $.IG_Text = $.IG_Text || {};

    $.IG_Text = function () {
        new $.IGSelectFonts();
        new $.IGColorPicker();
    }

    $(document).ready(function () {
        $.IG_Text();
    });

})(jQuery)