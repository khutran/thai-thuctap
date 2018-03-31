<?php
/**
 * @version    $Id$
 * @package    IG_Library
 * @author     InnoGears Team <support@innogears.com>
 * @copyright  Copyright (C) 2012 InnoGears.com. All Rights Reserved.
 * @license    GNU/GPL v2 or later http://www.gnu.org/licenses/gpl-2.0.html
 *
 * Websites: http://www.innogears.com
 */

if ( ! class_exists( 'IG_Init_Plugin' ) ) :

/**
 * IG Library initialization.
 *
 * @package  IG_Library
 * @since    1.0.0
 */
class IG_Init_Plugin {
	/**
	 * Define Ajax actions.
	 *
	 * @var  array
	 */
	protected static $actions = array( 'ig-addons-management' );

	/**
	 * Initialize IG Library.
	 *
	 * @return  void
	 */
	public static function init() {
		global $pagenow;

		if ( 'admin-ajax.php' == $pagenow && isset( $_GET['action'] ) && in_array( $_GET['action'], self::$actions ) ) {
			// Init WordPress Filesystem Abstraction
			IG_Init_File_System::get_instance();

			// Register Ajax actions
			switch ( $_GET['action'] ) {
				case 'ig-addons-management' :
					IG_Product_Addons::hook();
				break;
			}
		}

		// Add filter to fine-tune uploaded file name
		add_filter( 'wp_handle_upload_prefilter', array( __CLASS__, 'wp_handle_upload_prefilter' ) );

		// Do 'ig_init' action
		do_action( 'ig_init' );
	}

	/**
	 * Apply 'wp_handle_upload_prefilter' filter.
	 *
	 * @param   array  $file  Array containing uploaded file details.
	 *
	 * @return  string
	 */
	public static function wp_handle_upload_prefilter( $file ) {
		if ( $name = iconv( 'utf-8', 'ascii//TRANSLIT//IGNORE', $file['name'] ) ) {
			$file['name'] = $name;
		}

		return $file;
	}

	/**
	 * Register action to initialize IG Library.
	 *
	 * @return  void
	 */
	public static function hook() {
		// Register action to initialize IG Library
		static $registered;

		if ( ! isset( $registered ) ) {
			add_action( 'init', array( __CLASS__, 'init' ) );

			$registered = true;
		}
	}
}

endif;
