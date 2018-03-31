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

/**
 * Radio field renderer.
*
* @package  IG_Library
* @since    1.0.0
*/
class IG_Form_Field_Radio extends IG_Form_Field {
	/**
	 * Field type.
	 *
	 * @var  string
	 */
	protected $type = 'radio';

	/**
	 * Indicate whether checkbox(es) should be rendered inline or not?
	 *
	 * @var  boolean
	 */
	protected $inline = true;

	/**
	 * Check box options.
	 *
	 * @var  array
	 */
	protected $choices = array();
}
