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
class IG_Pb_Helper_Html_Destination extends IG_Pb_Helper_Html {
	/**
	 * Selectbox of Google Map Destination
	 * @param type $element
	 * @return type
	 */
	static function render( $element ) {
		$element = parent::get_extra_info( $element );
		$label   = parent::get_label( $element );
		$output  = "<div id='{$element['id']}_wrapper' class='ig-destination-wrapper'><select id=\"select_{$element['id']}\"><option value=\"none\">".__( 'None', IGPBL ).'</option></select></div>';
		$output .= "<div class='image_loader'></div>";
		$output .= "<input type='hidden' id='{$element['id']}' class='{$element['class']}' value='{$element['std']}'  DATA_INFO />";
		return parent::final_element( $element, $output, $label );
	}
}