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
 * @todo : Define information of Buil-in Shortcodes of IG PageBuilder
 */

add_action( 'ig_pb_addon', 'ig_pb_builtin_sc_init' );

function ig_pb_builtin_sc_init() {

	/**
	 * Main class to init Shortcodes
	 * for IG PageBuilder
	 *
	 * @package  IG PageBuilder Shortcodes
	 * @since    1.0.0
	 */
	class IG_Pb_Builtin_Shortcode extends IG_Pb_Addon {

		public function __construct() {

			// Addon information
			$this->set_provider(
				array(
					'name'             => __( 'Standard Elements', IGPBL ),
					'file'             => __FILE__,
					'shortcode_dir'    => dirname( __FILE__ ),
					'js_shortcode_dir' => 'assets/js/shortcodes',
				)
			);

			//$this->custom_assets();
			// call parent construct
			parent::__construct();

			add_filter( 'plugin_action_links', array( &$this, 'plugin_action_links' ), 10, 2 );
		}

		/**
         * Regiter & enqueue custom assets
         */
		public function custom_assets() {
			// register custom assets
			$this->set_assets_register(
				array(
					'ig-frontend-free-css' => array(
						'src' => plugins_url( 'assets/css/main.css', dirname( __FILE__ ) ),
						'ver' => '1.0.0',
					),
					'ig-frontend-free-js'  => array(
						'src' => plugins_url( 'assets/js/main.js', dirname( __FILE__ ) ),
						'ver' => '1.0.0',
					)
				)
			);
			// enqueue assets for WP Admin pages
			$this->set_assets_enqueue_admin( array( 'ig-frontend-free-css' ) );
			// enqueue assets for IG Modal setting iframe
			$this->set_assets_enqueue_modal( array( 'ig-frontend-free-js' ) );
			// enqueue assets for WP Frontend
			$this->set_assets_enqueue_frontend( array( 'ig-frontend-free-css', 'ig-frontend-free-js' ) );
		}

		/**
		 * Remove deactivate link
		 *
		 * @staticvar type $this_plugin
		 *
		 * @param type $links
		 * @param type $file
		 *
		 * @return type
		 */
		public function plugin_action_links( $links, $file ) {
			static $this_plugin;

			if ( ! $this_plugin ) {
				$this_plugin = plugin_basename( __FILE__ );
			}
			if ( $file == $this_plugin ) {
				unset ( $links['deactivate'] );
			}

			return $links;
		}

	}

	$this_ = new IG_Pb_Builtin_Shortcode();
}