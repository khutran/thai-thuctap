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

/**
 * @todo : List all page template
 */

$data = IG_Pb_Helper_Layout::get_premade_layouts();
?>

<div class="jsn-master" id="ig-pb-layout-box">
	<div class="jsn-bootstrap3">
		<div id="ig-layout-lib" >
			<input type="hidden" id="ig-pb-layout-group" value="<?php echo esc_attr( IG_PAGEBUILDER_USER_LAYOUT ); ?>" />
			<!-- Elements -->

				<?php
				// Get only the templates which saved by user.
				$user_templates = isset ( $data['files'] ) && isset ( $data['files'][IG_PAGEBUILDER_USER_LAYOUT] ) ? $data['files'][IG_PAGEBUILDER_USER_LAYOUT] : array();
if ( ! count( $user_templates ) ) {
	echo '<p class="jsn-bglabel">You did not save any page yet.</p>';
} else {
	$items   = array();
	$items[] = '<ul class="jsn-items-list " style="height: auto;">';
	foreach ( $user_templates as $name => $path ) {
		$layout_name = IG_Pb_Helper_Layout::extract_layout_data( $path, 'name' );
		$layout_name = empty ( $layout_name ) ? __( '&mdash; Untitled &mdash;' ) : $layout_name;
		$content     = IG_Pb_Helper_Layout::extract_layout_data( $path, 'content' );
		$items[]     = '<li data-type="element" data-value="user_layout" data-id="' . $name . '" class="jsn-item premade-layout-item" style="display: list-item;">
					' . $layout_name . '
					<i class="icon-trash delete-item"></i>
				<textarea style="display:none">' . $content . '</textarea>
			</li>';

	}
	$items[] = '</ul>';

	echo balanceTags( implode( "\n", $items ) );
}

				?>

		</div>
	</div>
</div>