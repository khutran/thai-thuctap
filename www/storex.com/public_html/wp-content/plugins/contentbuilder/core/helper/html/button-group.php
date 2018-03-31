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
class IG_Pb_Helper_Html_Button_Group extends IG_Pb_Helper_Html {
	/**
	 * Button group
	 * @param type $element
	 * @return string
	 */
	static function render( $element ) {
		$element = parent::get_extra_info( $element );
		$label   = parent::get_label( $element );

		$output = "<div class='btn-group'>
		  <a class='btn btn-default dropdown-toggle' data-toggle='dropdown' href='#'>
			".__( 'Convert to', IGPBL )."...
			<span class='caret'></span>
		  </a>
		  <ul class='dropdown-menu'>";
		foreach ( $element['actions'] as $action ) {
			$output .= "<li><a href='#' data-action = '{$action["action"]}' data-action-type = '{$action["action_type"]}'>{$action['std']}</a></li>";
		}
		$output .= '</ul></div>';
		return parent::final_element( $element, $output, $label );
	}
}