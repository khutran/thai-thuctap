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
class IG_Pb_Helper_Html_Radio_Button_Group extends IG_Pb_Helper_Html {
	/**
	 * Radio Button group
	 * @param type $element
	 * @return string
	 */
	static function render( $element ) {
		$element = parent::get_extra_info( $element );
		$label   = parent::get_label( $element );

		$output = "<div class='btn-group ig-btn-group' data-toggle='buttons'>";
		foreach ( $element['options'] as $key => $text ) {
			$active  = ( $key == $element['std'] ) ? 'active' : '';
			$checked = ( $key == $element['std'] ) ? 'checked' : '';
			$output .= "<label class='btn btn-default {$active}'>";
			$output .= "<input type='radio' name='{$element['id']}' $checked id='{$element['id']}' value='$key'/>";
			$output .= $text;
			$output .= "</label>";
		}
		$output .= '</div>';

		return parent::final_element( $element, $output, $label );
	}
}