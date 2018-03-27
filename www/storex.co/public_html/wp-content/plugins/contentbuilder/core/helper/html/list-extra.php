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
class IG_Pb_Helper_Html_List_Extra extends IG_Pb_Helper_Html {
	/**
	 * List Extra Element
	 * @param type $element
	 * @return string
	 */
	static function render( $element ) {
		$html  = "<div class='{$element['class']}'>";
		$html .= "<div id='{$element['id']}' class='jsn-items-list ui-sortable'>";

		if ( $element['std'] ) {

		}

		$html .= '</div>';
		$html .= "<a class='jsn-add-more add-more-extra-list' onclick='return false;' href='#'><i class='icon-plus'></i>" . __( 'Add Item', IGPBL ) . '</a>';
		$html .= '</div>';
		return $html;
	}
}