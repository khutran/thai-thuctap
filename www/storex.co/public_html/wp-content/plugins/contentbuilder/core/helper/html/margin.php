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
class IG_Pb_Helper_Html_Margin extends IG_Pb_Helper_Html {
	/**
	 * Option to Define top/right/bottom/left margin for element
	 * @param type $element
	 * @param type $input_params
	 * @return type
	 */
	static function render( $element, $input_params ) {
		$element = parent::get_extra_info( $element );
		$label = parent::get_label( $element );
		$_no_prefix_id = str_replace( 'param-', '', $element['id'] );
		// Set default margin element
		// t: top
		// r: right
		// b: bottom
		// l: left
		$element['margin_elements'] = isset( $element['margin_elements'] ) ? explode( ',', str_replace( ' ', '', ($element['margin_elements']) ) ) : array( 't', 'r', 'b', 'l' );

		$output = '';
		$_br    = false;
		if ( in_array( 't', $element['margin_elements'] ) ) {
			$_idx_top = $_no_prefix_id . '_top';
			$_br = true;
			$element['top_std'] = isset( $element[$_idx_top] ) ? $element[$_idx_top]['std'] : '';
			$element['top_std'] = isset( $input_params[$_idx_top] ) ? $input_params[$_idx_top] : $element['top_std'];
			$_top = array(
				'id' => $element['id'] . '_top',
				'type' => 'text_append',
				'input_type' => 'number',
				'class' => 'jsn-input-number input-mini',
				'parent_class' => 'input-group-inline',
				'std' => $element['top_std'],
				'append_before' => '<i class="icon-arrow-up"></i>',
				'append' => 'px',
				'validate' => 'number',
				'bound' => '0',
			);
			$output .= IG_Pb_Helper_Shortcode::render_parameter( 'text_append', $_top );
		}

		if ( in_array( 'r', $element['margin_elements'] ) ) {
			$_idx_right = $_no_prefix_id . '_right';
			$_br = true;
			$element['right_std'] = isset( $element[$_idx_right] ) ? $element[$_idx_right]['std'] : '';
			$element['right_std'] = isset( $input_params[$_idx_right] ) ? $input_params[$_idx_right] : $element['right_std'];
			$_right = array(
				'id' => $element['id'] . '_right',
				'input_type' => 'number',
				'class' => 'jsn-input-number input-mini',
				'parent_class' => 'input-group-inline',
				'std' => $element['right_std'],
				'append_before' => '<i class="icon-arrow-right"></i>',
				'append' => 'px',
				'validate' => 'number',
				'bound' => '0',
			);
			$output .= IG_Pb_Helper_Shortcode::render_parameter( 'text_append', $_right );
		}

		$output .= $_br ? '<div class="clearbreak"></div>' : '';

		if ( in_array( 'b', $element['margin_elements'] ) ) {
			$_idx_bottom = $_no_prefix_id . '_bottom';
			$element['bottom_std'] = isset( $element[$_idx_bottom] ) ? $element[$_idx_bottom]['std'] : '';
			$element['bottom_std'] = isset( $input_params[$_idx_bottom] ) ? $input_params[$_idx_bottom] : $element['bottom_std'];
			$_bottom = array(
				'id' => $element['id'] . '_bottom',
				'input_type' => 'number',
				'class' => 'jsn-input-number input-mini',
				'parent_class' => 'input-group-inline',
				'std' => $element['bottom_std'],
				'append_before' => '<i class="icon-arrow-down"></i>',
				'append' => 'px',
				'validate' => 'number',
				'bound' => '0',
			);
			$output .= IG_Pb_Helper_Shortcode::render_parameter( 'text_append', $_bottom );
		}

		if ( in_array( 'l', $element['margin_elements'] ) ) {
			$_idx_left = $_no_prefix_id . '_left';
			$element['left_std'] = isset( $element[$_idx_left] ) ? $element[$_idx_left]['std'] : '';
			$element['left_std'] = isset( $input_params[$_idx_left] ) ? $input_params[$_idx_left] : $element['left_std'];
			$_left = array(
				'id' => $element['id'] . '_left',
				'input_type' => 'number',
				'class' => 'jsn-input-number input-mini',
				'parent_class' => 'input-group-inline',
				'std' => $element['left_std'],
				'append_before' => '<i class="icon-arrow-left"></i>',
				'append' => 'px',
				'validate' => 'number',
				'bound' => '0',
			);
			$output .= IG_Pb_Helper_Shortcode::render_parameter( 'text_append', $_left );
		}

		return parent::final_element( $element, $output, $label );
	}
}