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
<ul class="<?php if ( ! @empty( $this->attributes['class'] ) ) echo esc_attr( $this->attributes['class'] ) . ' '; ?>ig-form-field-post-type" id="<?php $this->get( 'id' ); ?>">
<?php
// Backup original attributes
$original_attrs = $this->attributes;

foreach ( $this->choices as $content_type => $label ) :

// Update attributes
$this->attributes['type']  = 'hidden';
$this->attributes['name']  = "{$this->name}[{$content_type}]";
$this->attributes['value'] = isset( $this->value[ $content_type ] ) ? $this->value[ $content_type ] : 0;
?>
	<li id="<?php esc_attr_e( $content_type ); ?>" class="item">
		<div class="checkbox">
			<label>
				<input type="checkbox" onclick="jQuery(this).next().val(this.checked ? 1 : 0);" <?php if ( $this->attributes['value'] ) echo ' checked="checked"'; ?> />
				<input <?php $this->html_attributes( array( 'class', 'id', 'placeholder' ) ); ?> />
				<?php esc_html_e( $label ); ?>
			</label>
		</div>
	</li>
<?php
// Restore original attributes
$this->attributes = $original_attrs;

endforeach;
?>
</ul>
<?php
if ( $this->sortable ) :

$script = '
		$("#' . $this->get( 'id', null, true ) . '").sortable({});';

IG_Init_Assets::inline( 'js', $script, true );

endif;
