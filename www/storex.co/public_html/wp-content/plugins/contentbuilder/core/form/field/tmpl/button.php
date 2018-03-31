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
if ( ! isset( $this->attributes['class'] ) || 'form-control' == $this->attributes['class'] ) {
	$this->attributes['class'] = 'btn btn-primary';
}

if ( ! isset( $this->attributes['type'] ) ) {
	$this->attributes['type'] = 'submit';
}
?>
<button <?php $this->html_attributes( array( 'autocomplete', 'placeholder', 'name', 'value' ) ); ?>><?php esc_html_e( $this->attributes['text'], $this->text_domain ); ?></button>
