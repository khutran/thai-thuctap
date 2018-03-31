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
if ( ! class_exists( 'IG_Item_Testimonials' ) ) {

	class IG_Item_Testimonials extends IG_Pb_Shortcode_Child {

		public function __construct() {
			parent::__construct();
		}

		/**
		 * DEFINE configuration information of shortcode
		 */
		public function element_config() {
			$this->config['shortcode'] = strtolower( __CLASS__ );
			$this->config['exception'] = array(
				'data-modal-title' => esc_html__( 'Testimonials Item',  'storex' )
			);
            $this->config['edit_using_ajax'] = true;
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
                        'tooltip' => esc_html__( 'Set the text of your heading items',  'storex' ),
					),
                    array(
                        'name'    => esc_html__( 'Image File',  'storex' ),
                        'id'      => 'image_file',
                        'type'    => 'select_media',
                        'std'     => '',
                        'class'   => 'jsn-input-large-fluid',
                        'tooltip' => esc_html__( 'Select background image for item',  'storex' )
                    ),
                    array(
						'name'    => esc_html__( 'Name', 'storex' ),
						'id'      => 'name',
						'type'    => 'text_field',
						'class'   => 'input-sm',
					),
					array(
						'name'    => esc_html__( 'Color Text Name', 'storex' ),
						'id'      => 'text_name_color',
					'type'         => 'color_picker',
					'std'          => '#ffffff',
					'parent_class' => 'combo-item',
					),
					array(
						'name'    => esc_html__( 'Occupation', 'storex' ),
						'id'      => 'occupation',
						'type'    => 'text_field',
						'class'   => 'input-sm',
					),
					array(
					'name'    => esc_html__( 'Color Occupation', 'storex' ),
					'id'      => 'occupation_color',
					'type'         => 'color_picker',
					'std'          => '#ffffff',
					'parent_class' => 'combo-item',
					),
					array(
						'name' => esc_html__( 'Text',  'storex' ),
						'id'   => 'body',
						'role' => 'content',
						'type' => 'editor',
						'std'  => IG_Pb_Helper_Type::lorem_text(),
                        'tooltip' => esc_html__( 'Set content of element',  'storex' ),
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

			$html_output = '';

			// Main Elements
			$image = '';
			if ( $image_file ) {
				$image = "<img src='{$image_file}' alt='{$name}' />";
			}
			$heading = '';
			if ( $name ) {
				$heading = "<h3 style='color:{$text_name_color}'>{$name}</h3>";
			}
			$sub_heading = '';
			if ( $occupation ) {
				$sub_heading = "<span style='color:{$occupation_color}'>{$occupation}</span>";
			}
			$inner_content = IG_Pb_Helper_Shortcode::remove_autop( $content );

			// Shortcode output
			$html_output .= '<div class="item-wrapper">';
			$html_output .= '<div class="text-wrapper"><p><q>'.$inner_content.'</q></p>'.$heading.$sub_heading.'</div>';
			$html_output .= '<div class="img-wrapper">'.$image.'</div>';
			$html_output .= '</div><!--separate-->';

			return $html_output;

		}

	}

}
