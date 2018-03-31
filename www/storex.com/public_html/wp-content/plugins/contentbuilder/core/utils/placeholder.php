<?php
/**
 * Manage placeholders on Php files
 *
 * Set & Get placeholder on Php files
 *
 * @author		InnoGears Team <support@www.innogears.com>
 * @package		IGPGBLDR
 * @version		$Id$
 */

// define array of placeholders php
global  $placeholders;
$placeholders = array();
$placeholders['widget_title']   = '_IG_WIDGET_TIGLE_';
$placeholders['extra_class']    = '_IG_EXTRA_CLASS_';
$placeholders['index']		    = '_IG_INDEX_';
$placeholders['custom_style']   = '_IG_STYLE_';
$placeholders['standard_value'] = '_IG_STD_';
$placeholders['wrapper_append'] = '_IG_WRAPPER_TAG_';

class IG_Pb_Utils_Placeholder {
	/**
	 * Add placeholder to string
	 * Ex:	IG_Pb_Utils_Placeholder::add_placeholder( 'Text %s', 'widget_title' )	=>	'Progress bar _IG_WIDGET_TIGLE_'
	*/
	static function add_placeholder( $string, $placeholder, $expression = '' ){
		global $placeholders;
		if ( ! isset( $placeholders[$placeholder] ) )
			return NULL;		
		if ( empty( $expression ) )
			return sprintf( $string, $placeholders[$placeholder] );
		else
			return sprintf( $string, sprintf( $expression, $placeholders[$placeholder] ) );
	}

	/**
	 * Replace placeholder with real value
	 * Ex:	str_replace( '_IG_STYLE_', $replace, $string );   =>  IG_Pb_Utils_Placeholder::remove_placeholder( $string, 'custom_style', $replace )
	*/
	static function remove_placeholder( $string, $placeholder, $value ){
		global $placeholders;
		if ( ! isset( $placeholders[$placeholder] ) )
			return $string;
		return str_replace( $placeholders[$placeholder], $value, $string );
	}

	/**
	 * Get placeholder value
	 * @global array $placeholders
	 * @param type $placeholder
	 * @return string
	 */
	static function get_placeholder( $placeholder ){
		global $placeholders;
		if ( ! isset( $placeholders[$placeholder] ) )
			return NULL;
		return $placeholders[$placeholder];
	}
}