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

if ( ! class_exists( 'IG_Table' ) ) :

/**
 * Create Table element
 *
 * @package  IG PageBuilder Shortcodes
 * @since    1.0.0
 */
class IG_Table extends IG_Pb_Shortcode_Parent {
	/**
	 * Constructor
	 *
	 * @return  void
	 */
	public function __construct() {
		parent::__construct();
	}

	/**
	 * Configure shortcode.
	 *
	 * @return  void
	 */
	public function element_config() {
		$this->config['shortcode']        = strtolower( __CLASS__ );
		$this->config['name']             = __( 'Table', IGPBL );
		$this->config['cat']              = __( 'Typography', IGPBL );
		$this->config['icon']             = 'icon-paragraph-text';
		$this->config['has_subshortcode'] = 'IG_Item_' . str_replace( 'IG_', '', __CLASS__ );

		// Define exception for this shortcode
		$this->config['exception'] = array(
			'admin_assets' => array(
				// Shortcode initialization
				'table.js',
			),

			'frontend_assets' => array(
				// Bootstrap 3
				'ig-pb-bootstrap-css',
				'ig-pb-bootstrap-js',
			),
		);

		// Do not use Ajax to load element settings modal because this element has sub-item
		$this->config['edit_using_ajax'] = true;
	}

	/**
	 * Define shortcode settings.
	 *
	 * @return  void
	 */
	public function element_items() {
		$this->items = array(
			'content' => array(
				array(
					'name'    => __( 'Element Title', IGPBL ),
					'id'      => 'el_title',
					'type'    => 'text_field',
					'class'   => 'input-sm',
					'std'     => __( '', IGPBL ),
					'role'    => 'title',
					'tooltip' => __( 'Set title for current element for identifying easily', IGPBL )
				),
				array(
					'name'          => __( 'Table Content', IGPBL ),
					'id'            => 'table_',
					'type'          => 'table',
					'shortcode'     => ucfirst( __CLASS__ ),
					'sub_item_type' => $this->config['has_subshortcode'],
					'sub_items'     => array(
						array( 'std' => "[ig_item_table tagname='tr_start' ][/ig_item_table]" ),
						array( 'std' => '' ),
						array( 'std' => '' ),
						array( 'std' => "[ig_item_table tagname='tr_end' ][/ig_item_table]" ),
						array( 'std' => "[ig_item_table tagname='tr_start' ][/ig_item_table]" ),
						array( 'std' => '' ),
						array( 'std' => '' ),
						array( 'std' => "[ig_item_table tagname='tr_end' ][/ig_item_table]" ),
					),
				),
			),
			'styling' => array(
				array(
					'type' => 'preview',
				),
				array(
					'name'    => __( 'Style', IGPBL ),
					'id'      => 'tb_style',
					'type'    => 'select',
					'class'   => 'input-sm',
					'options' => array( 'table-default' => __( 'Default', IGPBL ), 'table-striped' => __( 'Striped', IGPBL ), 'table-bordered' => __( 'Bordered', IGPBL ), 'table-hover' => __( 'Hover', IGPBL ) ),
					'std'     => 'default',
					'tooltip' => __( 'Select table style', IGPBL ),
				),
			)
		);
	}

	/**
	 * Generate HTML code from shortcode content.
	 *
	 * @param   array   $atts     Shortcode attributes.
	 * @param   string  $content  Current content.
	 *
	 * @return  string
	 */
	public function element_shortcode_full( $atts = null, $content = null ) {
		$arr_params    = ( shortcode_atts( $this->config['params'], $atts ) );

		$sub_shortcode = IG_Pb_Helper_Shortcode::remove_autop( $content );
		// seperate by cell
		$items_html    = explode( '<!--seperate-->', $sub_shortcode );

		// remove empty element
		$items_html    = array_filter( $items_html );
		$row           = 0;
		$not_empty     = 0;
		$updated_html  = array();
		foreach ( $items_html as $item ) {
			$cell_html = '';
			$cell_wrap = ( $row == 0 ) ? 'th' : 'td';
			if ( strpos( $item, 'CELL_WRAPPER' ) === false ) {
				$cell_html .= ( $item == 'tr_start' ) ? '<tr>' : '</tr>';
				if ( strip_tags( $item ) == 'tr_end' )
					$row++;
			}
			else {
				if ( strpos( $item, '<!--empty-->' ) !== false )
					$item = str_replace( '<!--empty-->', '', $item );
				else
					$not_empty++;
				$cell_html .= str_replace( 'CELL_WRAPPER', $cell_wrap, $item );
			}
			$updated_html[] = $cell_html;
		}
		$sub_shortcode = implode( '', $updated_html );
		if ( $not_empty == 0 )
			$sub_shortcode = '';

		$html_element = "<table class='table {$arr_params['tb_style']}'>" . $sub_shortcode . '</table>';
		return $this->element_wrapper( $html_element, $arr_params );
	}
}

endif;
