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
 * Post type selector field renderer.
*
* @package  IG_Library
* @since    1.0.0
*/
class IG_Form_Field_Post_Type extends IG_Form_Field {
	/**
	 * Field type.
	 *
	 * @var  string
	 */
	protected $type = 'post-type';

	/**
	 * Enable sortable list or not?
	 *
	 * @var  boolean
	 */
	protected $sortable = true;

	/**
	 * Supported content types.
	 *
	 * @var  array
	 */
	protected $choices = array();

	/**
	 * Constructor.
	 *
	 * @param   array  $config  Field declaration.
	 * @param   array  $merge   Array of property should be merged.
	 *
	 * @return  void
	 */
	public function __construct( $config, $merge = array( 'attributes', 'choices' ) ) {
		// Get all post types
		$post_types = get_post_types( array( 'public' => true ), 'objects' );

		// Prepare post types
		foreach ( $post_types as $slug => $defines ) {
			$post_types[ $slug ] = $defines->labels->name;
		}

		// Update choices
		$this->choices = $post_types;

		// Call parent method to do remaining initialization
		parent::__construct( $config, $merge );

		// Prepare value
		if ( 'all' == $this->value ) {
			$this->value = array_map( 'strlen', $this->choices );
		}
	}
}
