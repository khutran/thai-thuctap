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
/**
 * Core initialization class of IG Pb Plugin.
 *
 * @package  IG_Pb_Assets_Register
 * @since	1.0.0
 */
class IG_Pb_Core {

	/**
	 * IG Pb Plugin's custom post type slug.
	 *
	 * @var  string
	 */
	private $ig_elements;

	/**
	 * Constructor.
	 *
	 * @return  void
	 */
	function __construct() {
		$this->ig_elements = array();

		global $pagenow;
		if (
				'post.php' == $pagenow || 'post-new.php' == $pagenow // Post editing page
				|| 'widgets.php' == $pagenow                         // Widget page, for IG Page Element Widget
				|| $_GET['ig-gadget'] != ''                          // IG Gadet
				|| ( defined( 'DOING_AJAX' ) && DOING_AJAX )         // Ajax page
				|| ! is_admin()                                      // Front end
		)
		{
				$this->register_element();
				$this->register_widget();
		}

		$this->custom_hook();
	}

	/**
	 * Get array of shortcode elements
	 * @return type
	 */
	function get_elements() {
		return $this->ig_elements;
	}

	/**
	 * Add shortcode element
	 * @param type $type: type of element ( element/layout )
	 * @param type $class: name of class
	 * @param type $element: instance of class
	 */
	function set_element( $type, $class, $element = null ) {
		if ( empty( $element ) )
			$this->ig_elements[$type][strtolower( $class )] = new $class();
		else
			$this->ig_elements[$type][strtolower( $class )] = $element;
	}

	/**
	 * IG PageBuilder custom hook
	 */
	function custom_hook() {
		// filter assets
		add_filter( 'ig_register_assets', array( &$this, 'apply_assets' ) );
		add_action( 'admin_head', array( &$this, 'load_assets' ), 10 );
		// translation
		add_action( 'init', array( &$this, 'translation' ) );
		// register modal page
		add_action( 'admin_init', array( &$this, 'modal_register' ) );
		add_action( 'admin_init', array( &$this, 'widget_register_assets' ) );

		// enable shortcode in content & filter content with IGPB shortcodes
		add_filter( 'the_content', array( &$this, 'pagebuilder_to_frontend' ), 9 );
		add_filter( 'the_content', 'do_shortcode' );
		remove_filter( 'the_excerpt', 'wpautop' );
		remove_filter( 'the_content', 'wpautop' );

		// enqueue js for front-end
		add_action( 'wp_enqueue_scripts', array( &$this, 'frontend_scripts' ) );

		// hook saving post
		add_action( 'edit_post', array( &$this, 'save_pagebuilder_content' ) );
		add_action( 'save_post', array( &$this, 'save_pagebuilder_content' ) );
		add_action( 'publish_post', array( &$this, 'save_pagebuilder_content' ) );
		add_action( 'edit_page_form', array( &$this, 'save_pagebuilder_content' ) );
		add_action( 'pre_post_update', array( &$this, 'save_pagebuilder_content' ) );

		// ajax action
		add_action( 'wp_ajax_save_css_custom', array( &$this, 'save_css_custom' ) );
		add_action( 'wp_ajax_delete_layout', array( &$this, 'delete_layout' ) );
		add_action( 'wp_ajax_delete_layouts_group', array( &$this, 'delete_layouts_group' ) );
		add_action( 'wp_ajax_reload_layouts_box', array( &$this, 'reload_layouts_box' ) );
		add_action( 'wp_ajax_igpb_clear_cache', array( &$this, 'igpb_clear_cache' ) );
		add_action( 'wp_ajax_save_layout', array( &$this, 'save_layout' ) );
		add_action( 'wp_ajax_upload_layout', array( &$this, 'upload_layout' ) );
		add_action( 'wp_ajax_update_whole_sc_content', array( &$this, 'update_whole_sc_content' ) );
		add_action( 'wp_ajax_shortcode_extract_param', array( &$this, 'shortcode_extract_param' ) );
		add_action( 'wp_ajax_get_json_custom', array( &$this, 'ajax_json_custom' ) );
		add_action( 'wp_ajax_get_shortcode_tpl', array( &$this, 'get_shortcode_tpl' ) );
		add_action( 'wp_ajax_get_default_shortcode_structure', array( &$this, 'get_default_shortcode_structure' ) );

		add_action( 'wp_ajax_text_to_pagebuilder', array( &$this, 'text_to_pagebuilder' ) );
		add_action( 'wp_ajax_get_html_content', array( &$this, 'get_html_content' ) );
		add_action( 'wp_ajax_get_same_elements', array( &$this, 'get_same_elements' ) );
		add_action( 'wp_ajax_merge_style_params', array( &$this, 'merge_style_params' ) );
		// add IGPB metabox
		add_action( 'add_meta_boxes', array( &$this, 'custom_meta_boxes' ) );

		// print html template of shortcodes
		add_action( 'admin_footer', array( &$this, 'element_tpl' ) );
		add_filter( 'wp_handle_upload_prefilter', array( &$this, 'media_file_name' ), 100 );

		// add IGPB button to Wordpress TinyMCE
		add_filter( 'mce_external_plugins', array( &$this, 'filter_mce_plugin' ) );
		//if ( $this->check_support() ) {
			add_action( 'media_buttons_context',  array( &$this, 'add_page_element_button' ) );
		//}

		// Remove Gravatar from Ig Modal Pages
		if ( is_admin() ) {
			add_filter( 'bp_core_fetch_avatar', array( &$this, 'remove_gravatar' ), 1, 9 );
			add_filter( 'get_avatar', array( &$this, 'get_gravatar' ), 1, 5 );
		}

		// add body class in backend
		add_filter( 'admin_body_class', array( &$this, 'admin_bodyclass' ) );

		// get image size
		add_filter( 'ig_pb_get_json_image_size', array( &$this, 'get_image_size' ) );

		// Editor hook before & after
		add_action( 'edit_form_after_title', array( &$this, 'hook_after_title' ) );
		add_action( 'edit_form_after_editor', array( &$this, 'hook_after_editor' ) );

		// Frontend hook
		add_filter( 'post_class', array( &$this, 'wp_bodyclass' ) );
		add_action( 'wp_head', array( &$this, 'post_view' ) );
		add_action( 'wp_footer', array( &$this, 'enqueue_compressed_assets' ) );

		// Custom css
		add_action( 'wp_head', array( &$this, 'enqueue_custom_css' ), 25 );
		add_action( 'wp_print_styles', array( $this, 'print_frontend_styles' ), 25 );

		do_action( 'ig_pb_custom_hook' );
	}

