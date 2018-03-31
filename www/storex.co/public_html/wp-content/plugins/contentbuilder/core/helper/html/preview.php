<?php
/**
 * @version    $Id$
 * @package    IG PageBuilder
 * @author     InnoGears Team <support@www.innogears.com>
 * @copyright  Copyright (C) 2012 www.innogears.com. All Rights Reserved.
 * @license    GNU/GPL v2 or later http://www.gnu.org/licenses/gpl-2.0.html
 *
 * Websites: http://www.www.innogears.com
 * Technical Support:  Feedback - http://www.www.innogears.com
 */
class IG_Pb_Helper_Html_Preview extends IG_Pb_Helper_Html {
	/**
	 * Preview Box of shortcode
	 * @return type
	 */
	static function render() {
		$hide_preview = __( 'Hide Live Preview', IGPBL );
		$show_preview = __( 'Show Live Preview', IGPBL );
		return "<div class='form-group control-group clearfix'>
		<div id='preview_container'>
		<div id='previewToggle'><i id='hide_preview' title='$hide_preview' class='icon-delete'></i><div id='show_preview' class='thumbnail jsn-padding-medium jsn-text-center hidden'>$show_preview</div></div>
		<div id='ig_overlay_loading' class='jsn-overlay jsn-bgimage image-loading-24'></div>
		<iframe id='shortcode_preview_iframe' name='shortcode_preview_iframe' class='shortcode_preview_iframe'></iframe>
		</div></div>";
	}
}