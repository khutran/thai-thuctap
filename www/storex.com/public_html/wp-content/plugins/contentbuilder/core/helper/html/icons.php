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
class IG_Pb_Helper_Html_Icons extends IG_Pb_Helper_Html {
	/**
	 * Icons
	 * @param type $element
	 * @return string
	 */
	static function render( $element ) {
		$element = parent::get_extra_info( $element );
		$label   = parent::get_label( $element );
		$output  = "<div id='icon_selector'>
			<input type='hidden' value='{$element['std']}' id='{$element['id']}' name='{$element['id']}'  DATA_INFO />
		</div>";

		add_filter( 'ig-edit-element-required-assets', array( __CLASS__, 'enqueue_assets_modal' ), 0 );

		return parent::final_element( $element, $output, $label );
	}

	/**
     * Enqueue icon selector assets
     *
     * @param array $scripts
     * @return array
     */
	static function enqueue_assets_modal( $scripts ){
		$scripts = array_merge( $scripts, array( 'ig-pb-joomlashine-iconselector-js', ) );

		return $scripts;
	}
}