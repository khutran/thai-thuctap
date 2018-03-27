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

// Check if this section has own template
$tmpl = IG_Loader::get_path( "form/tmpl/section/{$sid}.php" );

// Check if a specific section is requested
if ( empty( $section_id ) || $section_id == $sid ) :

if ( ! empty( $tmpl ) ) :
	include $tmpl;
else :
	if ( isset( $section['fields'] ) ) :
		$this->current_fields = $section['fields'];

		// Load fields template
		include IG_Loader::get_path( 'form/tmpl/fields.php' );
	endif;

	if ( isset( $section['fieldsets'] ) ) :
		$this->current_fieldsets = $section['fieldsets'];

		// Load fieldsets template
		include IG_Loader::get_path( 'form/tmpl/fieldsets.php' );
	endif;

	if ( isset( $section['accordion'] ) ) :
		$this->current_accordion = $section['accordion'];

		// Load accordion template
		include IG_Loader::get_path( 'form/tmpl/accordion.php' );
	endif;

	if ( isset( $section['tabs'] ) ) :
		$this->current_tabs = $section['tabs'];

		// Load tabs template
		include IG_Loader::get_path( 'form/tmpl/tabs.php' );
	endif;
endif;

endif;
