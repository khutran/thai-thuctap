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
/*
 * Parent class for sub elements
 */

class IG_Pb_Shortcode_Child extends IG_Pb_Shortcode_Element {

	/**
     * Over write parent method
     *
     * @param string $content
     * @param string $shortcode_data
     * @param string $el_title
     * @param int $index
	 * @param bool $inlude_sc_structure
	 * @param array $extra_params
     * @return string
     */
	public function element_in_pgbldr( $content = '', $shortcode_data = '', $el_title = '', $index = '', $inlude_sc_structure = true, $extra_params = array() ) {
		$this->config['sub_element'] = true;
		return parent::element_in_pgbldr( $content, $shortcode_data, $el_title, $index, $inlude_sc_structure, $extra_params );
	}

}