	/**
     * Get translation file
     */
	function translation() {
		load_plugin_textdomain( IGPBL, false, dirname( plugin_basename( IG_PB_FILE ) ) . '/languages/' );
	}

	/**
	 * Register custom asset files
     *
	 * @param type $assets
	 * @return string
	 */
	function apply_assets( $assets ) {
		$assets['ig-pb-frontend-css'] = array(
			'src' => IG_Pb_Helper_Functions::path( 'assets/innogears' ) . '/css/front_end.css',
			'ver' => '1.0.0',
		);
		IG_Pb_Helper_Functions::load_bootstrap_3( $assets );
		if ( ! is_admin() || IG_Pb_Helper_Functions::is_preview() ) {
			$options = array( 'ig_pb_settings_boostrap_js', 'ig_pb_settings_boostrap_css' );
			// get saved options value
			foreach ( $options as $key ) {
				$$key = get_option( $key, 'enable' );
			}
			if ( $ig_pb_settings_boostrap_css != 'enable' ) {
				$assets['ig-pb-bootstrap-css'] = array(
					'src' => '',
					'ver' => '3.0.2',
				);
			}
			if ( $ig_pb_settings_boostrap_js != 'enable' ) {
				$assets['ig-pb-bootstrap-js'] = array(
					'src' => '',
					'ver' => '3.0.2',
				);
			}
		}
		$assets['ig-pb-joomlashine-frontend-css'] = array(
			'src' => IG_Pb_Helper_Functions::path( 'assets/innogears' ) . '/css/jsn-gui-frontend.css',
			'deps' => array( 'ig-pb-bootstrap-css' ),
		);
		$assets['ig-pb-frontend-responsive-css'] = array(
			'src' => IG_Pb_Helper_Functions::path( 'assets/innogears' ) . '/css/front_end_responsive.css',
			'ver' => '1.0.0',
		);
		$assets['ig-pb-addpanel-js'] = array(
			'src' => IG_Pb_Helper_Functions::path( 'assets/innogears' ) . '/js/add_page_builder.js',
			'ver' => '1.0.0',
		);
		$assets['ig-pb-layout-js'] = array(
			'src' => IG_Pb_Helper_Functions::path( 'assets/innogears' ) . '/js/layout.js',
			'ver' => '1.0.0',
		);
		$assets['ig-pb-widget-js'] = array(
			'src' => IG_Pb_Helper_Functions::path( 'assets/innogears' ) . '/js/widget.js',
			'ver' => '1.0.0',
		);
		$assets['ig-pb-placeholder'] = array(
			'src' => IG_Pb_Helper_Functions::path( 'assets/innogears' ) . '/js/placeholder.js',
			'ver' => '1.0.0',
		);
		$assets['ig-pb-settings-js'] = array(
			'src' => IG_Pb_Helper_Functions::path( 'assets/innogears' ) . '/js/product/settings.js',
			'ver' => '1.0.0',
		);
		$assets['ig-pb-upgrade-js'] = array(
			'src' => IG_Pb_Helper_Functions::path( 'assets/innogears' ) . '/js/product/upgrade.js',
			'ver' => '1.0.0',
		);
		return $assets;
	}

	/**
	 * Enqueue scripts & style for Front end
	 */
	function frontend_scripts() {
		/* Load stylesheets */
		$ig_pb_frontend_css = array( 'ig-pb-font-icomoon-css', 'ig-pb-joomlashine-frontend-css', 'ig-pb-frontend-css', 'ig-pb-frontend-responsive-css' );

		IG_Init_Assets::load( $ig_pb_frontend_css );

		// Load scripts
		$ig_pb_frontend_js = array( 'ig-pb-bootstrap-js' );

		IG_Init_Assets::load( apply_filters( 'ig_pb_assets_enqueue_frontend',  $ig_pb_frontend_js ) );
	}

	/**
	 * Add IG PageBuilder Metaboxes
	 */
	function custom_meta_boxes() {
		//if ( $this->check_support() ) {
			add_meta_box(
				'ig_page_builder',
				__( 'Page Builder', IGPBL ),
				array( &$this, 'page_builder_html' )
			);
		//}
	}

	/**
	 * Content file for IG PageBuilder Metabox
	 */
	function page_builder_html() {
		// Get available data converters
		$converters = IG_Pb_Converter::get_converters();

		if ( @count( $converters ) ) {
			// Load script initialization for data conversion
			IG_Init_Assets::load( 'ig-pb-convert-data-js' );
		}

		// Load script initialization for undo / redo action
		IG_Init_Assets::load( 'ig-pb-activity-js' );

		include IG_PB_TPL_PATH . '/page-builder.php';
	}

