<?php
/**
 * @version    $Id$
 * @package    IG Pagebuilder
 * @author     InnoGearsTeam <support@TI.com>
 * @copyright  Copyright (C) 2012 TI.com. All Rights Reserved.
 * @license    GNU/GPL v2 or later http://www.gnu.org/licenses/gpl-2.0.html
 *
 * Websites: http://www.TI.com
 * Technical Support:  Feedback - http://www.TI.com
 */
if ( ! class_exists( 'IG_Item_Salescarousel' ) ) {

	class IG_Item_Salescarousel extends IG_Pb_Shortcode_Child {

		public function __construct() {
			parent::__construct();
		}

		/**
		 * DEFINE configuration information of shortcode
		 */
		public function element_config() {
			$this->config['shortcode'] = strtolower( __CLASS__ );
			$this->config['exception'] = array(
				'data-modal-title' => esc_html__( 'Carousel Item',  'storex' )
			);
		}

		/**
		 * DEFINE setting options of shortcode
		 */
		public function element_items() {
			$this->items = array(
				'Notab' => array(
					array(
						'name'  => esc_html__( 'Heading',  'storex' ),
						'id'    => 'heading',
						'type'  => 'text_field',
						'class' => 'jsn-input-xxlarge-fluid',
						'role'  => 'title',
						'std'   => '',
                        'tooltip' => esc_html__( 'Set the text of your heading items',  'storex' ),
					),
					array(
                        'name' 	  => esc_html__( "Enter Sale Product ID",  'storex' ),
                        'id'      => 'product_id',
						'type'    => 'text_field',
						'class'   => 'jsn-input-xxlarge-fluid',
                        'std'     => '',
                    ),
					array(
						'name'    => esc_html__( 'Target Date',  'storex' ),
						'id'      => 'target_date',
						'type'    => 'text_field',
						'class'   => 'jsn-input-xxlarge-fluid',
						'std'     => '2015-12-26',
						'tooltip' => esc_html__( 'Set target date (YYYY-MM-DD) when special offer ends',  'storex' )
					),
					array(
						'name'    => esc_html__( 'Pre-Countdown text',  'storex' ),
						'id'      => 'pre_countdown_text',
						'type'    => 'text_field',
						'class'   => 'jsn-input-xxlarge-fluid',
					),
					array(
						'name'    => esc_html__( 'Show Product Image',  'storex' ),
						'id'      => 'show_product_img',
						'type'    => 'radio',
						'std'     => 'yes',
						'options' => array( 'yes' => esc_html__( 'Yes',  'storex' ), 'no' => esc_html__( 'No',  'storex' ) ),
                        'tooltip' => esc_html__( 'Show/hide arrow buttons',  'storex' ),
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

			$target = explode("-", $target_date);
			if ( isset( $product_id ) ) {
				$product = new WC_Product( $product_id );
				$container_id = uniqid('countdown',false);
				$shortcode = '<span class="add_to_cart">[add_to_cart id='.$product_id.']</span>';
				$html_output = '<li>';
				$html_output .= '<span class="hot-deal">'.esc_html__('HOT DEAL!', 'storex').'</span>';
				// Sale value in percents
				$percentage = round( ( ( $product->regular_price - $product->sale_price ) / $product->regular_price ) * 100 );
				$html_output .= '<div class="sale-value"><p>'.esc_html__('save ap to', 'storex').'</p><div class="percentage-sale"><span class="percentage-count">'.$percentage.'</span><div class="wrapper-percentage"><span class="percentage">%</span><span class="off">'.esc_html__('OFF', 'storex').'</span></div></div></div>';
				if($show_product_img=='yes'){
				$html_output .= '<div class="img-wrapper">
									<a href="'.$product->get_permalink().'" class="link-to-product">'
										.$product->get_image( 'shop_catalog' ).
									'</a></div>';
				}					
				$html_output .= '<div class="counter-wrapper">';


				$html_output .= '<div class="countdown-wrapper">';
				if ($heading){
					$html_output .='<h3>'.$heading.'</h3>';
				}
				if ( $pre_countdown_text && $pre_countdown_text!='' ) {
					$html_output .= '<p>'.$pre_countdown_text.'</p>';
				}
				$html_output .= '<div id="'.$container_id.'"></div></div>';

				$html_output .= '<div class="price-wrapper">
									'.$shortcode.'
								</div>';

				if ( $target && $target!='' ) {
					$html_output.='
					<script type="text/javascript">
						(function($) {
							$(document).ready(function() {

								var container = $("#'.$container_id.'");
								var newDate = new Date('.$target[0].', '.$target[1].'-1, '.$target[2].');
								container.countdown({
									until: newDate,
									compact: true, 
									layout: \'<div id="countdown-Layout">\'+
									\'<div class="countdown-day">\'+									
									\'<span class="countdown-amount">{d10}</span>\'+ 
									\'<span class="countdown-amount">{d1}</span>\'+
									\'<div class="day">days</div>\'+
									\'</div>\'+
									\'<div class="countdown-hour">\'+
									\'<span class="countdown-amount">{h10}</span>\'+ 
									\'<span class="countdown-amount">{h1}</span>\'+ 
									\'<div class="hour">hours</div>\'+
									\'</div>\'+
									\'<div class="countdown-minute">\'+
									\'<span class="countdown-amount">{m10}</span>\'+ 
									\'<span class="countdown-amount">{m1}</span>\'+ 
									\'<div class="minute">minutes</div>\'+
									\'</div>\'+
									\'<div class="countdown-second">\'+
									\'<span class="countdown-amount">{s10}</span>\'+
									\'<span class="countdown-amount">{s1}</span>\'+
									\'<div class="second">seconds</div>\'+									
									\'</div>\'+
									\'</div>\'
									});
							});
						})(jQuery);
					</script>';
				}
									
				$html_output .= '</div></li>';
			}

			return $html_output;
		}

	}

}
