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
class IG_Pb_Helper_Html_Group_Table extends IG_Pb_Helper_Html {
	/**
	 * Group items
	 *
	 * @param type $element
	 *
	 * @return string
	 */
	static function render( $element ) {
		$_element   = $element;
		$label_item = ( isset( $element['label_item'] ) ) ? $element['label_item'] : '';

		$sub_items                = $_element['sub_items'];
		$overwrite_shortcode_data = isset( $element['overwrite_shortcode_data'] ) ? $element['overwrite_shortcode_data'] : true;
		$sub_item_type            = $element['sub_item_type'];
		$items_html               = array();
		$shortcode_name           = str_replace( 'IG_', '', $element['shortcode'] );

		// get id of parameter to extract
		$extract_title = isset ( $element['extract_title'] ) ? $element['extract_title'] : '';
		
		$extra_params = array(
			'drag_handle' => false
		);

		if ( $sub_items ) {
			foreach ( $sub_items as $idx => $item ) {
				$el = new $sub_item_type();
				$el->init_element();
				// check if $item['std'] is empty or not
				$shortcode_data = '';
				if ( ! $label_item ) {
					$content = __( $shortcode_name, IGPBL ) . ' ' . __( 'Item', IGPBL ) . ' ' . ( $idx + 1 );
				} else {
					$content = $label_item . ( $idx + 1 );
				}
				if ( isset( $_element['no_title'] ) ) {
					$content = $_element['no_title'];
				}

				if ( ! empty( $item['std'] ) ) {
					// keep shortcode data as it is
					$shortcode_data = $item['std'];
					// reassign params for shortcode base on std string
					$extract_params = IG_Pb_Helper_Shortcode::extract_params( ( $item['std'] ) );
					$params         = IG_Pb_Helper_Shortcode::generate_shortcode_params( $el->items, NULL, $extract_params, TRUE, FALSE, $content );
					$el->shortcode_data();
					$params['extract_title'] = empty ( $params['extract_title'] ) ? __( '(Untitled)', IGPBL ) : $params['extract_title'];
					$content                 = $params['extract_title'];
					if ( $overwrite_shortcode_data ) {
						$shortcode_data = $el->config['shortcode_structure'];
					}
				}

				$element_type = (array) $el->element_in_pgbldr( $content, $shortcode_data, '', '', true, $extra_params );
				foreach ( $element_type as $element_structure ) {
					$items_html[$shortcode_data] = $element_structure;
				}
			}
		}

		$style = ( isset( $_element['style'] ) ) ? 'style="' . $_element['style'] . '"' : '';

		// Wrap item html to table
		$html = '';
		foreach ( $items_html as $shortcode_data => $item_html ) {
			if ( ! empty ( $extract_title ) ) {
				$attrs = shortcode_parse_atts( $shortcode_data );
				$title = isset ( $attrs[$extract_title] ) ? $attrs[$extract_title] : '';
				$html .= sprintf( '<tr><td><b>%s</b></td><td>%s</td></tr>', $title, $item_html );
			}
		}

		$html = sprintf( '<table class="%s" %s>%s</table>', 'table table-bordered', $style, balanceTags( $html ) );

		$element_name = ( isset( $_element['name'] ) ) ? $_element['name'] : __( ucwords( ( ! $label_item ) ? $shortcode_name : $label_item ), IGPBL ) . ' ' . __( 'Items', IGPBL );
		$html_element = "<div id='{$_element['id']}' class='form-group control-group clearfix'><label class='col-xs-3 control-label'>{$element_name}</label>
				<div class='item-container submodal_frame_2 controls col-xs-9 group-table {$_element['class']}'>
                    <div class='item-container-content jsn-items-list'>
                        $html
                    </div>
                </div>
                </div>";

		return $html_element;
	}
}