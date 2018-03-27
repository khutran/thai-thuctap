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
class IG_Pb_Helper_Html_Text_Number extends IG_Pb_Helper_Html {
	/**
	 * Simple Input Number
	 * @param type $element
	 * @return string
	 */
	static function render( $element ) {
		$element = parent::get_extra_info( $element );
		$label   = parent::get_label( $element );
		$output  = "<input type='number' class='{$element['class']}' value='{$element['std']}' id='{$element['id']}' name='{$element['id']}' DATA_INFO />";
		return parent::final_element( $element, $output, $label );
	}
}