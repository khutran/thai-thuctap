<?php

/**
 * @version	$Id$
 * @package	IG PageBuilder
 * @author	 InnoGears Team <support@innogears.com>
 * @copyright  Copyright (C) 2012 innogears.com. All Rights Reserved.
 * @license	GNU/GPL v2 or later http://www.gnu.org/licenses/gpl-2.0.html
 *
 * Websites: http://www.innogears.com
 * Technical Support:  Feedback - http://www.innogears.com
 */

if ( ! class_exists( 'IG_Tab' ) ) :

/**
 * Create Tabs element
 *
 * @package  IG PageBuilder Shortcodes
 * @since    1.0.0
 */
class IG_Tab extends IG_Pb_Shortcode_Parent {
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
		$this->config['name']             = __( 'Tab', IGPBL );
		$this->config['cat']              = __( 'Typography', IGPBL );
		$this->config['icon']             = 'icon-paragraph-text';
		$this->config['has_subshortcode'] = 'IG_Item_' . str_replace( 'IG_', '', __CLASS__ );

		// Define exception for this shortcode
		$this->config['exception'] = array(
			'frontend_assets' => array(
				// Bootstrap 3
				'ig-pb-bootstrap-css',
				'ig-pb-bootstrap-js',

				// Font IcoMoon
				'ig-pb-font-icomoon-css',

				// Shortcode style
				'tab_frontend.css',
			),
		);

		// Use Ajax to speed up element settings modal loading speed
		$this->config['edit_using_ajax'] = true;
	}

	/**
	 * Define shortcode settings.
	 *
	 * @return  void
	 */
	public function element_items() {
		$this->items = array(
			'action' => array(
				array(
					'id'      => 'btn_convert',
					'type'    => 'button_group',
					'bound'   => 0,
					'actions' => array(
						array(
							'std'         => __( 'Accordion', IGPBL ),
							'action_type' => 'convert',
							'action'      => 'tab_to_accordion',
						),
						array(
							'std'         => __( 'Carousel', IGPBL ),
							'action_type' => 'convert',
							'action'      => 'tab_to_carousel',
						),
						array(
							'std'         => __( 'List', IGPBL ),
							'action_type' => 'convert',
							'action'      => 'tab_to_list',
						),
					)
				),
			),
			'content' => array(
				array(
					'name'    => __( 'Element Title', IGPBL ),
					'id'      => 'el_title',
					'type'    => 'text_field',
					'class'   => 'jsn-input-xxlarge-fluid',
					'std'     => __( '', IGPBL ),
					'role'    => 'title',
					'tooltip' => __( 'Set title for current element for identifying easily', IGPBL )
				),
				array(
					'id'            => 'tab_items',
					'type'          => 'group',
					'shortcode'     => ucfirst( __CLASS__ ),
					'sub_item_type' => $this->config['has_subshortcode'],
					'sub_items'     => array(
						array( 'std' => '' ),
						array( 'std' => '' ),
					),
				),
			),
			'styling' => array(
				array(
					'type' => 'preview',
				),
				array(
					'name'     => __( 'Initial Open', IGPBL ),
					'id'       => 'initial_open',
					'type'     => 'text_number',
					'std'      => '1',
					'class'    => 'input-mini',
					'validate' => 'number',
					'tooltip' => __( 'Sets which item will be opened first', IGPBL ),
				),
				array(
					'name'       => __( 'Fade Effect', IGPBL ),
					'id'         => 'fade_effect',
					'type'       => 'radio',
					'std'        => 'no',
					'options'    => array( 'yes' => __( 'Yes', IGPBL ), 'no' => __( 'No', IGPBL )),
					'tooltip'    => __( 'Whether to using fade effect or not', IGPBL ),
					'has_depend' => '1',
				),
				array(
					'name'    => __( 'Tab Position', IGPBL ),
					'id'      => 'tab_position',
					'type'    => 'select',
					'class'   => 'input-sm',
					'std'     => 'top',
					'options' => array( 'top' => __( 'Top', IGPBL ), 'bottom' => __( 'Bottom', IGPBL ), 'left' => __( 'Left', IGPBL ), 'right' => __( 'Right', IGPBL ) ),
					'tooltip' => __( 'Setting position: right, left, center, inherit parent style', IGPBL ),
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
		$arr_params   = ( shortcode_atts( $this->config['params'], $atts ) );
		$initial_open = intval( $arr_params['initial_open'] );
		$tab_position = ( $arr_params['tab_position'] );

		$random_id    = IG_Pb_Utils_Common::random_string();

		$tab_navigator   = array();
		$tab_navigator[] = '<ul class="nav nav-tabs">';

		$sub_shortcode = IG_Pb_Helper_Shortcode::remove_autop( $content );
		$items         = explode( '<!--seperate-->', $sub_shortcode );
		$items         = array_filter( $items );
		$initial_open  = ( $initial_open > count( $items ) ) ? 1 : $initial_open;
		$fade_effect = '';
		if ( $arr_params['fade_effect'] == 'yes' ) {
			$fade_effect = 'fade in';
		}
		foreach ( $items as $idx => $item ) {
			// Extract icon & heading
			$ex_heading = explode( '<!--heading-->', $item );
			$ex_icon    = explode( '<!--icon-->', isset ( $ex_heading[1] ) ? $ex_heading[1] : '' );

			$new_key = $random_id . $idx;
			$active  = ( $idx + 1 == $initial_open ) ? 'active' : '';

			$item            = isset ( $ex_icon[1] ) ? $ex_icon[1] : '';
			$item            = str_replace( '{index}', $new_key, $item );
			$item            = str_replace( '{active}', $active, $item );
			$item            = str_replace( '{fade_effect}', $fade_effect, $item );
			$items[ $idx ] = $item;

			$icon    = ! empty ( $ex_icon[0] ) ?  "<i class='{$ex_icon[0]}'></i>&nbsp;" : '';
			$heading = ! empty ( $ex_heading[0] ) ? $ex_heading[0] : ( __( 'Tab Item ' ) . ' ' . $idx );
			IG_Pb_Helper_Functions::heading_icon( $heading, $icon );
			$active_li       = ( $idx + 1 == $initial_open ) ? "class='active'" : '';
			$tab_navigator[] = "<li $active_li><a href='#pane$new_key' data-toggle='tab'>{$icon}{$heading}</a></li>";
		}
		$sub_shortcode = implode( '', $items );
		$tab_content   = "<div class='tab-content'>$sub_shortcode</div>";
		// update min-height of each tab content in case tap position is left/right
		if ( in_array( $tab_position, array( 'left', 'right' ) ) ) {
			$min_height  = 36 * count( $items );
			$tab_content = IG_Pb_Utils_Placeholder::remove_placeholder( $tab_content, 'custom_style', "style='min-height: {$min_height}px'" );
		}

		$tab_navigator[] = '</ul>';

		$tab_positions = array( 'top' => '', 'left' => 'tabs-left', 'right' => 'tabs-right', 'bottom' => 'tabs-below' );
		$extra_class = $tab_positions[ $tab_position ];
		if ( $tab_position == 'bottom' ) {
			$tab_content .= implode( '', $tab_navigator );
		} else {
			$tab_content = implode( '', $tab_navigator ) . $tab_content;
		}


		$html_element = "<div class='tabbable $extra_class' id='tab_{ID}'>$tab_content</div>";
		$html_element = str_replace( '{ID}', "$random_id", $html_element );

		return $this->element_wrapper( $html_element, $arr_params );
	}
}

endif;