	/**
	 * Register all Parent & No-child element, for Add Element popover
	 */
	function register_element() {
		global $Ig_Pb_Shortcodes;
		$current_shortcode = IG_Pb_Helper_Functions::current_shortcode();
		$Ig_Pb_Shortcodes  = ! empty ( $Ig_Pb_Shortcodes ) ? $Ig_Pb_Shortcodes : IG_Pb_Helper_Shortcode::ig_pb_shortcode_tags();
		foreach ( $Ig_Pb_Shortcodes as $name => $sc_info ) {
			$arr  = explode( '_', $name );
			$type = $sc_info['type'];
			if ( ! $current_shortcode || ! is_admin() || in_array( $current_shortcode, $arr ) || ( ! $current_shortcode && $type == 'layout' ) ) {
				$class   = IG_Pb_Helper_Shortcode::get_shortcode_class( $name );
				$element = new $class();
				$this->set_element( $type, $class, $element );
//				$this->register_sub_el( $class, 1 );
			}
		}
	}

	/**
     * Register IGPB Widget
     */
	function register_widget(){
		register_widget( 'IG_Pb_Objects_Widget' );
	}

	/**
	 * Regiter sub element
     *
	 * @param string $class
	 * @param int $level
	 */
	private function register_sub_el( $class, $level = 1 ) {
		$item  = str_repeat( 'Item_', intval( $level ) - 1 );
		$class = str_replace( "IG_$item", "IG_Item_$item", $class );
		if ( class_exists( $class ) ) {
			// 1st level sub item
			$element = new $class();
			$this->set_element( 'element', $class, $element );
			// 2rd level sub item
			$this->register_sub_el( $class, 2 );
		}
	}

	/**
	 * print HTML template of shortcodes
	 */
	function element_tpl() {
		ob_start();

		// Print template for IG PageBuilder elements
		$elements = $this->get_elements();

		foreach ( $elements as $type_list ) {
			foreach ( $type_list as $element ) {
				// Get element type
				$element_type = $element->element_in_pgbldr( null, null, null, null, false);
				// Print template tag
				foreach ( $element_type as $element_structure ) {
					echo balanceTags( "<script type='text/html' id='tmpl-{$element->config['shortcode']}'>\n{$element_structure}\n</script>\n" );
				}
			}
		}

		// Print widget template
		global $Ig_Pb_Widgets;

		if ( class_exists( 'IG_Widget' ) ) {
			foreach ( $Ig_Pb_Widgets as $shortcode => $shortcode_obj ) {
				// Instantiate Widget element
				$element = new IG_Widget();

				// Prepare necessary variables
				$modal_title = $shortcode_obj['identity_name'];
				$content     = $element->config['exception']['data-modal-title'] = $modal_title;

				$element->config['shortcode']           = $shortcode;
				$element->config['shortcode_structure'] = IG_Pb_Utils_Placeholder::add_placeholder( "[ig_widget widget_id=\"$shortcode\"]%s[/ig_widget]", 'widget_title' );
				$element->config['el_type']             = $type;

				// Get element type
				$element_type = $element->element_in_pgbldr( null, null, null, null, false);

				// Print template tag
				foreach ( $element_type as $element_structure ) {
					echo balanceTags( "<script type='text/html' id='tmpl-{$shortcode}'>\n{$element_structure}\n</script>\n" );
				}
			}
		}

		// Allow printing extra footer
		do_action( 'ig_pb_footer' );

		ob_end_flush();
	}

	/**
	 * Show Modal page
	 */
	function modal_register() {
		if ( IG_Pb_Helper_Functions::is_modal() ) {
			$cls_modal = IG_Pb_Objects_Modal::get_instance();
			if ( ! empty( $_GET['ig_modal_type'] ) )
				$cls_modal->preview_modal();
			if ( ! empty( $_GET['ig_layout'] ) )
				$cls_modal->preview_modal( '_layout' );
			if ( ! empty( $_GET['ig_custom_css'] ) )
				$cls_modal->preview_modal( '_custom_css' );
		}
	}

	/**
	 * Do action on modal page hook
	 */
	function modal_page_content() {
		do_action( 'ig_pb_modal_page_content' );
	}

	/**
	 * Save IG PageBuilder shortcode content of a post/page
     *
	 * @param int $post_id
	 * @return type
	 */
	function save_pagebuilder_content( $post_id ) {
		if ( ! current_user_can( 'edit_page', $post_id ) ) {
			return;
		}

		if ( ! isset($_POST[IGNONCE . '_builder'] ) || ! wp_verify_nonce( $_POST[IGNONCE . '_builder'], 'ig_builder' ) ) {
			return;
		}

		$ig_deactivate_pb = intval( esc_sql( $_POST['ig_deactivate_pb'] ) );

		if ( $ig_deactivate_pb ) {
			IG_Pb_Utils_Common::delete_meta_key( array( '_ig_page_builder_content', '_ig_html_content', '_ig_page_active_tab', '_ig_post_view_count' ), $post_id );
		} else {
			$ig_active_tab = intval( esc_sql( $_POST['ig_active_tab'] ) );
			$post_content  = '';

			// IG PageBuilder is activate
			if ( $ig_active_tab ) {
				$data = array();

				if ( isset( $_POST['shortcode_content'] ) && is_array( $_POST['shortcode_content'] ) ) {
					foreach ( $_POST['shortcode_content'] as $shortcode ) {
						$data[] = trim( stripslashes( $shortcode ) );
					}
				} else {
					$data[] = '';
				}

				$post_content = IG_Pb_Utils_Placeholder::remove_placeholder( implode( '', $data ), 'wrapper_append', '' );

				// update post meta
				update_post_meta( $post_id, '_ig_page_builder_content', $post_content );
				update_post_meta( $post_id, '_ig_html_content', IG_Pb_Helper_Shortcode::doshortcode_content( $post_content ) );
			}
			else {
				$content = stripslashes( $_POST['content'] );
				/// remove this line? $content = apply_filters( 'the_content', $content );
				$post_content = $content;
			}

			// update current active tab
			update_post_meta( $post_id, '_ig_page_active_tab', $ig_active_tab );
		}

		// update whether or not deactive pagebuilder
		update_post_meta( $post_id, '_ig_deactivate_pb', $ig_deactivate_pb );
	}

