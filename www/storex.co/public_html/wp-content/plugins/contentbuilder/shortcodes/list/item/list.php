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
if ( ! class_exists( 'IG_Item_List' ) ) {

	class IG_Item_List extends IG_Pb_Shortcode_Child {

		public function __construct() {
			parent::__construct();
		}

		/**
		 * DEFINE configuration information of shortcode
		 */
		public function element_config() {
			$this->config['shortcode'] = strtolower( __CLASS__ );
			$this->config['exception'] = array(
				'data-modal-title' => __( 'List Item', IGPBL ),

			);
		}

		/**
		 * DEFINE setting options of shortcode
		 */
		public function element_items() {
			$this->items = array(
				'Notab' => array(
					array(
						'name'    => __( 'Heading', IGPBL ),
						'id'      => 'heading',
						'type'    => 'text_field',
						'class'   => 'jsn-input-xxlarge-fluid',
						'role'    => 'title',
						'std'     => __( IG_Pb_Utils_Placeholder::add_placeholder( 'List Item %s', 'index' ), IGPBL ),
						'tooltip' => __( 'Set heading text of item', IGPBL )
					),
					array(
						'name'    => __( 'Body', IGPBL ),
						'id'      => 'body',
						'role'    => 'content',
						'type'    => 'editor',
						'std'     => IG_Pb_Helper_Type::lorem_text(),
						'tooltip' => __( 'Set content of element', IGPBL )
					),
					array(
						'name'    => __( 'Add Button Text', 'plumtree'),
						'id'      => 'button_list_text',
						'type'    => 'text_field',
						'class'   => 'jsn-input-xxlarge-fluid',
						'std'     => '',
					),
					array(
						'name'    => __( 'Add Source Button', 'plumtree'),
						'id'      => 'button_list_text_source',
						'type'    => 'text_field',
						'class'   => 'jsn-input-xxlarge-fluid',
						'std'     => '',
					),
					array(
						'name'       => __( 'Vertical Alignment Icon', 'plumtree' ),
						'id'         => 'vertical_alignment_icon',
						'type'       => 'radio',
						'std'        => 'middle',
						'options'    => array( 'top' => __( 'Top', 'plumtree' ), 'middle' => __( 'Middle', 'plumtree' ), 'bottom' => __( 'Bottom', 'plumtree' ) ),
					),
					array(
						'name'      => __( 'Icon', IGPBL ),
						'id'        => 'icon',
						'type'      => 'icons',
						'std'       => '',
						'role'      => 'title_prepend',
						'title_prepend_type' => 'icon',
						'tooltip'   => __( 'Select an icon', IGPBL )
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
            IG_Pb_Helper_Functions::heading_icon( $heading, $icon, true );
			if ($button_list_text!==''){
				$button_list='<a class="button-list" href="'.$button_list_text_source.'">'.$button_list_text.'</a>';
			}
			return "
			<li>
				[icon]<div class='ig-sub-icons' style='ig-styles'>
					<i class='$icon' style='vertical-align:$vertical_alignment_icon'></i>
				</div>[/icon]
				<div class='ig-list-content-wrap'>
					[heading]<h4 style='ig-list-title'>$heading</h4>[/heading]
					<div class='ig-list-content'>
						$content
					</div>
					$button_list
				</div>
			</li><!--seperate-->";
		}

	}

}
