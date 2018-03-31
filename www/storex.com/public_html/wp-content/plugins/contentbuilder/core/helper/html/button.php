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
class IG_Pb_Helper_Html_Button extends IG_Pb_Helper_Html {
	/**
	 * Button
	 * @param type $element
	 * @return string
	 */
	static function render( $element ) {
		$element = parent::get_extra_info( $element );
		$label = parent::get_label( $element );
		$element['class'] = ( $element['class'] ) ? $element['class'] . ' btn' : 'btn';
		$action_type = isset( $element['action_type'] ) ? " data-action-type = '{$element["action_type"]}' " : '';
		$action = isset( $element['action'] ) ? " data-action = '{$element["action"]}' " : '';
		$output = "<button class='{$element['class']}' $action_type $action>{$element['std']}</button>";
		return parent::final_element( $element, $output, $label );
	}
}