	/**
	 * Render shortcode preview in a blank page
     *
	 * @return Ambigous <string, mixed>|WP_Error
	 */
	function shortcode_iframe_preview() {

		if ( isset( $_GET['ig_shortcode_preview'] ) ) {
			if ( ! isset($_GET['ig_shortcode_name'] ) || ! isset( $_POST['params'] ) )
				return __( 'empty shortcode name / parameters', IGPBL );

			if ( ! isset($_GET[IGNONCE] ) || ! wp_verify_nonce( $_GET[IGNONCE], IGNONCE ) )
				return;

			$shortcode = esc_sql( $_GET['ig_shortcode_name'] );
			$params    = urldecode( $_POST['params'] );
			$pattern   = '/^\[ig_widget/i';
			if ( ! preg_match( $pattern, trim( $params ) ) ) {
				// get shortcode class
				$class = IG_Pb_Helper_Shortcode::get_shortcode_class( $shortcode );

				// get option settings of shortcode
				$elements = $this->get_elements();
				$elements = $this->get_elements();
				$element  = isset( $elements['element'][strtolower( $class )] ) ? $elements['element'][strtolower( $class )] : null;
				if ( ! is_object( $element ) )
					$element = new $class();

				if ( $params ) {
					$extract_params = IG_Pb_Helper_Shortcode::extract_params( $params, $shortcode );
				} else {
					$extract_params = $element->config;
				}

				$element->shortcode_data();

				$_shortcode_content = $extract_params['_shortcode_content'];
				$content = $element->element_shortcode( $extract_params, $_shortcode_content );
			} else {
				$class = 'IG_Widget';
				$content = IG_Pb_Helper_Shortcode::widget_content( array( $params ) );
			}
			global $Ig_Pb_Preview_Class;
			$Ig_Pb_Preview_Class = $class;

			$html  = '<div id="shortcode_inner_wrapper" class="jsn-bootstrap3">';
			$html .= $content;
			$html .= '</div>';
			echo balanceTags( $html );
		}
	}

	/**
	 * Update Shortcode content by merge its content & sub-shortcode content
	 */
	function update_whole_sc_content() {
		if ( ! isset($_POST[IGNONCE] ) || ! wp_verify_nonce( $_POST[IGNONCE], IGNONCE ) )
			return;

		$shortcode_content     = $_POST['shortcode_content'];
		$sub_shortcode_content = $_POST['sub_shortcode_content'];
		echo balanceTags( IG_Pb_Helper_Shortcode::merge_shortcode_content( $shortcode_content, $sub_shortcode_content ) );

		exit;
	}

	/**
	 * extract a param from shortcode data
	 */
	function shortcode_extract_param() {
		if ( ! isset($_POST[IGNONCE] ) || ! wp_verify_nonce( $_POST[IGNONCE], IGNONCE ) )
			return;

		$data		  = $_POST['data'];
		$extract_param = $_POST['param'];
		$extract       = array();
		$shortcodes    = IG_Pb_Helper_Shortcode::extract_sub_shortcode( $data );
		foreach ( $shortcodes as $shortcode ) {
			$shortcode    = stripslashes( $shortcode );
			$parse_params = shortcode_parse_atts( $shortcode );
			$extract[]    = isset( $parse_params[$extract_param] ) ? trim( $parse_params[$extract_param] ) : '';
		}
		$extract = array_filter( $extract );
		$extract = array_unique( $extract );

		echo balanceTags( implode( ',', $extract ) );
		exit;
	}

	function ajax_json_custom() {
		if ( ! isset($_POST[IGNONCE] ) || ! wp_verify_nonce( $_POST[IGNONCE], IGNONCE ) )
			return;

		if ( ! $_POST['custom_type'] )
			return 'false';

		$response = apply_filters( 'ig_pb_get_json_' . $_POST['custom_type'], $_POST );
		echo balanceTags( $response );

		exit;
	}

	/**
	 * Get shortcode structure with default attributes
	 * eg: [ig_text title="The text"]Lorem ipsum[/ig_text]
	 * Enter description here ...
	 */
	function get_default_shortcode_structure() {
		if ( ! isset($_POST[IGNONCE] ) || ! wp_verify_nonce( $_POST[IGNONCE], IGNONCE ) )
			return;
		if ( ! $_POST['shortcode'] )
			return;
		$shortcode = $_POST['shortcode'];
		$class     = IG_Pb_Helper_Shortcode::get_shortcode_class( $shortcode );
		if ( class_exists( $class ) ) {
			$element   = new $class();
			$element->init_element();
			echo $element->config['shortcode_structure'];
		}

		exit;
	}

	/**
	 * Update PageBuilder when switch Classic Editor to IG PageBuilder
     *
	 * @return string
	 */
	function text_to_pagebuilder() {
		if ( ! isset($_POST[IGNONCE] ) || ! wp_verify_nonce( $_POST[IGNONCE], IGNONCE ) )
			return;

		if ( ! isset( $_POST['content'] ) )
			return;
		// $content = urldecode( $_POST['content'] );
		$content = ( $_POST['content'] );
		$content = stripslashes( $content );

		$empty_str = IG_Pb_Helper_Shortcode::check_empty_( $content );
		if ( strlen( trim( $content ) ) && strlen( trim( $empty_str ) ) ) {
			$builder = new IG_Pb_Helper_Shortcode();

			// remove wrap p tag
			$content = preg_replace( '/^<p>(.*)<\/p>$/', '$1', $content );
			$content = balanceTags( $content );

			echo balanceTags( $builder->do_shortcode_admin( $content, false, true ) );
		} else {
			echo '';
		}

		exit;
	}

