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

// Define necessary attributes
$this->attributes['type' ] = 'password';

if ( isset( $this->attributes['size'] ) ) {
	$this->attributes['size'] = 10;
}
?>
<input <?php $this->html_attributes(); ?> />
