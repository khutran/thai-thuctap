<?php

// Define absolute path of plugin
define( 'IG_PB_PATH', plugin_dir_path( __FILE__ ) );

// Define absolute path of shortcodes folder
define( 'IG_PB_LAYOUT_PATH', IG_PB_PATH . 'core/shortcode/layout' );
define( 'IG_PB_ELEMENT_PATH', IG_PB_PATH . 'shortcodes' );

// Define premade layout folder
define( 'IG_PB_PREMADE_LAYOUT', IG_PB_PATH . 'templates/layout/pre-made' );
define( 'IG_PB_PREMADE_LAYOUT_URI', IG_PB_PATH . 'templates/layout/pre-made' );

// Define absolute path of templates folder
define( 'IG_PB_TPL_PATH', IG_PB_PATH . 'templates' );

// Define plugin uri
define( 'IG_PB_URI', plugin_dir_url( __FILE__ ) );

// Define plugin domain
define( 'IGPBL', 'ig-pb' );

// Define nonce ID
define( 'IGNONCE', 'ig_nonce_check' );

// Define URL to load element editor
define( 'IG_EDIT_ELEMENT_URL', admin_url( 'admin.php?ig-gadget=edit-element&action=form' ) );

// Define product identified name
define( 'IG_PAGEBUILDER_IDENTIFIED_NAME', 'ig_pagebuilder' );

// Define product addons
define( 'IG_PAGEBUILDER_ADDONS', 'ig_pagebuilder_addons_proelements' );

// Define folder in /wp-content/uploads stores user's template
define( 'IG_PAGEBUILDER_USER_LAYOUT', 'user' );

/**
 * Fix error warning of Woocommerce, when try to call Woocommerce in WP Admin
 */
if ( ! function_exists( 'woocommerce_reset_loop' ) ) {

	/**
	 * Reset the loop's index and columns when we're done outputting a product loop.
	 *
	 * @access public
	 * @subpackage	Loop
	 * @return void
	 */
	function woocommerce_reset_loop() {
		global $woocommerce_loop;
		// Reset loop/columns globals when starting a new loop
		$woocommerce_loop['loop'] = $woocommerce_loop['columns'] = '';
	}
}