	/**
	 * Show IG PageBuilder content for Frontend post
	 *
	 * @param string $content
	 * @return string
	 */
	function pagebuilder_to_frontend( $content ) {
		global $post;

		// Get what tab (Classic - Pagebuilder) is active when Save content of this post
		$ig_page_active_tab = get_post_meta( $post->ID, '_ig_page_active_tab', true );

		$ig_deactivate_pb = get_post_meta( $post->ID, '_ig_deactivate_pb', true );

		// if Pagebuilder is active when save and pagebuilder is not deactivate on this post
		if ( $ig_page_active_tab && empty( $ig_deactivate_pb ) ) {
			$ig_pagebuilder_content = get_post_meta( $post->ID, '_ig_page_builder_content', true );
			if ( ! empty( $ig_pagebuilder_content ) ) {
				// remove placeholder text which was inserted to &lt; and &gt;
				$ig_pagebuilder_content = IG_Pb_Utils_Placeholder::remove_placeholder( $ig_pagebuilder_content, 'wrapper_append', '' );

				$ig_pagebuilder_content = preg_replace_callback(
						'/\[ig_widget\s+([A-Za-z0-9_-]+=\"[^"\']*\"\s*)*\s*\](.*)\[\/ig_widget\]/Us', array( 'IG_Pb_Helper_Shortcode', 'widget_content' ), $ig_pagebuilder_content
						);

				$content = $ig_pagebuilder_content;
			}
		}

		return $content;
	}

	/**
	 * Get output html of pagebuilder content
	 */
	function get_html_content() {
		if ( ! isset($_POST[IGNONCE] ) || ! wp_verify_nonce( $_POST[IGNONCE], IGNONCE ) )
			return;

		$content = $_POST['content'];
		$content = stripslashes( $content );
		$content = IG_Pb_Helper_Shortcode::doshortcode_content( $content );

		if ( ! empty( $content ) ) {
			echo "<div class='jsn-bootstrap3'>" . $content . '</div>';
		}
		exit;
	}

	/**
     * Get media file name
     *
     * @param array $file
     * @return array
     */
	function media_file_name( $file ) {
		$file_name = iconv( 'utf-8', 'ascii//TRANSLIG//IGNORE', $file['name'] );
		if ( $file_name ) {
			$file['name'] = $file_name;
		}
		return $file;
	}

	/**
     * Check condition to load IG PageBuilder content & assets.
     *
     * @return  boolean
     */
	function check_support() {
		global $pagenow, $post;

		if ( 'post.php' == $pagenow || 'post-new.php' == $pagenow || 'widgets.php' == $pagenow ) {
			if ( 'widgets.php' != $pagenow && ! empty( $post->ID ) ) {
				// Check if IG PageBuilder is enabled for this post type
				$settings  = IG_Pb_Product_Plugin::ig_pb_settings_options();
				$post_type = get_post_type( $post->ID );

				if ( is_array( $settings['ig_pb_settings_enable_for'] ) ) {
					if ( isset( $settings['ig_pb_settings_enable_for'][ $post_type ] ) ) {
						return ( 'enable' == $settings['ig_pb_settings_enable_for'][ $post_type ] );
					} else {
						return post_type_supports( $post_type, 'editor' );
					}
				} elseif ( 'enable' == $settings['ig_pb_settings_enable_for'] ) {
					return post_type_supports( $post_type, 'editor' );
				}
			}

			return true;
		}

		return false;
	}

	/**
	 * Load necessary assets.
	 *
	 * @return  void
	 */
	function load_assets() {
		//if ( $this->check_support() ) {
			// Load styles
			IG_Pb_Helper_Functions::enqueue_styles();

			// Load scripts
			IG_Pb_Helper_Functions::enqueue_scripts();

			$scripts = array( 'ig-pb-jquery-select2-js', 'ig-pb-addpanel-js', 'ig-pb-jquery-resize-js', 'ig-pb-joomlashine-modalresize-js', 'ig-pb-layout-js', 'ig-pb-placeholder' );
			IG_Init_Assets::load( apply_filters( 'ig_pb_assets_enqueue_admin', $scripts ) );

			IG_Pb_Helper_Functions::enqueue_scripts_end();
		//}
	}

	/**
	 * Register pagebuilder widget assets
	 *
	 * @return void
	 */
	function widget_register_assets() {
		global $pagenow;

		if ( $pagenow == 'widgets.php' ) {
			// enqueue admin script
			if ( function_exists( 'wp_enqueue_media' ) ) {
				wp_enqueue_media();
			} else {
				wp_enqueue_style( 'thickbox' );
				wp_enqueue_script( 'media-upload' );
				wp_enqueue_script( 'thickbox' );
			}
			$this->load_assets();
			IG_Init_Assets::load( 'ig-pb-handlesetting-js' );
			IG_Init_Assets::load( 'ig-pb-jquery-fancybox-js' );
			IG_Init_Assets::load( 'ig-pb-widget-js' );
		}
	}

	/**
	 * Add Inno Button to Classic Editor
	 *
	 * @param array $context
	 * @return array
	 */
	function add_page_element_button( $context ) {
		$icon_url = IG_Pb_Helper_Functions::path( 'assets/innogears' ) . '/images/ig-pgbldr-icon-black.png';
		$context .= '<a title="' . __( 'Add Page Element', IGPBL ) . '" class="button" id="ig_pb_button" href="#"><i class="mce-ico mce-i-none" style="background-image: url(\'' . $icon_url . '\')"></i>' . __( 'Add Page Element', IGPBL ) . '</a>';

		return $context;
	}

	/**
	 * Add js file to handling event
	 *
	 * @param array $plugins
	 * @return string
	 */
	function filter_mce_plugin( $plugins ) {
		$plugins['ig_pb'] = IG_Pb_Helper_Functions::path( 'assets/innogears' ) . '/js/tinymce.js';
		return $plugins;
	}

