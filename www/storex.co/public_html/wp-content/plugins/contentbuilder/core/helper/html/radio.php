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
class IG_Pb_Helper_Html_Radio extends IG_Pb_Helper_Html {
	/**
	 * Radio
	 * @param type $element
	 * @return string
	 */
	static function render( $element ) {
		$element['class'] = isset( $element['class'] ) ? $element['class'] : 'radio-inline';
		$element['input_type'] = 'radio';
		return IG_Pb_Helper_Shortcode::render_parameter( 'checkbox', $element );
	}
}