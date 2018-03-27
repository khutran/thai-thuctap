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
 * HTML editor class use jquery-te 3rd-party.
 *
 * @package  IG_PageBuilder
 * @since    2.1.0
 */
class IG_Pb_Helper_Html_Editor extends IG_Pb_Helper_Html {
	
	/**
	 * Render editor using jquery-te library
	 * 
	 * @param type $element
	 * @return type
	 */
	static function render( $element ) {
		$element = parent::get_extra_info( $element );
		$label = parent::get_label( $element );
		$element['row'] = ( isset( $element['row'] ) ) ? $element['row'] : '8';
		$element['col'] = ( isset( $element['col'] ) ) ? $element['col'] : '50';
		//if ( $element['exclude_quote'] == '1' ) {
		//	$element['std'] = str_replace( '<ig_quote>', '"', $element['std'] );
		//}



        if ( array_key_exists('mce', $element) ) {

            if ( $element['mce'] == true ) $mce = true;
            else $mce = false;

        } else $mce = true;

		$output = "<textarea class='{$element['class']} ".( $mce ? "ig_pb_editor" :  "")." ' id='{$element['id']}' rows='{$element['row']}' cols='{$element['col']}' name='{$element['id']}' DATA_INFO>{$element['std']}</textarea>";
		
		add_filter( 'ig_pb_assets_register_modal', array( __CLASS__, 'register_assets_register_modal' ) );
		add_filter( 'ig-edit-element-required-assets', array( __CLASS__, 'enqueue_assets_modal' ), 9 );
		
		return parent::final_element( $element, $output, $label, true );
	}
	
	/**
	 * Register jquery-te assets
	 *
	 * @param array $scripts
	 * @return array
	 */
	static function register_assets_register_modal( $assets ){

        $assets['ig-pb-jquery-te-js'] = array(
			'src' => IG_Pb_Helper_Functions::path( 'assets/3rd-party/jquery-te' ) . '/jquery-te-1.4.0.min.js',
			'ver' => '1.4.0',
		);

		$assets['ig-pb-jquery-te-css'] = array(
			'src' => IG_Pb_Helper_Functions::path( 'assets/3rd-party/jquery-te' ) . '/jquery-te-1.4.0.css',
			'ver' => '1.4.0',
		);
	
		return $assets;
	}
	
	/**
	 * Enqueue jquery-te assets
	 *
	 * @param array $scripts
	 * @return array
	 */
	static function enqueue_assets_modal( $scripts ){
		$scripts = array_merge( $scripts, array( 'ig-pb-jquery-te-js', 'ig-pb-jquery-te-css' ) );
	
		return $scripts;
	}
	
}