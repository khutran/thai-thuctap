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
class IG_Pb_Helper_Html_Text_Append extends IG_Pb_Helper_Html {
	/**
	 * Text input which has prefix/postfix Bootstrap add-on
	 *
	 * @param type $element
	 *
	 * @return type
	 */
	static function render( $element ) {
		$element = parent::get_extra_info( $element );
		$label   = parent::get_label( $element );

		//$ext_class  = ( isset( $element['append_before'] ) ) ? 'input-prepend' : '';
		//$ext_class .= ( isset( $element['append'] ) ) ? ' input-append' : '';
		$ext_class = ( isset( $element['append_before'] ) || isset( $element['append'] ) ) ? 'input-group col-xs-2 ' : '';
		$ext_class .= ( isset( $element['specific_class'] ) ) ? $element['specific_class'] : '';
		$output    = "<div class='$ext_class'>";
		$output   .= ( isset( $element['append_before'] ) ) ? "<span class='add-on input-group-addon'>{$element['append_before']}</span>" : '';
		$output   .= "<input type='{$element['type_input']}' class='{$element['class']}' value='{$element['std']}' id='{$element['id']}' name='{$element['id']}' DATA_INFO />";
		$output   .= ( isset( $element['append'] ) ) ? "<span class='add-on input-group-addon'>{$element['append']}</span>" : '';
		$output   .= '</div>';

		return parent::final_element( $element, $output, $label );
	}
}