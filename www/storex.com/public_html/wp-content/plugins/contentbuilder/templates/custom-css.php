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
 * @todo : Custom CSS modal
 */

$custom_css_item = '<li class="jsn-item ui-state-default"><label class="checkbox"><input type="checkbox" name="item-list" value="VALUE" CHECKED>VALUE</label></li>';
$css_files = $css_custom = '';

if ( empty ( $_GET['pid'] ) ) {
	exit;
}

$post_id = esc_sql( $_GET['pid'] );

// get custom css data
$custom_css_data = IG_Pb_Helper_Functions::custom_css_data( isset ( $post_id ) ? $post_id : NULL );
extract( $custom_css_data );
$css_files  = stripslashes( $css_files );
$css_custom = stripslashes( $css_custom );
$_css_files_tooltip = 'Insert path to your CSS files, each line for each file.
						<br>The path can be relative like:
						<br> <i><u>assets/css/yourfile.css</u></i>
						<br>or absolute like:
						<br> <i><u>http://yourwebsite.com/assets/css/yourfile.css</u></i>
						';

$_style    = '.tooltip-inner { min-width: 350px !important; } .top-cut .tooltip-inner { margin-top: 60px; }';
IG_Init_Assets::inline( 'css', $_style, true );
?>

<div class="jsn-master" id="ig-pb-custom-css-box">
	<div class="jsn-bootstrap3">

		<!-- CSS files -->
		<div class="form-group control-group jsn-items-list-container ig-modal-content">
			<label for="option-items-itemlist" class="control-label top-cut" ><?php _e( 'CSS Files', IGPBL ); ?><i class=" icon-question-sign"  data-toggle="tooltip"  data-title="<?php _e( $_css_files_tooltip, IGPBL ); ?>"></i></label>
			<div class="controls">
				<div class="jsn-buttonbar">
					<button id="items-list-edit" class="btn btn-default btn-sm">
						<i class="icon-pencil"></i><?php _e( 'Edit', IGPBL ); ?></button>
					<button id="items-list-save" class="btn btn-default btn-sm btn-primary hidden">
						<i class="icon-ok"></i><?php _e( 'Done', IGPBL ); ?></button>
				</div>
				<ul class="jsn-items-list ui-sortable css-files-container">
					<?php
if ( ! empty( $css_files ) ) {
	$css_files = json_decode( $css_files );
	$data      = $css_files->data;
	foreach ( $data as $file_info ) {
		$checked = $file_info->checked;
		$url     = $file_info->url;

		$item = str_replace( 'VALUE', $url, $custom_css_item );
		$item = str_replace( 'CHECKED', $checked ? 'checked' : '', $item );
		echo balanceTags( $item );
	}
}
					?>
				</ul>
				<div class="items-list-edit-content hidden">
					<textarea class="col-xs-12" rows="5"></textarea></div>
			</div>
		</div>

		<!-- Custom CSS code -->
		<div class="control-group jsn-items-list-container ig-modal-content">
			<label for="option-items-itemlist" class="control-label"><?php _e( 'CSS Code', IGPBL ); ?><i class="icon-question-sign" data-toggle="tooltip" data-title="<?php _e( 'Input here custom CSS code you want to be loaded on this page', IGPBL ); ?>"></i></label>

			<div class="controls">
				<textarea id="custom-css" class="input-sm css-code" rows="10"><?php echo esc_textarea( $css_custom ); ?></textarea>
			</div>
		</div>

	</div>
</div>

<script type='text/html' id='tmpl-ig-custom-css-item'>
    <?php echo balanceTags( $custom_css_item );?>
</script>