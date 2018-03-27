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
if ( ! class_exists( 'IG_Item_Tab' ) ) {
	/**
	 * Create child Tab element
	 *
	 * @package  IG PageBuilder Shortcodes
	 * @since    1.0.0
	 */
	class IG_Item_Tab extends IG_Pb_Shortcode_Child {

		public function __construct() {
			parent::__construct();
		}

		/**
		 * DEFINE configuration information of shortcode
		 */
		public function element_config() {
			$this->config['shortcode'] = strtolower( __CLASS__ );
			$this->config['exception'] = array(
				'data-modal-title' => __( 'Tab Item', IGPBL ),

			);
		}

		/**
		 * DEFINE setting options of shortcode
		 */
		public function element_items() {
			$this->items = array(
				'Notab' => array(
					array(
						'name'  => __( 'Heading', IGPBL ),
						'id'    => 'heading',
						'type'  => 'text_field',
						'class' => 'input-sm',
						'role'  => 'title',
						'std'   => __( IG_Pb_Utils_Placeholder::add_placeholder( 'Tab Item %s', 'index' ), IGPBL ),
                        'tooltip' => __( 'Set heading text of item', IGPBL ),
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
            $arr_params = ( shortcode_atts( $this->config['params'], $atts ) );
			extract( $arr_params );
			$inner_content = IG_Pb_Helper_Shortcode::remove_autop( $content );
            $custom_style  = IG_Pb_Utils_Placeholder::get_placeholder( 'custom_style' );
			return "$heading<!--heading-->$icon<!--icon--><div id='pane{index}' class='tab-pane {active} {fade_effect}'>
					{$inner_content}
				</div><!--seperate-->";
		}

	}

}
