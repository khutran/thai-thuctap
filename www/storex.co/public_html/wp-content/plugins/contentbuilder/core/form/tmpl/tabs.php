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
<div class="ig-form-tabs">
	<ul class="nav nav-tabs clearfix">
		<?php $first = true; foreach ( $this->current_tabs as $tid => $tab ) : ?>
		<li<?php if ( $first ) : ?> class="active"<?php endif; ?>>
			<a data-toggle="tab" href="#ig-form-tab-<?php esc_attr_e( $tid ); ?>">
				<?php esc_html_e( isset( $tab['title'] ) ? $tab['title'] : $tid, $this->text_domain ); ?>
			</a>
		</li>
		<?php $first = false; endforeach; ?>
	</ul>

	<div class="tab-content">
		<?php $first = true; foreach ( $this->current_tabs as $tid => $tab ) : ?>
		<div class="tab-pane<?php if ($first) echo ' active'; ?>" id="ig-form-tab-<?php esc_attr_e( $tid ); ?>">
			<?php
			if ( isset( $tab['fields'] ) ) :
				$this->current_fields = $tab['fields'];

				// Load fields template
				include IG_Loader::get_path( 'form/tmpl/fields.php' );
			endif;

			if ( isset( $tab['fieldsets'] ) ) :
				$this->current_fieldsets = $tab['fieldsets'];

				// Load fieldsets template
				include IG_Loader::get_path( 'form/tmpl/fieldsets.php' );
			endif;

			if ( isset( $tab['accordion'] ) ) :
				$this->current_accordion = $tab['accordion'];

				// Load accordion template
				include IG_Loader::get_path( 'form/tmpl/accordion.php' );
			endif;
			?>
		</div>
		<?php $first = false; endforeach; ?>
	</div>
</div>
