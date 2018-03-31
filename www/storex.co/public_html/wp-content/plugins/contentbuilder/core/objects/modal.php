<?php

/**
 * @version    $Id$
 * @package    IG PageBuilder
 * @author     InnoGears Team <support@www.innogears.com>
 * @copyright  Copyright (C) 2012 www.innogears.com. All Rights Reserved.
 * @license    GNU/GPL v2 or later http://www.gnu.org/licenses/gpl-2.0.html
 *
 * Websites: http://www.www.innogears.com
 * Technical Support:  Feedback - http://www.www.innogears.com
 */
if ( ! class_exists( 'IG_Pb_Objects_Modal' ) ) {

	class IG_Pb_Objects_Modal {

		private static $instance;

		public static function get_instance() {
			if ( ! self::$instance ) {
				self::$instance = new self();
			}

			return self::$instance;
		}

		private function __construct() {
			add_filter( 'ig_register_assets', array( &$this, 'apply_assets' ) );
			$this->enqueue_admin_style();
			$this->enqueue_admin_script();

			// Localize script
			$this->ig_localize();

			do_action( 'ig_modal_init' );
		}

		/**
		 * Register custom assets to use on Modal
		 *
		 * @param array $assets
		 *
		 * @return array
		 */
		public function apply_assets( $assets ) {
			IG_Pb_Helper_Functions::load_bootstrap_3( $assets );
			$assets['ig-pb-handlesetting-js'] = array(
				'src' => IG_Pb_Helper_Functions::path( 'assets/innogears' ) . '/js/handle_setting.js',
				'ver' => '1.0.0',
			);
			if ( IG_Pb_Helper_Functions::is_preview() ) {
				$assets['ig-pb-frontend-css'] = array(
					'src' => IG_Pb_Helper_Functions::path( 'assets/innogears' ) . '/css/front_end.css',
					'ver' => '1.0.0',
				);
			}
			$assets['ig-pb-modal-css']             = array(
				'src' => IG_Pb_Helper_Functions::path( 'assets/innogears' ) . '/css/modal.css',
				'ver' => '1.0.0',
			);
			$assets['ig-pb-codemirror-css']        = array(
				'src' => IG_Pb_Helper_Functions::path( 'assets/3rd-party/' ) . '/codemirror/codemirror.css',
				'ver' => '1.0.0',
			);
			$assets['ig-pb-codemirror-js']         = array(
				'src' => IG_Pb_Helper_Functions::path( 'assets/3rd-party/' ) . '/codemirror/codemirror.js',
				'ver' => '1.0.0',
			);
			$assets['ig-pb-codemirrormode-css-js'] = array(
				'src' => IG_Pb_Helper_Functions::path( 'assets/3rd-party/' ) . '/codemirror/mode/css.js',
				'ver' => '1.0.0',
			);

			$assets = apply_filters( 'ig_pb_assets_register_modal', $assets );

			return $assets;
		}

		/**
		 * Enqueue scripts
		 */
		public function enqueue_admin_script() {
			IG_Pb_Helper_Functions::enqueue_scripts_modal();

			wp_enqueue_media();

			IG_Pb_Helper_Functions::enqueue_scripts_end();

			IG_Init_Assets::load( array( 'ig-pb-placeholder' ) );
		}

		/**
		 * Enqueue style
		 */
		public function enqueue_admin_style() {
			IG_Pb_Helper_Functions::enqueue_styles();

			if ( ! IG_Pb_Helper_Functions::is_preview() ) {
				IG_Init_Assets::load( array( 'ig-pb-modal-css' ) );
			} else {
				IG_Init_Assets::load( array( 'ig-pb-frontend-css' ) );
			}
		}

		/**
		 * Localize Script
		 */
		public function ig_localize() {
			IG_Init_Assets::localize( 'ig-pb-handlesetting-js', 'Ig_Ajax', IG_Pb_Helper_Functions::localize_js() );
		}

		/**
		 * Get related content for each Modal
		 *
		 * @param type $page
		 */
		public function preview_modal( $page = '' ) {
			add_action( 'ig_pb_modal_page_content', array( &$this, 'content' . $page ), 10 );
		}

		/**
		 * HTML content for Shortcode editing Modal
		 */
		public function content() {
			include IG_PB_TPL_PATH . '/modal.php';

			// Load required assets
			$assets = apply_filters( 'ig_pb_assets_enqueue_modal', array( 'ig-pb-handlesetting-js', ) );

			IG_Init_Assets::load( $assets );
		}

		/**
		 * HTML content for Page template Modal
		 */
		public function content_layout() {

			include IG_PB_TPL_PATH . '/layout/list.php';

			// load last assets: HandleSettings & hooked assets
			$assets = apply_filters( 'ig_pb_assets_enqueue_modal', array( 'ig-pb-handlesetting-js' ) );
			IG_Init_Assets::load( $assets );
		}

		/**
		 * HTML content for Custom css Modal
		 */
		public function content_custom_css() {
			$assets = apply_filters( 'ig_pb_assets_enqueue_modal', array( 'ig-pb-codemirror-css', 'ig-pb-codemirror-js', 'ig-pb-codemirrormode-css-js' ) );
			IG_Init_Assets::load( $assets );
			include IG_PB_TPL_PATH . '/custom-css.php';
		}

		/**
		 * Ignore settings key in array
		 *
		 * @param array $options
		 *
		 * @return array
		 */
		static function ignore_settings( $options ) {
			if ( array_key_exists( 'settings', $options ) ) {
				$options = array_slice( $options, 1 );
			}

			return $options;
		}

		/**
		 * Add setting data to a tag
		 *
		 * @param string $tag
		 * @param array  $data
		 * @param string $content
		 *
		 * @return string
		 */
		static function tab_settings( $tag, $data, $content ) {
			$tag_data = array();
			if ( ! empty( $data ) ) {
				foreach ( $data as $key => $value ) {
					if ( ! empty( $value ) ) {
						$tag_data[] = "$key = '$value'";
					}
				}
			}
			$tag_data = implode( ' ', $tag_data );

			return "<$tag $tag_data>$content</$tag>";
		}

		/**
		 * get HTML of Modal Settings Box of Shortcode
		 *
		 * @param array $options
		 *
		 * @return string
		 */
		static function get_shortcode_modal_settings( $settings, $shortcode = '', $input_params = null, $raw_shortcode = null ) {
			$i    = 0;
			$tabs = $contents = $actions = $general_actions = array();

			foreach ( (array) $settings as $tab => $options ) {
				$options = self::ignore_settings( $options );
				if ( $tab == 'action' ) {
					foreach ( $options as $option ) {
						$actions[] = IG_Pb_Helper_Shortcode::render_parameter( $option['type'], $option );
					}
				} else if ( $tab == 'generalaction' ) {
					foreach ( $options as $option ) {
						$option['id']      = isset( $option['id'] ) ? ( 'param-' . $option['id'] ) : '';
						$general_actions[] = IG_Pb_Helper_Shortcode::render_parameter( $option['type'], $option );
					}
				} else {
					$active = ( $i ++ == 0 ) ? 'active' : '';
					if ( strtolower( $tab ) != 'notab' ) {
						$data_                = isset( $settings[$tab]['settings'] ) ? $settings[$tab]['settings'] : array();
						$data_['href']        = "#$tab";
						$data_['data-toggle'] = 'tab';
						$content_             = ucfirst( $tab );
						$tabs[]               = "<li class='$active'>" . self::tab_settings( 'a', $data_, $content_ ) . '</li>';
					}

					$has_margin = 0;
					$param_html = array();
					foreach ( $options as $idx => $option ) {
						// check if this element has Margin param (1)
						if ( isset( $option['name'] ) && $option['name'] == __( 'Margin', IGPBL ) && $option['id'] != 'div_margin' )
							$has_margin = 1;
						// if (1), don't use the 'auto extended margin ( top, bottom ) option'
						if ( $has_margin && isset( $option['id'] ) && $option['id'] == 'div_margin' )
							continue;

						$type         = $option['type'];
						$option['id'] = isset( $option['id'] ) ? ( 'param-' . $option['id'] ) : "$idx";
						if ( ! is_array( $type ) ) {
							$param_html[$option['id']] = IG_Pb_Helper_Shortcode::render_parameter( $type, $option, $input_params );
						} else {
							$output_inner = '';
							foreach ( $type as $sub_options ) {
								$sub_options['id'] = isset( $sub_options['id'] ) ? ( 'param-' . $sub_options['id'] ) : '';
								/* for sub option, auto assign bound = 0 {not wrapped by <div class='controls'></div> } */
								$sub_options['bound'] = '0';
								/* for sub option, auto assign 'input-small' class */
								$sub_options['class'] = isset( $sub_options['class'] ) ? ( $sub_options['class'] ) : '';
								$type                 = $sub_options['type'];
								$output_inner .= IG_Pb_Helper_Shortcode::render_parameter( $type, $sub_options );
							}
							$option                    = IG_Pb_Helper_Html::get_extra_info( $option );
							$label                     = IG_Pb_Helper_Html::get_label( $option );
							$param_html[$option['id']] = IG_Pb_Helper_Html::final_element( $option, $output_inner, $label );
						}
					}

					if ( !empty( $param_html['param-copy_style_from'] ) ) {
						array_pop( $param_html );
						// move "auto extended margin ( top, bottom ) option" to top of output
						$style_copy = array_shift( $param_html );
						// Shift Preview frame from the array
						$preview    = array_shift( $param_html );

						if ( ! empty ( $param_html['param-div_margin'] ) ) {
							$margin = $param_html['param-div_margin'];
							$param_html = array_merge(
								array(
									$preview,
									$style_copy,
									$margin,
								),
								$param_html
							);
						}else{
							$param_html = array_merge(
								array(
									$preview,
									$style_copy
								),
								$param_html
							);
						}
					}

					$param_html  = implode( '', $param_html );
					$content_tab = "<div class='tab-pane $active ig-pb-setting-tab' id='$tab'>$param_html</div>";
					$contents[]  = $content_tab;
				}
			}

			// Auto-append `Shortcode Content` tab
			if ( $shortcode != 'ig_row' ) {
				self::shortcode_content_tab( $tabs, $contents, $raw_shortcode );
			}

			return self::setting_tab_html( $shortcode, $tabs, $contents, $general_actions, $settings, $actions );
		}

		/**
		 * Generate tab with content, use for generating Modal
		 *
		 * @return string
		 */
		static function setting_tab_html( $shortcode, $tabs, $contents, $general_actions, $settings, $actions ) {
			$output = '<input type="hidden" value="' . $shortcode . '" id="shortcode_name" name="shortcode_name" />';

			/* Tab Content - Styling */

			$output .= '<div class="jsn-tabs">';
			if ( count( $tabs ) > 0 ) {
				$output .= '<ul class="" id="ig_option_tab">';
				$output .= implode( '', $tabs );
				$output .= '</ul>';
			}
			/* Tab Content */

			$output .= implode( '', $contents );

			$output .= "<div class='jsn-buttonbar ig_action_btn'>";

			/* Tab Content - General actions */
			if ( count( $general_actions ) ) {
				$data_    = $settings['generalaction']['settings'];
				$content_ = implode( '', $general_actions );
				$output  .= self::tab_settings( 'div', $data_, $content_ );
			}

			$output .= implode( '', $actions );
			$output .= '</div>';
			$output .= '</div>';

			return $output;
		}

		/**
		 * Append shortcode content tab to all element settings modal.
		 *
		 * @param   array  &$tabs         Current tabs array.
		 * @param   array  &$contents     Currnt content array.
		 * @param   string $raw_shortcode Raw shortcode content.
		 *
		 * @return  void
		 */
		public static function shortcode_content_tab( &$tabs, &$contents, $raw_shortcode ) {
			// Auto-append `Shortcode Content` tab only if this is not a sub-modal
			if ( ! isset( $_REQUEST['submodal'] ) || ! $_REQUEST['submodal'] ) {
				// Generate tab for shortcode content
				$tabs[] = '<li><a href="#shortcode-content" data-toggle="tab">' . __( 'Shortcode', IGPBL ) . '</a></li>';

				// Generate content for shortcode content tab
				$contents[] = '<div class="tab-pane clearfix" id="shortcode-content">'
				. '<textarea id="shortcode_content" class="form-control" rows="10" disabled="disabled">' . esc_textarea( $raw_shortcode ) . '</textarea>'
				. '<div class="text-center"><button class="btn btn-success" id="copy_to_clipboard" data-textchange="' . __( 'Done!', IGPBL ) . '">' . __( 'Copy to Clipboard', IGPBL ) . '</button></div>'
					. '</div>';
			} else {
				// Generate hidden text area to hold raw shortcode
				$contents[] = '<textarea class="hidden" id="shortcode_content">' . esc_textarea( $raw_shortcode ) . '</textarea>';
			}
		}
	}
}
