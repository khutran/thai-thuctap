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

if ( ! class_exists( 'IG_Init_File_System' ) ) :

/**
 * File system initialization.
 *
 * @package  IG_Library
 * @since    1.0.0
 */
class IG_Init_File_System {
	/**
	 * Initialize WordPress Filesystem Abstraction.
	 *
	 * @return  object
	 */
	public static function get_instance() {
		global $wp_filesystem;

		if ( ! function_exists( 'WP_Filesystem' ) ) {
			include_once ABSPATH . 'wp-admin/includes/file.php';
		}

		if ( ! $wp_filesystem ) {
			WP_Filesystem();
		}

		return $wp_filesystem;
	}
}

endif;