	/**
     * Gravatar : use default avatar, don't request from gravatar server
     *
     * @param type $image
     * @param type $params
     * @param type $item_id
     * @param type $avatar_dir
     * @param type $css_id
     * @param type $html_width
     * @param type $html_height
     * @param type $avatar_folder_url
     * @param type $avatar_folder_dir
     * @return type
     */
	function remove_gravatar( $image, $params, $item_id, $avatar_dir, $css_id, $html_width, $html_height, $avatar_folder_url, $avatar_folder_dir ) {

		$default = IG_Pb_Helper_Functions::path( 'assets/innogears' ) . '/images/default_avatar.png';

		if ( $image && strpos( $image, 'gravatar.com' ) ) {

			return '<img src="' . $default . '" alt="avatar" class="avatar" ' . $html_width . $html_height . ' />';
		} else {
			return $image;
		}
	}

	/**
     * Gravatar : use default avatar
     *
     * @param type $avatar
     * @param type $id_or_email
     * @param type $size
     * @param string $default
     * @return type
     */
	function get_gravatar( $avatar, $id_or_email, $size, $default ) {
		$default = IG_Pb_Helper_Functions::path( 'assets/innogears' ) . '/images/default_avatar.png';
		return '<img src="' . $default . '" alt="avatar" class="avatar" width="60" height="60" />';
	}

	/**
     * Add admin body class
     *
     * @param string $classes
     * @return string
     */
	function admin_bodyclass( $classes ) {
		$classes .= ' jsn-master';
		if ( isset($_GET['ig_load_modal'] ) AND isset( $_GET['ig_modal_type']) ) {
			$classes .= ' contentpane';
		}
		return $classes;
	}

	/**
     * Get image size
     *
     * @param array $post_request
     * @return string
     */
	function get_image_size( $post_request ) {
		$response  = '';
		$image_url = $post_request['image_url'];

		if ( $image_url ) {
			$image_id   = IG_Pb_Helper_Functions::get_image_id( $image_url );
			$attachment = wp_prepare_attachment_for_js( $image_id );
			if ( $attachment['sizes'] ) {
				$sizes		       = $attachment['sizes'];
				$attachment['sizes'] = null;
				foreach ( $sizes as $key => $item ) {
					$item['total_size']	= $item['height'] + $item['width'];
					$attachment['sizes'][ucfirst( $key )] = $item;
				}
			}
			$response = json_encode( $attachment );
		}

		return $response;
	}

	/**
     * Filter frontend body class
     *
     * @param array $classes
     * @return array
     */
	function wp_bodyclass( $classes ) {
		$classes[] = 'jsn-master';
		return $classes;
	}

	/**
     * Update post view in frontend
     *
     * @global type $post
     * @return type
     */
	function post_view() {
		global $post;
		if ( ! isset($post ) || ! is_object( $post ) )
			return;
		if ( is_single( $post->ID ) ) {
			IG_Pb_Helper_Functions::set_postview( $post->ID );
		}
	}

	/**
     * Add custom HTML code after title in Post editing page
     *
     * @global type $post
     */
	function hook_after_title() {
		global $post;
		if ( $this->check_support() ) {
			$ig_pagebuilder_content = get_post_meta( $post->ID, '_ig_page_builder_content', true );
	
			// Get active tab
			$ig_page_active_tab = get_post_meta( $post->ID, '_ig_page_active_tab', true );
			$tab_active         = isset( $ig_page_active_tab ) ? intval( $ig_page_active_tab ) : ( ! empty( $ig_pagebuilder_content ) ? 1 : 0 );
	
			// Deactivate pagebuilder
			$ig_deactivate_pb = get_post_meta( $post->ID, '_ig_deactivate_pb', true );
			$ig_deactivate_pb = isset( $ig_deactivate_pb ) ? intval( $ig_deactivate_pb ) : 0;
	
			$wrapper_style = $tab_active ? 'style="display:none"' : '';
			
			echo '
                <input id="ig_active_tab" name="ig_active_tab" value="' . $tab_active . '" type="hidden">
                <input id="ig_deactivate_pb" name="ig_deactivate_pb" value="' . $ig_deactivate_pb . '" type="hidden">
                <div class="jsn-bootstrap3 ig-editor-wrapper" ' . $wrapper_style . '>
                    <ul class="nav nav-tabs" id="ig_editor_tabs">
                        <li class="active"><a href="#ig_editor_tab1">' . __( 'Classic Editor', IGPBL ) . '</a></li>
                        <li><a href="#ig_editor_tab2">' . __( 'Page Builder', IGPBL ) . '</a></li>
                    </ul>
                    <div class="tab-content ig-editor-tab-content">
                        <div class="tab-pane active" id="ig_editor_tab1">';
		}
	}

	/**
     * Add custom HTML code after text editor in Post editing page
     *
     * @global type $post
     */
	function hook_after_editor() {
		if ( $this->check_support() ) {
			echo '</div><div class="tab-pane" id="ig_editor_tab2"><div id="ig_before_pagebuilder"></div></div></div></div>';
		} else {
			echo '<div class="tab-pane" id="ig_editor_tab2" style="display:none"><div id="ig_before_pagebuilder"></div></div>';
		}
	}

