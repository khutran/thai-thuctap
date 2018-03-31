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
?>
<div class="ig-form-field-radio" id="<?php $this->get( 'id' ); ?>">
<?php
// Backup original attributes
$original_attrs = $this->attributes;

foreach ( $this->choices as $value => $label ) :

// Update attributes
$this->attributes['value'] = $value;

if ( $this->value == $value ) {
	$this->attributes['checked'] = 'checked';
}

// Prepare additional attributes
if ( is_array( $label ) ) {
	foreach ( $label as $k => $v ) {
		if ( 'label' != $k ) {
			$this->attributes[ $k ] = $v;
		}
	}

	$label = $label['label'];
}
?>
	<div class="radio<?php if ( $this->inline ) echo '-inline'; ?>">
		<label>
			<input <?php $this->html_attributes( array( 'class', 'id', 'placeholder' ) ); ?> />
			<?php esc_html_e( $label, $this->text_domain ); ?>
		</label>
	</div>
<?php
// Trigger click event if option is selected
if ( isset( $this->attributes['onclick'] ) && isset( $this->attributes['checked'] ) ) {
	$script = '
		$(window).load(function() {
			$("#' . $this->get( 'id', null, true ) . ' input[checked]").trigger("click");
		});';

	IG_Init_Assets::inline( 'js', $script, true );
}

// Restore original attributes
$this->attributes = $original_attrs;

endforeach;
?>
</div>
