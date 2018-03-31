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
if ( ! class_exists( 'IG_Item_Carousel' ) ) {

	/**
	 * Create Carousel element
	 *
	 * @package  IG PageBuilder Shortcodes
	 * @since    1.0.0
	 */
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
				'data-modal-title' => __( 'Carousel Item', IGPBL ),

			);
		}

		/**
		 * DEFINE setting options of shortcode
		 */
		public function element_items() {
			$this->items = array(
				'Notab' => array(
					array(
						'name'    => __( 'Image File', IGPBL ),
						'id'      => 'image_file',
						'type'    => 'select_media',
						'std'     => '',
						'class'   => 'jsn-input-large-fluid',
						'tooltip' => __( 'Select background image for item', IGPBL )
					),
					array(
						'name'  => __( 'Heading', IGPBL ),
						'id'    => 'heading',
						'type'  => 'text_field',
						'class' => 'input-sm',
						'role'  => 'title',
						'std'   => __( IG_Pb_Utils_Placeholder::add_placeholder( 'Carousel Item %s', 'index' ), IGPBL ),
                        'tooltip' => __( 'Set the text of your heading items', IGPBL ),
					),
					array(
						'name' => __( 'Body', IGPBL ),
						'id'   => 'body',
						'role' => 'content',
						'type' => 'editor',
						'std'  => IG_Pb_Helper_Type::lorem_text(),
                        'tooltip' => __( 'Set content of element', IGPBL ),
					),
					array(
						'name'      => __( 'Icon', IGPBL ),
						'id'        => 'icon',
						'type'      => 'icons',
						'std'       => '',
						'role'      => 'title_prepend',
						'title_prepend_type' => 'icon',
                        'tooltip' => __( 'Select an icon', IGPBL ),
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
			$content_class = ! empty( $image_file ) ? 'carousel-caption' : 'carousel-content';
			$img           = ! empty( $image_file ) ? "<img src='$image_file' style='{HEIGHT}'>" : '';

			// remove image shortcode in content
			$content = IG_Pb_Helper_Shortcode::remove_ig_shortcodes( $content, 'ig_image' );

			$inner_content = IG_Pb_Helper_Shortcode::remove_autop( $content );
            IG_Pb_Helper_Functions::heading_icon( $heading, $icon, true );

            if ( empty( $heading ) && empty( $inner_content ) ) {
            	$html_content = "";
            } else {
            	$html_content = "<div class='$content_class'>";
            	$html_content .= ( ! empty( $heading ) ) ? "<h4><i class='$icon'></i>$heading</h4>" : '';
            	$html_content .= ( ! empty( $inner_content ) ) ? "<p>{$inner_content}</p>" : '';
            	$html_content .= "</div>";
            }

			return "<div class='{active} item'>{$img}{$html_content}</div><!--seperate-->";
		}

	}

}