	/**
     * Compress asset files
     */
	function enqueue_compressed_assets() {
		if ( ! empty ( $_SESSION['ig-pb-assets-frontend'] ) ) {
			global $post;
			if ( empty ( $post ) )
				exit;
			$ig_pb_settings_cache = get_option( 'ig_pb_settings_cache', 'enable' );
			if ( $ig_pb_settings_cache != 'enable' ) {
				exit;
			}
			$contents_of_type = array();
			$this_session     = $_SESSION['ig-pb-assets-frontend'][$post->ID];
			// Get content of assets file from shortcode directories
			foreach ( $this_session as $type => $list ) {
				$contents_of_type[$type] = array();
				foreach ( $list as $path => $modified_time ) {
					$fp = @fopen( $path, 'r' );
					if ( $fp ) {
						$contents_of_type[$type][$path] = fread( $fp, filesize( $path ) );
						fclose( $fp );
					}
				}
			}
			// Write content of css, js to 2 seperate files
			$cache_dir = IG_Pb_Helper_Functions::get_wp_upload_folder( '/igcache/pagebuilder' );
			foreach ( $contents_of_type as $type => $list ) {
				$handle_info   = $this_session[$type];
				$hash_name     = md5( implode( ',', array_keys( $list ) ) );
				$file_name     = "$hash_name.$type";
				$file_to_write = "$cache_dir/$file_name";

				// check stored data
				$store = IG_Pb_Helper_Functions::compression_data_store( $handle_info, $file_name );

				if ( $store[0] == 'exist' ) {
					$file_name     = $store[1];
					$file_to_write = "$cache_dir/$file_name";
				} else {
					$fp = fopen( $file_to_write, 'w' );
					$handle_contents = implode( "\n/*------------------------------------------------------------*/\n", $list );
					fwrite( $fp, $handle_contents );
					fclose( $fp );
				}

				// Enqueue script/style to footer of page
				if ( file_exists( $file_to_write ) ) {
					$function = ( $type == 'css' ) ? 'wp_enqueue_style' : 'wp_enqueue_script';
					$function( $file_name, IG_Pb_Helper_Functions::get_wp_upload_url( '/igcache/pagebuilder' ) . "/$file_name" );
				}
			}
		}
	}

	/**
	 * Clear cache files
     *
	 * @return type
	 */
	function igpb_clear_cache() {
		if ( ! isset($_POST[IGNONCE] ) || ! wp_verify_nonce( $_POST[IGNONCE], IGNONCE ) )
			return;

		$delete = IG_Pb_Utils_Common::remove_cache_folder();

		echo balanceTags( $delete ? __( '<i class="icon-checkmark"></i>', IGPBL ) : __( "Fail. Can't delete cache folder", IGPBL ) );

		exit;
	}

	/**
	 * Save premade layout to file
     *
	 * @return type
	 */
	function save_layout() {
		if ( ! isset($_POST[IGNONCE] ) || ! wp_verify_nonce( $_POST[IGNONCE], IGNONCE ) )
			return;

		$layout_name    = $_POST['layout_name'];
		$layout_content = stripslashes( $_POST['layout_content'] );

		$error = IG_Pb_Helper_Layout::save_premade_layouts( $layout_name, $layout_content );

		echo intval( $error ) ? 'error' : 'success';

		exit;
	}

	/**
	 * Upload premade layout to file
     *
	 * @return type
	 */
	function upload_layout() {
		if ( ! isset($_POST[IGNONCE] ) || ! wp_verify_nonce( $_POST[IGNONCE], IGNONCE ) )
			return;

		$status = 0;
		if ( ! empty ( $_FILES ) ) {
			$fileinfo = $_FILES['file'];
			$file     = $fileinfo['tmp_name'];
			$tmp_file = 'tmp-layout-' . time();
			$dest     = IG_Pb_Helper_Functions::get_wp_upload_folder( '/ig-pb-layout/' . $tmp_file );
			if ( $fileinfo['type'] == 'application/octet-stream' ) {
				WP_Filesystem();
				$unzipfile = unzip_file( $file, $dest );
				if ( $unzipfile ) {
					// explore extracted folder to get provider info
					$status = IG_Pb_Helper_Layout::import( $dest );
				}
				// remove zip file
				unlink( $file );
			}
			IG_Pb_Utils_Common::recursive_delete( $dest );
		}
		echo intval( $status );

		exit;
	}

	/**
	 * Get list of Page template
     *
	 * @return type
	 */
	function reload_layouts_box() {
		if ( ! isset($_POST[IGNONCE] ) || ! wp_verify_nonce( $_POST[IGNONCE], IGNONCE ) )
			return;

		include IG_PB_TPL_PATH . '/layout/list.php';

		exit;
	}

	/**
	 * Delete group layout
     *
	 * @return html
	 */
	function delete_layouts_group() {
		if ( ! isset( $_POST[IGNONCE] ) || ! wp_verify_nonce( $_POST[IGNONCE], IGNONCE ) ) {
			return;
		}

		$group  = sanitize_key( $_POST['group'] );
		$delete = IG_Pb_Helper_Layout::remove_group( $group );

		include IG_PB_TPL_PATH . '/layout/list.php';

		exit;
	}

	/**
	 * Delete layout
	 *
	 * @return int
	 */
	function delete_layout() {
		if ( ! isset( $_POST[IGNONCE] ) || ! wp_verify_nonce( $_POST[IGNONCE], IGNONCE ) ) {
			return;
		}

		$group  = sanitize_key( $_POST['group'] );
		$layout = urlencode( $_POST['layout'] );
		$delete = IG_Pb_Helper_Layout::remove_layout( $group, $layout );

		echo esc_html( $delete ? 1 : 0 );

		exit;
	}

	/**
	 * Save custom CSS information: files, code
     *
	 * @return void
	 */
	function save_css_custom() {
		if ( ! isset( $_POST[IGNONCE] ) || ! wp_verify_nonce( $_POST[IGNONCE], IGNONCE ) ) {
			return;
		}

		$post_id = esc_sql( $_POST['post_id'] );
		// save custom css code & files
		IG_Pb_Helper_Functions::custom_css( $post_id, 'css_files', 'put', esc_sql( $_POST['css_files'] ) );
		IG_Pb_Helper_Functions::custom_css( $post_id, 'css_custom', 'put', esc_textarea( $_POST['custom_css'] ) );

		exit;
	}

