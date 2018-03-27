<?php
/**
 * @version    $Id$
 * @package    IG_PageBuilder
 * @author     InnoGears Team <support@www.innogears.com>
 * @copyright  Copyright (C) 2012 InnoGears.com. All Rights Reserved.
 * @license    GNU/GPL v2 or later http://www.gnu.org/licenses/gpl-2.0.html
 *
 * Websites: http://www.www.innogears.com
 * Technical Support:  Feedback - http://www.www.innogears.com/contact-us/get-support.html
 */

/**
 * Init InnoGears's plugins.
 *
 * @package  IG_Plugin_Framework
 * @since    1.0.0
 */
class IG_Pb_Assets_Register {
	/**
	 * Assets to be registered.
	 *
	 * @var  array
	 */
	protected static $assets = array(
		/**
		 * Third party assets.
		 */
		'ig-pb-bootstrap-css' => array(
			'src' => 'assets/3rd-party/bootstrap3/css/bootstrap.min.css',
			'ver' => '3.1.1',
			'site' => 'admin',
		),

		'ig-pb-bootstrap-responsive-css' => array(
			'src' => 'assets/3rd-party/bootstrap3/css/bootstrap-responsive.min.css',
			'deps' => array( 'ig-pb-bootstrap-css' ),
			'ver' => '3.1.1',
			'site' => 'admin',
		),

		'ig-pb-bootstrap-js' => array(
			'src' => 'assets/3rd-party/bootstrap3/js/bootstrap.min.js',
			'deps' => array( 'jquery' ),
			'ver' => '3.1.1',
			'site' => 'admin',
		),

		'ig-pb-bootstrap-paginator-js' => array(
			'src' => 'assets/3rd-party/bootstrap-paginator/bootstrap-paginator.js',
			'deps' => array( 'ig-pb-bootstrap-js' ),
			'ver' => '0.5',
		),

		'ig-pb-classygradient-css' => array(
			'src' => 'assets/3rd-party/classygradient/css/jquery.classygradient.css',
			'deps' => array( 'ig-pb-colorpicker-css' ),
			'ver' => '1.0.0',
		),

		'ig-pb-classygradient-js' => array(
			'src' => 'assets/3rd-party/classygradient/js/jquery.classygradient.js',
			'deps' => array( 'jquery-ui-draggable', 'ig-pb-colorpicker-js' ),
			'ver' => '1.0.0',
		),

		'ig-pb-colorpicker-css' => array(
			'src' => 'assets/3rd-party/colorpicker/css/colorpicker.css',
		),

		'ig-pb-colorpicker-js' => array(
			'src' => 'assets/3rd-party/colorpicker/js/colorpicker.js',
		),

		'ig-pb-font-icomoon-css' => array(
			'src' => 'assets/3rd-party/font-icomoon/css/icomoon.css',
		),

		'ig-pb-jsn-css' => array(
			'src' => 'assets/3rd-party/jsn/css/jsn-gui.css',
			'deps' => array( 'ig-pb-bootstrap-css' ),
		),

		'ig-pb-joomlashine-fontselector-js' => array(
			'src' => 'assets/3rd-party/jsn/js/jsn-fontselector.js',
		),

		'ig-pb-joomlashine-iconselector-js' => array(
			'src' => 'assets/3rd-party/jsn/js/jsn-iconselector.js',
		),

		'ig-pb-joomlashine-modalresize-js' => array(
			'src' => 'assets/3rd-party/jsn/js/jsn-modalresize.js',
		),

		'ig-pb-jquery-easing-js' => array(
			'src' => 'assets/3rd-party/jquery-easing/jquery.easing.min.js',
			'ver' => '1.3',
		),

		'ig-pb-jquery-fancybox-js' => array(
			'src' => 'assets/3rd-party/jquery-fancybox/jquery.fancybox-1.3.4.js',
			'ver' => '1.3.4',
		),

		'ig-pb-jquery-fancybox-css' => array(
			'src' => 'assets/3rd-party/jquery-fancybox/jquery.fancybox-1.3.4.css',
			'ver' => '1.3.4',
		),

		'ig-pb-jquery-lazyload-js' => array(
			'src' => 'assets/3rd-party/jquery-lazyload/jquery.lazyload.js',
			'deps' => array( 'jquery' ),
			'ver' => '1.8.4',
		),

		'ig-pb-jquery-resize-js' => array(
			'src' => 'assets/3rd-party/jquery-resize/jquery.ba-resize.js',
			'deps' => array( 'jquery' ),
			'ver' => '1.1',
		),

		'ig-pb-jquery-select2-css' => array(
			'src' => 'assets/3rd-party/jquery-select2/select2.css',
			'ver' => '3.3.2',
		),

		'ig-pb-jquery-select2-bootstrap3-css' => array(
			'src' => 'assets/3rd-party/jquery-select2/select2-bootstrap3.css',
			'ver' => '3.3.2',
		),

		'ig-pb-jquery-select2-js' => array(
			'src' => 'assets/3rd-party/jquery-select2/select2.js',
			'deps' => array( 'jquery' ),
			'ver' => '3.3.2',
		),

		'ig-pb-jquery-ui-css' => array(
			'src' => 'assets/3rd-party/jquery-ui/css/ui-bootstrap/jquery-ui-1.9.0.custom.css',
			'ver' => '1.9.0',
		),

		'ig-zeroclipboard-js' => array(
			'src' => 'assets/3rd-party/zeroclipboard/ZeroClipboard.min.js',
			'ver' => '1.3.5',
		),

		'ig-pb-convert-data-js' => array(
			'src' => 'assets/innogears/js/convert-data.js',
		),

		'ig-pb-activity-js' => array(
			'src' => 'assets/innogears/js/activity.js',
		),
	);

	/**
	 * Set hook prefix for loading assets.
	 *
	 * @param   string  $prefix  Current hook prefix.
	 *
	 * @return  string
	 */
	public static function hook_prefix( $prefix = '' ) {
		if ( 'admin' == $prefix && class_exists( 'IG_Pb_Helper_Functions' ) && IG_Pb_Helper_Functions::is_modal() ) {
			$prefix = 'pb_admin';
		}

		return $prefix;
	}

	/**
	 * Filter to apply supported assets.
	 *
	 * @param   array  $assets  Current assets.
	 *
	 * @return  array
	 */
	public static function apply_assets( $assets = array() ) {
		foreach ( self::$assets AS $key => $value ) {
			if ( ! isset( $assets[$key] ) ) {
				// Fine-tune asset location
				if ( ! preg_match( '#^(https?:)?/#', $value['src'] ) AND is_file( IG_PB_PATH . ltrim( $value['src'], '/' ) ) ) {
					$value['src'] = IG_PB_URI . ltrim( $value['src'], '/' );

					$assets[$key] = $value;
				}
			}
		}

		return $assets;
	}

	/**
	 * Initialize InnoGears's plugins.
	 *
	 * @return  void
	 */
	public static function init() {
		// Add filters to register assets
		add_filter( 'ig_asset_hook_prefix', array( __CLASS__, 'hook_prefix'  ) );
		add_filter( 'ig_register_assets',   array( __CLASS__, 'apply_assets' ) );

		// Do 'ig_init_plugins' action
		do_action( 'ig_pb_init_plugin' );
	}
}
