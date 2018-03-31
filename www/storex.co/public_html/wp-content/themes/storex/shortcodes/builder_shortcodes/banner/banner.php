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
if ( ! class_exists( 'IG_Banner' ) ) {

	class IG_Banner extends IG_Pb_Shortcode_Parent {

		public function __construct() {
			parent::__construct();
		}

		public function element_config() {
			$this->config['shortcode'] = strtolower( __CLASS__ );
			$this->config['name'] = esc_html__('PT Banner',  'storex' );
            $this->config['edit_using_ajax'] = true;
            $this->config['exception'] = array(
			
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
						'name'    => esc_html__( 'Image File',  'storex' ),
						'id'      => 'image_file',
						'type'    => 'select_media',
						'std'     => '',
						'class'   => 'jsn-input-large-fluid',
						'tooltip' => esc_html__( 'Choose image',  'storex' )
					),
					array(
						'name'    => esc_html__( 'Alt Text',  'storex' ),
						'id'      => 'image_alt',
						'type'    => 'text_field',
						'class'   => 'jsn-input-xxlarge-fluid',
						'std'     => '',
						'tooltip' => esc_html__( 'Set alt text for image',  'storex' )
					),
                    array(
                        'name'    => esc_html__( 'Banner Type',  'storex' ),
                        'id'      => 'banner_type',
                        'type'    => 'select',
                        'std'     => 'simple',
                        'options' => array(
                            'simple' => 'Simple Image',
                            'with_html' => 'Image with HTML',
                        ),
                        'has_depend' => '1',
                    ),
                    array(
                        'name' 	  => esc_html__( 'Banner Text',  'storex' ),
                        'desc'    => esc_html__( 'Enter some content for the banner text block',  'storex' ),
                        'id'      => 'banner_text',
                        'type'    => 'editor',
                        'role'    => 'content',
                        'std'     => '',
                        'rows'    => 5,
                        'dependency' => array( 'banner_type', '=', 'with_html' ),
                    ),
                    array(
						'name'       => esc_html__( 'Banner Text Position', 'storex' ),
						'id'         => 'banner_text_position',
						'type'       => 'radio',
						'label_type' => 'image',
						'dimension'  => array( 23, 23 ),
						'std'        => 'center center',
						'options'    => array(
							'left top'      => array( 'left top' ),
							'center top'    => array( 'center top' ),
							'right top'     => array( 'right top', 'linebreak' => true ),
							'left center'   => array( 'left center' ),
							'center center' => array( 'center center' ),
							'right center'  => array( 'right center', 'linebreak' => true ),
							'left bottom'   => array( 'left bottom' ),
							'center bottom' => array( 'center bottom' ),
							'right bottom'  => array( 'right bottom' ),
						),
						'dependency' => array( 'banner_type', '=', 'with_html' ),
					),
					array(
						'name'    => esc_html__( 'URL',  'storex' ),
						'id'      => 'banner_url',
						'type'    => 'text_field',
						'class'   => 'jsn-input-xxlarge-fluid',
						'std'     => 'http://',
                        'tooltip' => esc_html__( 'Url of link when click on image',  'storex' ),
					),
				),

				'styling' => array(
					array(
						'name'    => esc_html__( 'Set Container Width for banner 50%',  'storex' ),
						'id'      => 'banner_width',
						'type' => 'radio',
						'std' => 'no',
						'options' => array( 'yes' => esc_html__( 'Yes', 'storex' ), 'no' => esc_html__( 'No', 'storex' ) ),
						'tooltip' => esc_html__( 'Set Container Width for banner 50%',  'storex' ),
					),
					array(
						'name' => esc_html__( 'Show "Read More" button', 'storex' ),
						'id' => 'banner_button',
						'type' => 'radio',
						'std' => 'no',
						'options' => array( 'yes' => esc_html__( 'Yes', 'storex' ), 'no' => esc_html__( 'No', 'storex' ) ),
						'tooltip' => esc_html__( 'Show or not linked button above banner', 'storex' ),
						'has_depend' => '1',
					),
					array(
						'name' => esc_html__( 'Color "Read More" button', 'storex' ),
						'id' => 'banner_button_color',
						'type'       => 'color_picker',
						'std'        => '#00aeef',
						'dependency' => array( 'banner_button', '=', 'yes'),
					),
					array(
						'name'    => esc_html__( 'Button Text',  'storex' ),
						'id'      => 'banner_button_text',
						'type'    => 'text_field',
						'class'   => 'jsn-input-xxlarge-fluid',
						'std'     => '',
						'tooltip' => esc_html__( 'Set banner button text',  'storex' ),
						'dependency' => array( 'banner_button', '=', 'yes'),
					),
					array(
						'name'    => esc_html__( 'Button Type',  'storex' ),
						'id'      => 'banner_button_type',
						'type'    => 'select',
                        'std'     => 'btn_type_default',
						'tooltip' => esc_html__( 'Set banner button text',  'storex' ),
						'options' => array(
							'btn_type_default' => 'Button Default',
                            'btn_type_1' => 'Button Type 1',
							'btn_type_2' => 'Button Type 2',
							'btn_type_3' => 'Button Type 3',
							),
						'dependency' => array( 'banner_button', '=', 'yes'),
					),
					array(
						'name'       => esc_html__( 'Button Position', 'storex' ),
						'id'         => 'banner_button_position',
						'type'       => 'radio',
						'label_type' => 'image',
						'dimension'  => array( 23, 23 ),
						'std'        => 'center center',
						'options'    => array(
							'left top'      => array( 'left top' ),
							'center top'    => array( 'center top' ),
							'right top'     => array( 'right top', 'linebreak' => true ),
							'left center'   => array( 'left center' ),
							'center center' => array( 'center center' ),
							'right center'  => array( 'right center', 'linebreak' => true ),
							'left bottom'   => array( 'left bottom' ),
							'center bottom' => array( 'center bottom' ),
							'right bottom'  => array( 'right bottom' ),
						),
						'dependency' => array( 'banner_button', '=', 'yes' ),
					),
					array(
                        'name'    => esc_html__( 'Banner Hover Effect',  'storex' ),
                        'id'      => 'hover_type',
                        'type'    => 'select',
                        'std'     => 'lily',
                        'options' => array(
	                        'zoom' => 'Hover Zoom',
                            'lily' => 'Hover Effect Lily',
                            'sadie' => 'Hover Effect Sadie',
							'sadie1' => 'Hover Effect Sadie1',
                            'roxy' => 'Hover Effect Roxy',
                            'bubba' => 'Hover Effect Bubba',
                            'romeo' => 'Hover Effect Romeo',
                            'oscar' => 'Hover Effect Oscar',
                            'ruby' => 'Hover Effect Ruby',
                            'milo' => 'Hover Effect Milo',
                            'dexter' => 'Hover Effect Dexter',
							'julia' => 'Hover Effect Julia',
							'julia1' => 'Hover Effect Julia 1',
                        ),
                        'tooltip' => esc_html__( 'Choose hover effect for banner',  'storex' ),
                    ),
					array(
						'name'    => esc_html__( 'Banner Background Color',  'storex' ),
						'id'      => 'banner_bg_color',
						'type'       => 'color_picker',
						'std'        => '#ffffff',
					),
				)
			);
		}

		public function element_shortcode_full( $atts = null, $content = null ) {
			$arr_params     = shortcode_atts( $this->config['params'], $atts );
			extract( $arr_params );

			$html_output = '';
			if($banner_width=='yes')
				$banner_width_storex='banner-width';
			else
				$banner_width_storex='';
			$show_banner_button = $arr_params['banner_button'];
			$alt_text = ( $image_alt ) ? " alt='{$image_alt}'" : ' alt=""';
			$container_class = 'figure banner-with-effects effect-'.$hover_type.' '.$banner_width_storex.' '.$css_suffix;
			if ( $show_banner_button == 'yes' ) { $container_class = $container_class.' with-button'; }
			$button_text = ( $banner_button_text == '' ? $banner_button_text : esc_html__('Read More', 'storex') );
			
			$simple_class = 'figcaption';
			if ($banner_type == 'simple') {
				$simple_class .= " simple-banner";
			}

			// Banner output
			$html_output = "<div class='{$container_class}'  style='background-color:{$banner_bg_color}' >";

			if ( $show_banner_button == 'no' ) {
				$html_output.= "<a href='{$banner_url}' title='{$image_alt}' rel='nofollow'>";
			}

			$html_output.= "<img src='{$image_file}'$alt_text/><div class='{$simple_class}'>";

			if ($banner_type == 'with_html') {
				$html_output.= "<div class='banner-content {$banner_text_position}'>{$content}</div>";
			}

			$html_output.= "</div>";

			if ( $show_banner_button == 'yes' ) {
				$html_output.= "<a href='{$banner_url}' style='color:{$banner_button_color}' class='{$banner_button_position} {$banner_button_type}' rel='nofollow'>{$banner_button_text}</a>";
			}
			
			if ( $show_banner_button == 'no' ) {
				$html_output.= "</a>";
			}

			$html_output.= "</div>";

			return $this->element_wrapper( $html_output, $arr_params );
		}

	}

}