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
if ( ! class_exists( 'IG_Item_Carousel' ) ) {

	class IG_Item_Carousel extends IG_Pb_Shortcode_Child {

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
						'name'    => esc_html__( 'Image File',  'storex' ),
						'id'      => 'image_file',
						'type'    => 'select_media',
						'std'     => '',
						'class'   => 'jsn-input-large-fluid',
						'tooltip' => esc_html__( 'Select background image for item',  'storex' )
					),
					array(
                        'name'    => esc_html__( 'Image Size',  'storex' ),
                        'id'      => 'image_size',
                        'type'    => 'select',
                        'std'     => 'medium',
                        'options' => array(
							'thumbnail' => 'Thumbnail',
							'storex-carousel-medium' => 'Medium',
							'storex-carousel-large' => 'Large',
						),
                    ),
					array(
						'name'  => esc_html__( 'Heading',  'storex' ),
						'id'    => 'heading',
						'type'  => 'text_field',
						'class' => 'jsn-input-xxlarge-fluid',
						'role'  => 'title',
                        'tooltip' => esc_html__( 'Enter heading text for item',  'storex' ),
					),
					array(
						'name'  => esc_html__( 'Short Description',  'storex' ),
						'id'    => 'description',
						'type'  => 'text_field',
						'class' => 'jsn-input-xxlarge-fluid',
                        'tooltip' => esc_html__( 'Enter description text for item',  'storex' ),
					),
					array(
						'name'  => esc_html__( 'Alignment description',  'storex' ),
						'id'    => 'alignment_description',
						'type'  => 'select',
						'std'     => 'bottom',
                        'options' => array(
							'top' => 'Top',
							'middle' => 'Middle',
							'bottom' => 'Bottom',
						),
                    ),
					array(
					'name'    => esc_html__( 'Color description', 'storex' ),
					'id'      => 'description_color',
					'type'         => 'color_picker',
					'std'          => '#000000',
					'parent_class' => 'combo-item',
					),
					array(
						'name'       => esc_html__( 'Background items', 'storex' ),
						'id'         => 'background_items',
						'type'       => 'color_picker',
						'std'        => '#f8f8f8',
						'parent_class'    => 'combo-item',
					),
					array(
						'name'       => esc_html__( 'URL for detailed view', 'storex' ),
						'id'         => 'url',
						'type'       => 'text_field',
						'class'      => 'input-sm',
						'std'        => '',
						'tooltip'    => esc_html__( 'Url of link for detailed view', 'storex' ),
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

			global $wpdb;
			$link = preg_replace('/-\d+x\d+(?=\.(jpg|jpeg|png|gif)$)/i', '', $image_file);
			$id = $wpdb->get_var( $wpdb->prepare("SELECT ID FROM $wpdb->posts WHERE BINARY guid = %s", $link ) );

			$html_output = '';
			$img = ! empty( $image_file ) ? wp_get_attachment_image( $id, $image_size ) : '';
			$header = ! empty( $heading ) ? '<h2>'.$heading.'</h2>' : '';
			$text = ! empty( $description ) ? '<span style="color:'.$description_color.'">'.$description.'</span>' : '';
			$source = ! empty( $url ) ? $url : '';

			if($source!==''){
				$html_output .= '<a href="'.$source.'"><div class="item-wrapper ' .$padding_items.'"><figure style="background:'.$background_items.'"><div class="background_item"></div>';
				$html_output .= $img;
				$html_output .= '<figcaption>';
				$html_output .= '<div class="caption-wrapper '.$alignment_description.'">'.$text.$header.'</div>';
				$html_output .= '<div class="vertical-helper"></div></figcaption></figure></div></a><!--separate-->';
			}
			
			else{
				$html_output .= '<div class="item-wrapper"><figure style="background:'.$background_items.'"><div class="background_item"></div>';
				$html_output .= $img;
				$html_output .= '<figcaption>';
				$html_output .= '<div class="caption-wrapper '.$alignment_description.'">'.$text.$header.'</div>';
				$html_output .= '<div class="vertical-helper"></div></figcaption></figure></div><!--separate-->';
			
			}



			
			return $html_output;
		}

	}

}
