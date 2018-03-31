<?php
/**
 * Plugin Name: IG PageBuilder PT MOD
 * Plugin URI:  http://www.innogears.com
 * Description: Awesome content builder for Wordpress websites
 * Version:     1.0
 * Author:      InnoGears Team <support@www.innogears.com>
 * Author URI:  http://www.innogears.com
 * License:     GNU/GPL v2 or later http://www.gnu.org/licenses/gpl-2.0.html
 */

// Set custom error reporting level
error_reporting( E_ALL ^ E_NOTICE );

// Define path to this plugin file
define( 'IG_PB_FILE', __FILE__ );

// Load WordPress plugin functions
require_once( ABSPATH . 'wp-admin/includes/plugin.php' );

if ( ! class_exists( 'IG_Pb_Init' ) ) :

/**
 * Initialize IG PageBuilder.
 *
 * @package  IG PageBuilder
 * @since    1.0.0
 */
class IG_Pb_Init {
	/**
	 * Constructor
	 *
	 * @return  void
	 */
	public function __construct() {
		// Load core functionalities
		$this->includes();
		$this->autoload();

		// Initialize assets management and loader
		IG_Pb_Assets_Register::init();
		IG_Init_Assets       ::hook();

		// Register necessary actions
		add_action( 'widgets_init', array(                 &$this, 'init'          ), 100 );
		add_action( 'admin_init'  , array(       'IG_Gadget_Base', 'hook'          ), 100 );
		add_action( 'admin_init'  , array( 'IG_Pb_Product_Plugin', 'settings_form' )      );

		// Initialize built-in shortcodes
		include dirname( __FILE__ ) . '/shortcodes/main.php';
	}

	/**
	 * Initialize core functionalities.
	 *
	 * @return  void
	 */
	function init(){
		global $Ig_Pb, $Ig_Pb_Widgets;

		// Initialize IG PageBuilder
		$Ig_Pb = new IG_Pb_Core();
		new IG_Pb_Utils_Plugin();
		remove_filter( 'the_content', 'wpautop' );
		do_action( 'ig_pagebuilder_init' );

		// Initialize productivity functions
		IG_Pb_Product_Plugin::init();

		// Initialize widget support
		$Ig_Pb_Widgets = ! empty( $Ig_Pb_Widgets ) ? $Ig_Pb_Widgets : IG_Pb_Helper_Functions::widgets();
	}

	/**
	 * Include required files.
	 *
	 * @return  void
	 */
	function includes() {
		// include core files
		include_once 'core/loader.php';
		include_once 'defines.php';
	}

	/**
	 * Register autoloader.
	 *
	 * @return  void
	 */
	function autoload() {
		IG_Loader::register( IG_PB_PATH . 'core'       , 'IG_Pb_'     );
		IG_Loader::register( IG_PB_PATH . 'core/gadget', 'IG_Gadget_' );

		// Allow autoload registration from outside
		do_action( 'ig_pb_autoload' );
	}
}

// Instantiate IG PageBuilder initialization class
$GLOBALS['ig_pagebuilder'] = new IG_Pb_Init();

endif;
