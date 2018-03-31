<?php
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

class WR_Megamenu_Helpers_Html_Type_Group extends WR_Megamenu_Helpers_Html {
	/**
	 * List of "items_list"
	 * @param type $element
	 * @return type
	 */
	static function render( $element ) {
		$element = parent::get_extra_info( $element );
		$label   = parent::get_label( $element );
		$output  = '';
		$items   = isset( $element['items'] ) ? $element['items'] : '';

		if ( is_array( $items ) ) {
			foreach ( $items as $element_ ) {
				$element_func = $element_['type'];
				$element_['wrap'] = '0';
				$element_['wrap_class'] = '';
				$element_['std'] = $element['std'];
				$element_['id'] = $element['id'];

				$output .= WR_Megamenu_Helpers_Shortcode::render_parameter( $element_func, $element_ );
			}
		}
		return parent::final_element( $element, $output, $label );
	}
}