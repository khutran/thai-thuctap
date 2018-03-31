<?php
/**
 * @version    $Id$
 * @package    IG PageBuilder
 * @author     InnoGears Team <support@innogears.com>
 * @copyright  Copyright (C) 2012 innogears.com. All Rights Reserved.
 * @license    GNU/GPL v2 or later http://www.gnu.org/licenses/gpl-2.0.html
 *
 * Websites: http://www.innogears.com
 * Technical Support:  Feedback - http://www.innogears.com
 */
if ( ! class_exists( 'IG_Item_Table' ) ) {

	/**
	 * Create Table child element
	 *
	 * @package  IG PageBuilder Shortcodes
	 * @since    1.0.0
	 */
	class IG_Item_Table extends IG_Pb_Shortcode_Child {

		public function __construct() {
			parent::__construct();
		}

		/**
		 * DEFINE configuration information of shortcode
		 */
		public function element_config() {
			$this->config['shortcode'] = strtolower( __CLASS__ );
			$this->config['exception'] = array(
				'item_text'        => __( '', IGPBL ),
				'data-modal-title' => __( 'Table Item', IGPBL ),
				'item_wrapper'     => 'div',
				'action_btn'       => 'edit',

				'admin_assets' => array(
					// Shortcode initialization
					'item_table.js',
				),
			);
		}

		/**
		 * DEFINE setting options of shortcode
		 */
		public function element_items() {
			$this->items = array(
				'Notab' => array(
					array(
						'name' => __( 'Width', IGPBL ),
						'type' => array(
							array(
								'id'           => 'width_value',
								'type'         => 'text_number',
								'std'          => '',
								'class'        => 'input-mini',
								'validate'     => 'number',
								'parent_class' => 'combo-item merge-data',
							),
							array(
								'id'           => 'width_type',
								'type'         => 'select',
								'class'        => 'input-mini',
								'options'      => array( '%' => '%', 'px' => 'px' ),
								'std'          => '%',
								'parent_class' => 'combo-item merge-data',
							),
						),
						'container_class' => 'combo-group',
                        'tooltip' => __( 'Set the width of a row (px or %)', IGPBL ),
					),
					array(
						'name'            => __( 'Tag Name', IGPBL ),
						'id'              => 'tagname',
						'type'            => 'text_field',
						'std'             => 'td',
						'input_type'      => 'hidden',
						'container_class' => 'hidden',
                        'tooltip' => __( '', IGPBL ),
					),
					array(
						'name'     => __( 'Row Span', IGPBL ),
						'id'       => 'rowspan',
						'type'     => 'text_number',
						'std'      => '1',
						'class'    => 'input-mini positive-val',
						'validate' => 'number',
						'role'     => 'extract',
                        'tooltip' => __( 'Enable extending over multiple rows', IGPBL ),
					),
					array(
						'name'     => __( 'Column Span', IGPBL ),
						'id'       => 'colspan',
						'type'     => 'text_number',
						'std'      => '1',
						'class'    => 'input-mini positive-val',
						'validate' => 'number',
						'role'     => 'extract',
                        'tooltip' => __( 'Enable extending over multiple columns', IGPBL ),
					),
					array(
						'name'    => __( 'Row Style', IGPBL ),
						'id'      => 'rowstyle',
						'type'    => 'select',
						'class'   => 'input-sm',
						'std'     => IG_Pb_Helper_Type::get_first_option( IG_Pb_Helper_Type::get_table_row_color() ),
						'options' => IG_Pb_Helper_Type::get_table_row_color(),
						'tooltip' => __( 'Select a style for a row', IGPBL )
					),
					array(
						'name'   => __( 'Content', IGPBL ),
						'id'     => 'cell_content',
						'role'   => 'content',
						'role_2' => 'title',
						'type'   => 'editor',
						'std'    => __( '', IGPBL ),
                        'tooltip' => __( 'Table content', IGPBL ),
					),
				)
			);
		}

		/**
		 * DEFINE shortcode content
		 *
		 * @param type $atts
		 * @param type $content
		 */
		public function element_shortcode_full( $atts = null, $content = null ) {
			extract( shortcode_atts( $this->config['params'], $atts ) );
			$rowstyle       = ( ! $rowstyle || strtolower( $rowstyle ) == 'default' ) ? '' : $rowstyle;
			if ( in_array( $tagname, array( 'tr_start', 'tr_end' ) ) ) {
				return "$tagname<!--seperate-->";
			}
			$width = ! empty( $width_value ) ? "width='$width_value$width_type'" : '';
			$empty = empty( $content ) ? '<!--empty-->' : '';
			return "<CELL_WRAPPER class='$rowstyle' rowspan='$rowspan' colspan='$colspan' $width>" . IG_Pb_Helper_Shortcode::remove_autop( $content ) . "</CELL_WRAPPER>$empty<!--seperate-->";
		}

	}

}
