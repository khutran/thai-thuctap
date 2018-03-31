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

if ( ! class_exists( 'IG_Image' ) ) :

/**
 * Create Image element
 *
 * @package  IG PageBuilder Shortcodes
 * @since    1.0.0
 */
class IG_Image extends IG_Pb_Shortcode_Element {
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
		$this->config['name'] = __( 'Image', IGPBL );
		$this->config['cat'] = __( 'Media', IGPBL );
		$this->config['icon'] = 'icon-paragraph-text';

		// Define exception for this shortcode
		$this->config['exception'] = array(
			'admin_assets' => array(
				// Link Type
				'ig-linktype.js',

				// Shortcode initialization
				'image.js',
			),

			'frontend_assets' => array(
				// Bootstrap 3
				'ig-pb-bootstrap-css',
				'ig-pb-bootstrap-js',

				// Fancy Box
				'ig-pb-jquery-fancybox-css',
				'ig-pb-jquery-fancybox-js',

				// Lazy Load
				'ig-pb-jquery-lazyload-js',

				// Shortcode initialization
				'image_frontend.js',
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
					'name'    => __( 'Image File', IGPBL ),
					'id'      => 'image_file',
					'type'    => 'select_media',
					'std'     => '',
					'class'   => 'jsn-input-large-fluid',
					'tooltip' => __( 'Choose image', IGPBL )
				),
				array(
					'name'    => __( 'Image Size', IGPBL ),
					'id'      => 'image_size',
					'type'    => 'large_image',
					'tooltip' => __( 'Set image size', IGPBL )
				),
				array(
					'name'    => __( 'Alt Text', IGPBL ),
					'id'      => 'image_alt',
					'type'    => 'text_field',
					'class'   => 'input-sm',
					'std'     => '',
					'tooltip' => __( 'Set alt text for image', IGPBL )
				),
				array(
					'name'       => __( 'On Click', IGPBL ),
					'id'         => 'link_type',
					'type'       => 'select',
					'class'      => 'input-sm',
					'std'        => IG_Pb_Helper_Type::get_first_option( IG_Pb_Helper_Type::get_image_link_types() ),
					'options'    => IG_Pb_Helper_Type::get_image_link_types(),
					'tooltip'    => __( 'Set link type of image', IGPBL ),
					'has_depend' => '1',
				),
				array(
					'name'       => __( 'Large Image Size', IGPBL ),
					'id'         => 'image_image_size',
					'type'       => 'large_image',
					'tooltip'    => __( 'Choose image size', IGPBL ),
					'dependency' => array( 'link_type', '=', 'large_image' )
				),
				array(
					'name'       => __( 'URL', IGPBL ),
					'id'         => 'image_type_url',
					'type'       => 'text_field',
					'class'      => 'input-sm',
					'std'        => 'http://',
					'dependency' => array( 'link_type', '=', 'url' ),
					'tooltip'    => __( 'Url of link when click on image', IGPBL ),
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
					'std'        => IG_Pb_Helper_Type::get_first_option( IG_Pb_Helper_Type::get_open_in_options() ),
					'options'    => IG_Pb_Helper_Type::get_open_in_options(),
					'dependency' => array( 'link_type', '!=', 'no_link' ),
					'tooltip'    => __( 'Select type of opening action when click on element', IGPBL ),
				),
			),
			'styling' => array(
				array(
					'type' => 'preview',
				),
				array(
					'name'    => __( 'Container Style', IGPBL ),
					'id'      => 'image_container_style',
					'type'    => 'select',
					'class'   => 'input-sm',
					'std'     => IG_Pb_Helper_Type::get_first_option( IG_Pb_Helper_Type::get_container_style() ),
					'options' => IG_Pb_Helper_Type::get_container_style(),
					'tooltip' => __( 'Set Container Style', IGPBL )
				),
				array(
					'name'    => __( 'Alignment', IGPBL ),
					'id'      => 'image_alignment',
					'type'    => 'select',
					'class'   => 'input-sm',
					'std'     => IG_Pb_Helper_Type::get_first_option( IG_Pb_Helper_Type::get_text_align() ),
					'options' => IG_Pb_Helper_Type::get_text_align(),
					'tooltip' => __( 'Setting position: right, left, center, inherit parent style', IGPBL )
				),
				array(
					'name'            => __( 'Margin', IGPBL ),
					'container_class' => 'combo-group',
					'id'              => 'image_margin',
					'type'            => 'margin',
					'extended_ids'    => array( 'image_margin_top', 'image_margin_right', 'image_margin_bottom', 'image_margin_left' ),
						'image_margin_top'    => array( 'std' => '10' ),
						'image_margin_bottom' => array( 'std' => '10' ),
						'tooltip'             => __( 'Set margin size', IGPBL )
				),
				array(
					'name'    => __( 'Fade in Animations', IGPBL ),
					'id'      => 'image_effect',
					'type'    => 'radio',
					'std'     => 'no',
					'options' => array( 'yes' => __( 'Yes', IGPBL ), 'no' => __( 'No', IGPBL ) ),
					'tooltip' => 'Whether to fading in or not',
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
		$arr_params     = shortcode_atts( $this->config['params'], $atts );
		extract( $arr_params );
		$html_elemments = $script = '';
		$alt_text       = ( $image_alt ) ? " alt='{$image_alt}'" : '';
		$image_styles   = array();
		if ( $image_margin_top )
			$image_styles[] = "margin-top:{$image_margin_top}px";
		if ( $image_margin_bottom )
			$image_styles[] = "margin-bottom:{$image_margin_bottom}px";
		if ( $image_margin_right )
			$image_styles[] = "margin-right:{$image_margin_right}px";
		if ( $image_margin_left )
			$image_styles[] = "margin-left:{$image_margin_left}px";
		$styles    = ( count( $image_styles ) ) ? ' style="' . implode( ';', $image_styles ) . '"' : '';
		$class_img = ( $image_container_style != 'no-styling' ) ? $image_container_style : '';
		$class_img = ( $image_effect == 'yes' ) ? $class_img . ' image-scroll-fade' : $class_img;
		$class_img = ( ! empty( $class_img ) ) ? ' class="' . $class_img . '"' : '';

		if ( $image_file ) {
			$image_id       = IG_Pb_Helper_Functions::get_image_id( $image_file );
			$attachment     = wp_prepare_attachment_for_js( $image_id );
			$image_file     = ( ! empty( $attachment['sizes'][$image_size]['url'] ) ) ? $attachment['sizes'][$image_size]['url'] : $image_file;
			$html_elemments .= "<img src='{$image_file}'{$alt_text}{$styles}{$class_img} alt=''/>";
			$script         = '';
			$target         = '';

			if ( $image_effect == 'yes' AND ! isset( $_POST['action'] ) ) {
				$data = getimagesize( $image_file );
				$width = $data[0];
				$height = $data[1];
				$html_elemments = "<img src='" . IG_Pb_Helper_Functions::path( 'assets/3rd-party' ) . '/jquery-lazyload/grey.gif' . "' data-original='{$image_file}' width='{$width}' height='{$height}' {$alt_text}{$styles} {$class_img}/>";
			}

			if ( $open_in ) {
				switch ( $open_in ) {
					case 'current_browser':
						$target = '';
						break;
					case 'new_browser':
						$target = ' target="_blank"';
						break;
					case 'lightbox':
						$cls_button_fancy = 'ig-image-fancy';
						break;
				}
			}

			$class = ( isset( $cls_button_fancy ) && ! empty( $cls_button_fancy ) ) ? " class='{$cls_button_fancy}'" : '';

			// get Single Item and check type to get right link
			$single_item = explode( '__#__', $single_item );
			$single_item = $single_item[0];
			$taxonomies  = IG_Pb_Helper_Type::get_public_taxonomies();
			$post_types  = IG_Pb_Helper_Type::get_post_types();
			// single post
			if ( array_key_exists( $link_type, $post_types ) ) {
				$permalink      = home_url() . "/?p=$single_item";
				$html_elemments = "<a href='{$permalink}'{$target}{$class}>" . $html_elemments . '</a>';
			}
			// taxonomy
			else if ( array_key_exists( $link_type, $taxonomies ) ) {
				$permalink = get_term_link( intval( $single_item ), $link_type );
				if ( ! is_wp_error( $permalink ) )
					$html_elemments = "<a href='{$permalink}'{$target}{$class}>" . $html_elemments . '</a>';
			}
			else {
				switch ( $link_type ) {
					case 'url':
						$html_elemments = "<a href='{$image_type_url}'{$target}{$class}>" . $html_elemments . '</a>';
						break;
					case 'large_image':
						$image_id       = IG_Pb_Helper_Functions::get_image_id( $image_file );
						$attachment     = wp_prepare_attachment_for_js( $image_id );
						$image_url      = ( ! empty( $attachment['sizes'][$image_image_size]['url'] ) ) ? $attachment['sizes'][$image_image_size]['url'] : $image_file;
						$html_elemments = "<a href='{$image_url}'{$target}{$class}>" . $html_elemments . '</a>';
						break;
				}
			}

			if ( strtolower( $image_alignment ) != 'inherit' ) {
				if ( strtolower( $image_alignment ) == 'left' )
					$cls_alignment = 'pull-left';
				if ( strtolower( $image_alignment ) == 'right' )
					$cls_alignment = 'pull-right';
				if ( strtolower( $image_alignment ) == 'center' )
					$cls_alignment = 'text-center';
				$html_elemments = "<div class='{$cls_alignment}'>" . $html_elemments . '</div>';
			}
		}

		return $this->element_wrapper( $html_elemments . $script, $arr_params );
	}
}

endif;
