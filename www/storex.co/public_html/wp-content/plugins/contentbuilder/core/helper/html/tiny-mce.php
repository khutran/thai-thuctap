<?php
/**
 * @version	$Id$
 * @package	IG PageBuilder
 * @author	 InnoGears Team <support@www.innogears.com>
 * @copyright  Copyright (C) 2012 www.innogears.com. All Rights Reserved.
 * @license	GNU/GPL v2 or later http://www.gnu.org/licenses/gpl-2.0.html
 *
 * Websites: http://www.www.innogears.com
 * Technical Support:  Feedback - http://www.www.innogears.com
 */
class IG_Pb_Helper_Html_Tiny_Mce extends IG_Pb_Helper_Html {
	/**
	 * text area with WYSIWYG
	 * @param type $element
	 * @return type
	 */
	static function render( $element ) {
		$element = parent::get_extra_info( $element );
		$label = parent::get_label( $element );
		$element['row'] = ( isset( $element['row'] ) ) ? $element['row'] : '8';
		$element['col'] = ( isset( $element['col'] ) ) ? $element['col'] : '50';
		if ( $element['exclude_quote'] == '1' ) {
			$element['std'] = str_replace( '<ig_quote>', '"', $element['std'] );
		}
		$output = "<textarea class='{$element['class']} ig_pb_tiny_mce' id='{$element['id']}' rows='{$element['row']}' cols='{$element['col']}' name='{$element['id']}' DATA_INFO>{$element['std']}</textarea>";

		add_filter( 'ig_pb_assets_register_modal', array( __CLASS__, 'register_assets_register_modal' ) );
		add_filter( 'ig-edit-element-required-assets', array( __CLASS__, 'enqueue_assets_modal' ), 9 );

		return parent::final_element( $element, $output, $label, true );
	}

	/**
     * Register tinymce assets
     *
     * @param array $scripts
     * @return array
     */
	static function register_assets_register_modal( $assets ){
		$assets['ig-pb-wysiwyg-js'] = array(
			'src' => IG_Pb_Helper_Functions::path( 'assets/3rd-party/jquery-jwysiwyg' ) . '/jquery.wysiwyg.js',
			'ver' => '1.0.0',
		);
		$assets['ig-pb-wysiwyg-css'] = array(
			'src' => IG_Pb_Helper_Functions::path( 'assets/3rd-party/jquery-jwysiwyg' ) . '/jquery.wysiwyg.css',
			'ver' => '1.0.0',
		);
		$assets['ig-pb-wysiwyg-0.9-js'] = array(
			'src' => IG_Pb_Helper_Functions::path( 'assets/3rd-party/jquery-jwysiwyg' ) . '/jquery.wysiwyg-0.9.js',
			'ver' => '1.0.0',
		);
		$assets['ig-pb-wysiwyg-0.9-css'] = array(
			'src' => IG_Pb_Helper_Functions::path( 'assets/3rd-party/jquery-jwysiwyg' ) . '/jquery.wysiwyg-0.9.css',
			'ver' => '1.0.0',
		);

		$assets['ig-pb-wysiwyg-colorpicker-js'] = array(
			'src' => IG_Pb_Helper_Functions::path( 'assets/3rd-party/jquery-jwysiwyg' ) . '/controls/wysiwyg.colorpicker.js',
			'ver' => '1.0.0',
		);
		$assets['ig-pb-wysiwyg-table-js'] = array(
			'src' => IG_Pb_Helper_Functions::path( 'assets/3rd-party/jquery-jwysiwyg' ) . '/controls/wysiwyg.table.js',
			'ver' => '1.0.0',
		);
		$assets['ig-pb-wysiwyg-cssWrap-js'] = array(
			'src' => IG_Pb_Helper_Functions::path( 'assets/3rd-party/jquery-jwysiwyg' ) . '/controls/wysiwyg.cssWrap.js',
			'ver' => '1.0.0',
		);
		$assets['ig-pb-wysiwyg-image-js'] = array(
			'src' => IG_Pb_Helper_Functions::path( 'assets/3rd-party/jquery-jwysiwyg' ) . '/controls/wysiwyg.image.js',
			'ver' => '1.0.0',
		);
		$assets['ig-pb-wysiwyg-link-js'] = array(
			'src' => IG_Pb_Helper_Functions::path( 'assets/3rd-party/jquery-jwysiwyg' ) . '/controls/wysiwyg.link.js',
			'ver' => '1.0.0',
		);

		return $assets;
	}

	/**
     * Enqueue colorpikcer assets
     *
     * @param array $scripts
     * @return array
     */
	static function enqueue_assets_modal( $scripts ){
		$scripts = array_merge( $scripts, array( 'ig-pb-wysiwyg-js', 'ig-pb-wysiwyg-css', 'ig-pb-wysiwyg-0.9-js', 'ig-pb-wysiwyg-0.9-css', 'ig-pb-wysiwyg-colorpicker-js', 'ig-pb-wysiwyg-table-js', 'ig-pb-wysiwyg-cssWrap-js', 'ig-pb-wysiwyg-image-js', 'ig-pb-wysiwyg-link-js' ) );

		return $scripts;
	}
}