	/**
	 * Get same type elements in a text
     *
	 * @return type
	 */
	function get_same_elements() {
		if ( ! isset($_POST[IGNONCE] ) || ! wp_verify_nonce( $_POST[IGNONCE], IGNONCE ) )
			return;
		$shortcode_name  = $_POST['shortcode_name'];
		$content         = $_POST['content'];

		// replace opening tag
		$regex   = '\\[' // Opening bracket
			. '(\\[?)' // 1: Optional second opening bracket for escaping shortcodes: [[tag]]
			. "($shortcode_name)" // 2: Shortcode name
			. '(?![\\w-])' // Not followed by word character or hyphen
			. '(' // 3: Unroll the loop: Inside the opening shortcode tag
			. '[^\\]\\/]*' // Not a closing bracket or forward slash
			. '(?:'
			. '\\/(?!\\])' // A forward slash not followed by a closing bracket
			. '[^\\]\\/]*' // Not a closing bracket or forward slash
			. ')*?'
			. ')'
			. '(?:'
			. '(\\/)' // 4: Self closing tag ...
			. '\\]' // ... and closing bracket
			. '|'
			. '\\]' // Closing bracket
			. ')'
			. '(\\]?)'; // 6: Optional second closing brocket for escaping shortcodes: [[tag]]

		preg_match_all('#' . $regex . '#', $content, $out, PREG_SET_ORDER);

		$select_options   = array();
		$options          = array();

		$k = 0;
		foreach ( $out as $el ) {
			$extracted_params  = IG_Pb_Helper_Shortcode::extract_params($el[0]);
			if ( $extracted_params ) {
				$k ++;
				$el_title   = $extracted_params['el_title'] ? $extracted_params['el_title'] : __( '(Untitled)', IGPBL );
				// Append unique number to ensure array key is unique
				// for sorting purpose.
				if ( isset( $options[$el_title] ) ) {
					$options[$el_title . "___" . $k ] = $el[0];
				}else{
					$options[$el_title] = $el[0];
				}

			}
		}

		if ( count( $options ) ) {
			// Sort the options by title
			ksort( $options );

			foreach ( $options as $title => $value ) {
				if ( stripos( $value, '#_EDITTED' ) === false ) {
					if ( strpos( $title, "___" ) !== false ) {
						$title = substr( $title, 0, strpos( $title, "___" ) );
					}
					$select_options[]  = "<option value='" . $value . "'>" . $title . '</option>';
				}
			}

		}

		// Print out the options HTML for select box
		echo implode('', $select_options);
		exit;
	}

	/**
	 * Merge new style params to existed shortcode content
     *
	 * @return type
	 */
	function merge_style_params() {
		if ( ! isset($_POST[IGNONCE] ) || ! wp_verify_nonce( $_POST[IGNONCE], IGNONCE ) )
			return;
		$shortcode_name  = $_POST['shortcode_name'];
		$structure       = str_replace( "\\", "", $_POST['content'] );
		$alter_structure = str_replace( "\\", "", $_POST['new_style_content'] );

		// Extract params of current element
		$params    = IG_Pb_Helper_Shortcode::extract_params( $structure, $shortcode_name );

		// Extract styling params of copied element
		$alter_params  = IG_Pb_Helper_Shortcode::get_styling_atts( $shortcode_name , $alter_structure );

		// Alter params of current element by copied element's params
		if ( count( $alter_params ) ) {
			foreach ( $alter_params as $k => $v ) {
				$params[$k]    = $v;
			}
		}

		$_shortcode_content = '';
		// Exclude shortcode_content from param list
		if ( isset ( $params['_shortcode_content'] ) ) {
			$_shortcode_content  = $params['_shortcode_content'];
			unset ($params['_shortcode_content']);
		}

		$new_shortcode_structure = IG_Pb_Helper_Shortcode::join_params($params, $shortcode_name, $_shortcode_content );
		// Print out new shortcode structure.
		echo $new_shortcode_structure;
		exit;
	}

	/**
	 * Echo custom css code, link custom css files
	 */
	function enqueue_custom_css() {
		global $post;
		if ( ! isset( $post ) || ! is_object( $post ) ) {
			return;
		}

		$ig_deactivate_pb = get_post_meta( $post->ID, '_ig_deactivate_pb', true );

		// if not deactivate pagebuilder on this post
		if ( empty( $ig_deactivate_pb ) ) {

			$custom_css_data = IG_Pb_Helper_Functions::custom_css_data( isset ( $post->ID ) ? $post->ID : NULL );
			extract( $custom_css_data );

			$css_files = stripslashes( $css_files );

			if ( ! empty( $css_files ) ) {
				$css_files = json_decode( $css_files );
				$data      = $css_files->data;

				foreach ( $data as $idx => $file_info ) {
					$checked = $file_info->checked;
					$url     = $file_info->url;

					// if file is checked to load, enqueue it
					if ( $checked ) {
						wp_enqueue_style( 'ig-pb-custom-file-' . $post->ID . '-' . $idx, $url );
					}
				}
			}
		}
	}

	/**
	 * Print style on front-end
	 */
	function print_frontend_styles() {
		global $post;
		if ( ! isset( $post ) || ! is_object( $post ) ) {
			return;
		}

		$ig_deactivate_pb = get_post_meta( $post->ID, '_ig_deactivate_pb', true );

		// if not deactivate pagebuilder on this post
		if ( empty( $ig_deactivate_pb ) ) {

			$custom_css_data = IG_Pb_Helper_Functions::custom_css_data( isset ( $post->ID ) ? $post->ID : NULL );
			extract( $custom_css_data );

			$css_custom = stripslashes( $css_custom );

			echo balanceTags( "<style id='ig-pb-custom-{$post->ID}-css'>\n$css_custom\n</style>\n" );
		}
	}

}