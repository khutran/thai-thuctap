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

if ( ! class_exists( 'IG_Button' ) ) :

/**
 * Create button elements
 *
 * @package  IG PageBuilder Shortcodes
 * @since    2.1.0
 */
class IG_Button extends IG_Pb_Shortcode_Element {

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
		$this->config['shortcode'] = strtolower( __CLASS__ );
		$this->config['name']      = __( 'Button', IGPBL );
		$this->config['cat']       = __( 'Extra', IGPBL );
		$this->config['icon']      = 'icon-paragraph-text';

		// Define exception for this shortcode
		$this->config['exception'] = array(
			'default_content'  => __( 'Button', IGPBL ),
			'data-modal-title' => __( 'Button', IGPBL ),

			'admin_assets' => array(
				// Shortcode initialization
				'ig-linktype.js',
				'button.js',
			),

			'frontend_assets' => array(
				// Bootstrap 3
				//'ig-pb-bootstrap-css',
				//'ig-pb-bootstrap-js',

				// Font IcoMoon
				//'ig-pb-font-icomoon-css',

				// Fancy Box
				'ig-pb-jquery-fancybox-css',
				'ig-pb-jquery-fancybox-js',
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
					'name'    => __( 'Text', IGPBL ),
					'id'      => 'button_text',
					'type'    => 'text_field',
					'std'     => __( 'Button', IGPBL ),
					'role'    => 'title',
					'tooltip' => __( 'Set the text on the button', IGPBL )
				),
				array(
					'name'       => __( 'On Click', IGPBL ),
					'id'         => 'link_type',
					'type'       => 'select',
					'class'      => 'input-sm',
					'std'        => 'url',
					'options'    => IG_Pb_Helper_Type::get_link_types(),
					'has_depend' => '1',
					'tooltip' => __( 'Select link types: link to post, page, category...', IGPBL ),
				),
				array(
					'name'       => __( 'URL', IGPBL ),
					'id'         => 'button_type_url',
					'type'       => 'text_field',
					'class'      => 'input-sm',
					'std'        => 'http://',
					'dependency' => array( 'link_type', '=', 'url' ),
					'tooltip' => __( 'Set url of button', IGPBL ),
				),
				array(
					'name'  => __( 'Single Item', IGPBL ),
					'id'    => 'single_item',
					'type'  => 'type_group',
					'std'   => '',
					'items' => IG_Pb_Helper_Type::get_single_item_button_bar(
						'link_type',
						array(
							'type'         => 'items_list',
							'options_type' => 'select',
							'ul_wrap'      => false,
						)
					),
					'tooltip' => __( 'Choose item to link to', IGPBL ),
				),
				array(
					'name'       => __( 'Open in', IGPBL ),
					'id'         => 'open_in',
					'type'       => 'select',
					'class'      => 'input-sm',
					'std'        => IG_Pb_Helper_Type::get_first_option( IG_Pb_Helper_Type::get_open_in_options() ),
					'options'    => IG_Pb_Helper_Type::get_open_in_options(),
					'dependency' => array( 'link_type', '!=', 'no_link' ),
					'tooltip' => __( 'Select type of opening action when click on element', IGPBL ),
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
			),
			'styling' => array(
				/*array(
					'type' => 'preview',
				),*/
				array(
					'name'    => __( 'Size', IGPBL ),
					'id'      => 'button_size',
					'type'    => 'select',
					'class'   => 'input-sm',
					'std'     => IG_Pb_Helper_Type::get_first_option( IG_Pb_Helper_Type::get_button_size() ),
					'options' => IG_Pb_Helper_Type::get_button_size(),
					'tooltip' => __( 'Set the size of the button', IGPBL ),
				),
				array(
					'name'    => __( 'Color', IGPBL ),
					'id'      => 'button_color',
					'type'    => 'select',
					'std'     => IG_Pb_Helper_Type::get_first_option( IG_Pb_Helper_Type::get_button_color() ),
					'options' => IG_Pb_Helper_Type::get_button_color(),
					'container_class'   => 'color_select2',
					'tooltip' => __( 'Select the color of the button', IGPBL ),
				),
				array(
					'name'            => __( 'Margin', IGPBL ),
					'container_class' => 'combo-group',
					'id'              => 'button_margin',
					'type'            => 'margin',
					'extended_ids'    => array( 'button_margin_top', 'button_margin_right', 'button_margin_bottom', 'button_margin_left' ),
						'image_margin_top'    => array( 'std' => '10' ),
						'image_margin_bottom' => array( 'std' => '10' ),
						'tooltip'             => __( 'Set margin size', IGPBL )
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
		$arr_params   = shortcode_atts( $this->config['params'], $atts );
		extract( $arr_params );
		$button_text  = ( ! $button_text ) ? '' : $button_text;
		$button_size  = ( ! $button_size || strtolower( $button_size ) == 'default' ) ? '' : $button_size;
		$button_color = ( ! $button_color || strtolower( $button_color ) == 'default' ) ? '' : $button_color;
		$button_icon  = ( ! $icon ) ? '' : "<i class='{$icon}'></i>";
		$tag          = 'a';
		$href         = '';
		$single_item  = explode( '__#__', $single_item );
		$single_item  = $single_item[0];
		$css_suffix   = ( ! $css_suffix ) ? '' : $css_suffix;
		$button_styles   = array();
		if ( $button_margin_top )
			$button_styles[] = "margin-top:{$button_margin_top}px";
		if ( $button_margin_bottom )
			$button_styles[] = "margin-bottom:{$button_margin_bottom}px";
		if ( $button_margin_right )
			$button_styles[] = "margin-right:{$button_margin_right}px";
		if ( $button_margin_left )
			$button_styles[] = "margin-left:{$button_margin_left}px";
		$styles    = ( count( $button_styles ) ) ? ' style="' . implode( ';', $button_styles ) . '"' : '';
		if ( ! empty( $link_type ) ) {
			$taxonomies = IG_Pb_Helper_Type::get_public_taxonomies();
			$post_types = IG_Pb_Helper_Type::get_post_types();
			// single post
			if ( array_key_exists( $link_type, $post_types ) ) {
				$permalink = home_url() . "/?p=$single_item";
				$href      = ( ! $single_item ) ? ' href="#"' : " href='{$permalink}'";
			}
			// taxonomy
			else if ( array_key_exists( $link_type, $taxonomies ) ) {
				$permalink = get_term_link( intval( $single_item ), $link_type );
				if ( ! is_wp_error( $permalink ) )
					$href = ( ! $single_item ) ? ' href="#"' : " href='{$permalink}'";
			}
			else {
				switch ( $link_type ) {
					case 'no_link':
						$tag = 'button';
						break;
					case 'url':
						$href = ( ! $button_type_url ) ? ' href="#"' : " href='{$button_type_url}'";
						break;
				}
			}
		}
		$target = '';
		if ( $open_in ) {
			switch ( $open_in ) {
				case 'current_browser':
					$target = '';
					break;
				case 'new_browser':
					$target = ' target="_blank"';
					break;
				case 'lightbox':
					$cls_button_fancy = 'ig-button-fancy';
					$script = IG_Pb_Helper_Functions::fancybox( ".$cls_button_fancy", array( 'type' => 'iframe', 'width' => '75%', 'height' => '75%' ) );
					break;
			}
		}
		$button_type      = ( $tag == 'button' ) ? " type='button'" : '';
		$cls_button_fancy = ( ! isset( $cls_button_fancy ) ) ? '' : $cls_button_fancy;
		$script           = ( ! isset( $script ) ) ? '' : $script;

		$html_element      = $script . "<{$tag} class='btn {$button_size} {$button_color} {$cls_button_fancy} {$css_suffix}'{$styles}{$href}{$target}{$button_type}>{$button_icon}{$button_text}</{$tag}>";
		return $this->element_wrapper( $html_element, $arr_params );
	}

}

endif;