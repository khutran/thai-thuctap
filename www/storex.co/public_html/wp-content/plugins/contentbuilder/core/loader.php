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

if ( ! class_exists( 'IG_Loader' ) ) :

// Include constant definition file
include_once dirname( __FILE__ ) . '/defines.php';

/**
 * Class autoloader.
 *
 * @package  IG_Library
 * @since    1.0.0
 */
class IG_Loader {
	/**
	 * Base paths to search for class file.
	 *
	 * @var  array
	 */
	protected static $paths = array();

	/**
	 * Register base path to search for class files.
	 *
	 * @param   string  $path    Base path.
	 * @param   string  $prefix  Class prefix.
	 *
	 * @return  void
	 */
	public static function register( $path, $prefix = 'IG_' ) {
		// Allow one base directory associates with more than one class prefix
		if ( ! isset( self::$paths[ $path ] ) ) {
			self::$paths[ $path ] = array( $prefix );
		} elseif ( ! in_array( $prefix, self::$paths[ $path ] ) ) {
			self::$paths[ $path ][] = $prefix;
		}
	}

	/**
	 * Loader for IG Library classes.
	 *
	 * @param   string  $className  Name of class.
	 *
	 * @return  void
	 */
	public static function load( $className ) {
		// Only autoload class name prefixed with IG_
		if ( 'IG_' == substr( $className, 0, 3 ) ) {
			// Filter paths to search for class file
			self::$paths = apply_filters( 'ig_loader_get_path', self::$paths );

			// Loop thru base directory to find class declaration file
			foreach ( array_reverse( self::$paths ) AS $base => $prefixes ) {
				// Loop thru all class prefix to find appropriate class declaration file
				foreach ( array_reverse( $prefixes ) as $prefix ) {
					// Check if requested class name match a supported class prefix
					if ( 0 === strpos( $className, $prefix ) ) {
						// Split the class name into parts separated by underscore character
						$path = explode( '_', trim( str_replace( $prefix, '', $className ), '_' ) );

						// Convert class name to file path
						$path = implode( '/', array_map( 'strtolower', $path ) );

						// Check if class file exists
						$file  = $path . '.php';
						$slave = $path . '/' . basename( $path ) . '.php';

						while ( true ) {
							$exists = false;

							// Check if file exists
							if ( @is_file( $base . '/' . $file ) ) {
								$exists = $file;
							}

							if ( $exists ) {
								break;
							}

							// Check if alternative file exists
							if ( @is_file( $base . '/' . $slave ) ) {
								$exists = $slave;
							}

							if ( $exists ) {
								break;
							}

							// If there is no any alternative file, quit the loop
							if ( false === strrpos( $file, '/' ) || 0 === strrpos( $file, '/' ) ) {
								break;
							}

							// Generate further alternative files
							$file  = preg_replace( '#/([^/]+)$#', '-\\1', $file );
							$slave = dirname( $file ) . '/' . substr( basename( $file ), 0, -4 ) . '/' . basename( $file );
						}

						if ( $exists ) {
							return include_once $base . '/' . $exists;
						}
					}
				}
			}

			return false;
		}
	}

	/**
	 * Search a file in registered paths.
	 *
	 * @param   string  $file  Relative file path to search for.
	 *
	 * @return  string
	 */
	public static function get_path( $file ) {
		// Generate alternative file name
		$slave = str_replace( '_', '-', $file );

		// Filter paths to search for file
		self::$paths = apply_filters( 'ig_loader_get_path', self::$paths );

		foreach ( array_reverse( self::$paths ) AS $base => $prefixes ) {
			if ( @is_file( $base . '/' . $slave ) ) {
				return $base . '/' . $slave;
			} elseif ( @is_file( $base . '/' . $file ) ) {
				return $base . '/' . $file;
			}
		}

		return null;
	}
}

// Register class autoloader with PHP
spl_autoload_register( array( 'IG_Loader', 'load' ) );

endif;

// Register base path to look for class file
IG_Loader::register( dirname( __FILE__ ), 'IG_' );
