<?php
/**
 * @version    $Id$
 * @package    WR MegaMenu
 * @author     WooRockets Team <support@woorockets.com>
 * @copyright  Copyright (C) 2014 WooRockets.com All Rights Reserved.
 * @license    GNU/GPL v2 or later http://www.gnu.org/licenses/gpl-2.0.html
 *
 * Websites: http://www.woorockets.com
 * Technical Support:  Feedback - http://www.woorockets.com
 */

// define array of placeholders php
global $placeholders;
$placeholders                     = array();
$placeholders[ 'widget_title' ]   = '_WR_WIDGET_TIGLE_';
$placeholders[ 'extra_class' ]    = '_WR_EXTRA_CLASS_';
$placeholders[ 'index' ]          = '_WR_INDEX_';
$placeholders[ 'custom_style' ]   = '_WR_STYLE_';
$placeholders[ 'standard_value' ] = '_WR_STD_';
$placeholders[ 'wrapper_append' ] = '_WR_WRAPPER_TAG_';

class WR_Megamenu_Helpers_Placeholder
{
	/**
	 * Add placeholder to string
	 * Ex:	WR_Megamenu_Helpers_Placeholder::add_placeholder( 'Text %s', 'widget_title' )	=>	'Progress bar _WR_WIDGET_TIGLE_'
	 */
	static function add_placeholder( $string, $placeholder, $expression = '' )
	{
		global $placeholders;
		if ( ! isset( $placeholders[ $placeholder ] ) )
			return NULL;
		if ( empty( $expression ) )
			return sprintf( $string, $placeholders[ $placeholder ] );
		else
			return sprintf( $string, sprintf( $expression, $placeholders[ $placeholder ] ) );
	}

	/**
	 * Replace placeholder with real value
	 * Ex:	str_replace( '_WR_STYLE_', $replace, $string );   =>  WR_Megamenu_Helpers_Placeholder::remove_placeholder( $string, 'custom_style', $replace )
	 */
	static function remove_placeholder( $string, $placeholder, $value )
	{
		global $placeholders;
		if ( ! isset( $placeholders[ $placeholder ] ) )
			return $string;
		return str_replace( $placeholders[ $placeholder ], $value, $string );
	}

	/**
	 * Get placeholder value
	 * @global array $placeholders
	 * @param type $placeholder
	 * @return string
	 */
	static function get_placeholder( $placeholder )
	{
		global $placeholders;
		if ( ! isset( $placeholders[ $placeholder ] ) )
			return NULL;
		return $placeholders[ $placeholder ];
	}
}