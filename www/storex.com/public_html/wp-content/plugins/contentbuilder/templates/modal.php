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
 *
 */

/**
 * @todo : Modal box content
 */

if ( ! isset( $_POST ) ) {
	die;
}

extract( $_POST );
$submodal = ! empty( $submodal ) ? 'submodal_frame' : '';
if ( ! isset( $params ) ) {
	exit;
}

if ( ! empty( $shortcode ) ) {
	$script     = '';
	if ( isset( $init_tab ) && $init_tab == 'styling' ) {
		// Auto move to Styling tab if previous action
		// is coping style from other element.
		$script .= "
			(function ($) {
				$(document).ready(function (){
					setTimeout(function (){
						$('[href=\"#styling\"]').click();
					}, 500);

				});
			})(jQuery);";
	}

	if ($_REQUEST['form_only']) {
		$script .=  " var ig_pb_modal_ajax = true;";
	}

	IG_Init_Assets::print_inline( 'js', $script, true );
	?>

	<div id="ig-element-<?php echo esc_attr( IG_Pb_Helper_Shortcode::shortcode_name( $shortcode ) ); ?>">
		<div class="ig-pb-form-container jsn-bootstrap3">
			<div id="modalOptions" class="form-horizontal <?php echo esc_attr( $submodal ); ?>">
	<?php
	if ( ! empty( $params ) ) {
		$params = stripslashes( $params );
		$params = urldecode( $params );
	}
	// elements
	if ( $el_type == 'element' ) {

		// get shortcode class
		$class = IG_Pb_Helper_Shortcode::get_shortcode_class( $shortcode );
		if ( class_exists( $class ) ) {
			global $Ig_Pb;
			$elements = $Ig_Pb->get_elements();
			$instance = isset( $elements['element'][strtolower( $class )] ) ? $elements['element'][strtolower( $class )] : null;

			if ( ! is_object( $instance ) ) {
				$instance = new $class();
			}
			$instance->init_element();

			// Generate default params if they were not posted.
			if ( empty( $params ) ) {
				$params  = $instance->config['shortcode_structure'];
			}

			if ( ! empty( $params ) ) {
				$params = str_replace( '#_EDITTED', '', $params );
				$extract_params = IG_Pb_Helper_Shortcode::extract_params( $params, $shortcode );

				// if have sub-shortcode, extract sub shortcodes content
				if ( ! empty( $instance->config['has_subshortcode'] ) ) {
					$sub_sc_data                         = IG_Pb_Helper_Shortcode::extract_sub_shortcode( $params, true );
					$sub_sc_data                         = apply_filters( 'ig_pb_sub_items_filter', $sub_sc_data, $shortcode, isset ( $_COOKIE['ig_pb_data_for_modal'] ) ? $_COOKIE['ig_pb_data_for_modal'] : '' );
					$extract_params['sub_items_content'] = $sub_sc_data;
				}

				// Set auto title for the subitem if have
				$extract_title   =( isset( $el_title ) && $el_title != __( '(Untitled)', IGPBL ) ) ? $el_title : '';
				// MODIFY $instance->items
				IG_Pb_Helper_Shortcode::generate_shortcode_params( $instance->items, NULL, $extract_params, TRUE, FALSE, $extract_title );

				// if have sub-shortcode, re-generate shortcode structure
				if ( ! empty( $instance->config['has_subshortcode'] ) ) {
					$instance->shortcode_data();
				}
			}

			// get Modal setting box
			$settings      = $instance->items;
			$settings_html = IG_Pb_Objects_Modal::get_shortcode_modal_settings( $settings, $shortcode, $extract_params, $params );
			echo balanceTags( $settings_html );
		}

		?>
        <form id="frm_shortcode_settings" action="" method="post">
            <?php
               // Render the inputs to store element setting data for Copy style feature
                foreach ( $_POST as $k => $v ) {
                    echo '<input type="hidden" id="hid-' . $k .  '" name="' . $k . '" value="' . urlencode( $v ) . '" />';
                }
                echo '<input type="hidden" id="hid-init_tab" name="init_tab" value="styling" />';
            ?>
        </form>
        <?php
    }
	// widgets
	else if ( $el_type == 'widget' ) {
			$instance          = IG_Pb_Helper_Shortcode::extract_widget_params( $params );
			$instance['title'] = isset( $instance['title'] ) ? $instance['title'] : $el_title;

			// generate setting form of widget
			$widget = new $shortcode();
			ob_start();
			$widget->form( $instance );
			$form = ob_get_clean();

			// simplify widget field name
			$exp  = preg_quote( $widget->get_field_name( '____' ) );
			$exp  = str_replace( '____', '(.*? )', $exp );
			$form = preg_replace( '/' . $exp . '/', '$1', $form );

			// simplify widget field id
			$exp  = preg_quote( $widget->get_field_id( '____' ) );
			$exp  = str_replace( '____', '(.*? )', $exp );
			$form = preg_replace( '/' . $exp . '/', '$1', $form );

			// tab and content generate
			$tabs = array();
			foreach ( array( 'content', 'styling' ) as $i => $tab ) {
				$active               = ( $i ++ == 0 ) ? 'active' : '';
				$data_['href']        = "#$tab";
				$data_['data-toggle'] = 'tab';
				$content_             = ucfirst( $tab );
				$tabs[]               = "<li class='$active'>" . IG_Pb_Objects_Modal::tab_settings( 'a', $data_, $content_ ) . '</li>';
			}

			// content
			$contents   = array();
			$contents[] = "<div class='tab-pane active' id='content'><form id='ig-widget-form'>$form</form></div>";
			$contents[] = "<div class='tab-pane' id='styling'>" . IG_Pb_Helper_Shortcode::render_parameter( 'preview' ) . '</div>';

			$output = IG_Pb_Objects_Modal::setting_tab_html( $shortcode, $tabs, $contents, array(), '', array() );

			echo balanceTags( $output );
	}
	?>
				<div id="modalAction" class="ig-pb-setting-tab"></div>
			</div>
			<textarea class="hidden" id="shortcode_content"><?php echo esc_attr( $params ); ?></textarea>
			<textarea class="hidden" id="ig_share_data"></textarea>
			<textarea class="hidden" id="ig_merge_data"></textarea>
			<textarea class="hidden" id="ig_extract_data"></textarea>
			<input type="hidden" id="ig_previewing" value="0" />
			<input id="shortcode_type" type="hidden" value="<?php echo esc_attr( $el_type ); ?>" />
			<input id="shortcode_name" type="hidden" value="<?php echo esc_attr( esc_sql( $_GET['ig_modal_type'] ) ); ?>" />

			<div class="jsn-modal-overlay"></div>
			<div class="jsn-modal-indicator"></div>

			<?php
			// append custom assets/HTML for specific shortcode here
			do_action( 'ig_pb_modal_footer', $shortcode ); ?>
		</div>
	</div>
	<?php
}