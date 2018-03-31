<?php

/**
 * @version	$Id$
 * @package	IG Pagebuilder
 * @author	 InnoGearsTeam <support@TI.com>
 * @copyright  Copyright (C) 2012 TI.com. All Rights Reserved.
 * @license	GNU/GPL v2 or later http://www.gnu.org/licenses/gpl-2.0.html
 *
 * Websites: http://www.TI.com
 * Technical Support:  Feedback - http://www.TI.com
 */
if ( ! class_exists( 'IG_Woo_Codes' ) ) {

	class IG_Woo_Codes extends IG_Pb_Shortcode_Parent {

		public function __construct() {
			parent::__construct();
		}

		public function element_config() {
			$this->config['shortcode'] = strtolower( __CLASS__ );
			$this->config['name'] = esc_html__( 'PT Woocommerce Shortcode',  'storex' );
            $this->config['edit_using_ajax'] = true;
            $this->config['exception'] = array(
				'default_content'  => esc_html__( 'Woocommerce Shortcode',  'storex' ),
				'data-modal-title' => esc_html__( 'Woocommerce Shortcode',  'storex' ),
			
				'admin_assets' => array(
					// Shortcode initialization
					'row.js',
					'ig-colorpicker.js',
				),

			);
		}

		public function element_items() {

			$this->items = array(
				'content' => array(
					array(
						'name'    => esc_html__( 'Element Title',  'storex' ),
						'id'      => 'el_title',
						'type'    => 'text_field',
						'class'   => 'jsn-input-xxlarge-fluid',
						'std'     => '',
						'role'    => 'title',
						'tooltip' => esc_html__( 'Set title for current element for identifying easily',  'storex' )
					),
					array(
                        'name'    => esc_html__( 'Woocommerce Shortcode',  'storex' ),
                        'id'      => 'woo_code_type',
                        'type'    => 'select',
                        'std'     => 'recent_products',
                        'options' => array(
                            'recent_products' => 'Recent Products',
                            'featured_products' => 'Featured Products',
                            'product_category' => 'Products by category',
                            'sale_products' => 'Sale Products',
                            'best_selling_products ' => 'Best Selling Products',
                            'top_rated_products' => 'Top Rated Products',
                            'product_categories' => 'Product Categories',
                        ),
                        'tooltip' => esc_html__( 'Choose Woocommerce Shortcode',  'storex' ),
                        'has_depend' => '1',
                    ),
					array(
                        'name'    => esc_html__( 'Columns quantity',  'storex' ),
                        'id'      => 'cols_qty',
                        'type'    => 'select',
                        'std'     => '4',
                        'options' => array(
                            '2' => '2 Cols',
                            '3' => '3 Cols',
                            '4' => '4 Cols',
                            '5' => '5 Cols',
                            '6 ' => '6 Cols',
                        ),
                        'tooltip' => esc_html__( 'Choose Columns Quantity',  'storex' ),
                    ),
					array(
                        'name'    => esc_html__( 'Orderby Parameter',  'storex' ),
                        'id'      => 'orderby',
                        'type'    => 'select',
                        'std'     => 'date',
                        'options' => array(
                            'date' => 'Date',
                            'title' => 'Title',
                            'name' => 'Name',
                            'ID' => 'ID',
                            'rand' => 'Random'
                        ),
                    ),
					array(
                        'name'    => esc_html__( 'Order Parameter',  'storex' ),
                        'id'      => 'order',
                        'type'    => 'select',
                        'std'     => 'ASC',
                        'options' => array(
                            'ASC' => 'Ascending',
                            'DESC' => 'Descending',
                        ),
                    ),
					array(
						'name'       => esc_html__( 'Number of Products/Categories to show',  'storex' ),
						'id'         => 'items_number',
						'type'       => 'text_append',
						'type_input' => 'number',
						'std'        => '4',
					),
                    array(
                        'name' 	  => esc_html__( 'Product Category',  'storex' ),
                        'desc'    => esc_html__( 'Enter the category which products you want to display',  'storex' ),
                        'id'      => 'product_cat',
						'type'    => 'text_field',
						'class'   => 'jsn-input-xxlarge-fluid',
                        'std'     => '',
                        'dependency' => array( 'woo_code_type', '=', 'product_category'),
                    ),
					array(
                        'name'    => esc_html__( 'Output Categories Parameter',  'storex' ),
                        'id'      => 'cats_output',
                        'type'    => 'select',
                        'std'     => '',
                        'options' => array(
                            'ids' => "Enter multiply ID's",
                            'parent' => 'Enter parent ID',
                        ),
                        'dependency' => array( 'woo_code_type', '=', 'product_categories'),
                        'has_depend' => '1',
                    ),
					array(
						'name'       => esc_html__( 'Enter Parent category ID',  'storex' ),
						'id'         => 'parent_cat',
						'type'       => 'text_append',
						'type_input' => 'number',
						'std'        => '',
						'dependency' => array( 'cats_output', '=', 'parent'),
					),
					array(
                        'name' 	  => esc_html__( "Enter categories ID's",  'storex' ),
                        'desc'    => esc_html__( 'Coma separated list of categories to display',  'storex' ),
                        'id'      => 'product_cat_ids',
						'type'    => 'text_field',
						'class'   => 'jsn-input-xxlarge-fluid',
                        'std'     => '',
                        'dependency' => array( 'cats_output', '=', 'ids'),
                    ),
				),

				'styling' => array(
					array(
						'name' => esc_html__( 'Use Owl Carousel?', 'storex' ),
						'id' => 'use_slider',
						'type' => 'radio',
						'std' => 'no',
						'options' => array( 'yes' => esc_html__( 'Yes', 'storex' ), 'no' => esc_html__( 'No', 'storex' ) ),
						'tooltip' => esc_html__( 'Show or not linked button above banner', 'storex' ),
						'has_depend' => '1',
					),
				)
			);
		}

		public function element_shortcode_full( $atts = null, $content = null ) {
			$arr_params     = shortcode_atts( $this->config['params'], $atts );
			extract( $arr_params );

			$use_slider = $arr_params['use_slider'];
			$html_output = '';

			$container_class = 'pt-woo-shortcode '.$css_suffix;
			if ( $use_slider == 'yes' ) { 
				$container_class = $container_class.' with-slider';
				$container_id = uniqid('owl',false);
			}
			$container_class = ( ! empty( $container_class ) ) ? ' class="' . $container_class . '"' : '';

			$woo_shortcode = $woo_code_type;
			$woo_cols = $cols_qty;
			$woo_orderby = $orderby;
			$woo_order = $order;
			$woo_per_page = $items_number;

			if ( $cols_qty=='2' && (pt_show_layout()!='layout-one-col') ) {
				$qty_sm = $qty_xs = 1;
				$qty_md = 2;
			} elseif ( $cols_qty=='2' && (pt_show_layout()=='layout-one-col') ) {
				$qty_sm = $qty_xs = 1;
				$qty_md = 2;
			} elseif ( $cols_qty!='2' && (pt_show_layout()!='layout-one-col') ) {
				$qty_md = 3;
				$qty_sm = 2;
				$qty_xs = 2;
			} elseif ( $cols_qty!='2' && (pt_show_layout()=='layout-one-col') ) {
				$qty_md = $cols_qty;
				$qty_sm = 2;
				$qty_xs = 1;
			}

			$html_output = "<div{$container_class} id='{$container_id}'>";
			$html_output .= "<div class='title-wrapper'><h3>{$el_title}</h3>";
			if ( $use_slider == 'yes' ) { $html_output .= "<div class='slider-navi'><span class='prev'><i class='fa fa-angle-left'></i></span><span class='next'><i class='fa fa-angle-right'></i></span></div>"; }
			$html_output .= "</div>";
			if ($woo_shortcode == 'product_category') { 
				$shortcode= "[{$woo_shortcode} per_page='{$woo_per_page}' columns='{$woo_cols}' orderby='{$woo_orderby}' order='{$woo_order}' category='{$product_cat}']"; 
			} elseif ($woo_shortcode == 'product_categories') { 
				$woo_parent_cat = ( ! empty( $parent_cat ) ) ? ' parent="' . $parent_cat . '"' : '';
				$woo_cat_ids = ( ! empty( $product_cat_ids ) ) ? ' ids="' . $product_cat_ids . '"' : '';
				$shortcode = "[{$woo_shortcode} number='{$items_number}' columns='{$woo_cols}' orderby='{$woo_orderby}' order='{$woo_order}'{$woo_parent_cat}{$woo_cat_ids}]"; 
			} elseif ($woo_shortcode == 'best_selling_products') { 
				$shortcode = "[{$woo_shortcode} columns='{$woo_cols}' per_page='{$woo_per_page}']"; 
			} else { 
				$shortcode = "[{$woo_shortcode} per_page='{$woo_per_page}' columns='{$woo_cols}' orderby='{$woo_orderby}' order='{$woo_order}']"; }
			
			$html_output .= $shortcode."</div>";

			if ( $use_slider == 'yes' ) {
				$html_output.='
				<script type="text/javascript">
					(function($) {
						$(document).ready(function() {
							var owl = $("#'.$container_id.' ul.products");
 
							owl.owlCarousel({
							items : '.$woo_cols.',        				  // items above 1000px browser width
							itemsDesktop : [1199,'.$qty_md.'], 			  // items between 1000px and 901px
							itemsDesktopSmall : [979,'.$qty_sm.'],  	  // betweem 900px and 601px
							itemsTablet: [768,'.$qty_xs.'], 		  	  // items between 600 and 0
							itemsMobile : [479,1], 						  // 1 item on Mobile dwvices
							pagination: false,
							navigation : false,
							rewindNav : false,
							scrollPerPage : false,
							});
 
							// Custom Navigation Events
							$("#'.$container_id.'").find(".next").click(function(){
							owl.trigger("owl.next");
							})
							$("#'.$container_id.'").find(".prev").click(function(){
							owl.trigger("owl.prev");
							})

						});
					})(jQuery);
				</script>';
			}

			return $this->element_wrapper( $html_output, $arr_params );
		}

	}

}