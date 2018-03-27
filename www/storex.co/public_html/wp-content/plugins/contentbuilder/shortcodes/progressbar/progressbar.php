<?php
/**
 * @version    $Id$
 * @package    IG PageBuilder
 * @author     InnoGears Team <support@innogears.com>
 * @copyright  Copyright (C) 2012 innogears.com. All Rights Reserved.
 * @license    GNU/GPL v2 or later http://www.gnu.org/licenses/gpl-2.0.html
 *
 * Websites: http://www.innogears.com
 * Technical Support:  Feedback - http://www.innogears.com
 */

if ( ! class_exists( 'IG_Progressbar' ) ) :

/**
 * Create Progress Bar Element
 *
 * @package  IG PageBuilder Shortcodes
 * @since    1.0.0
 */
class IG_Progressbar extends IG_Pb_Shortcode_Parent {
	/**
	 * Constructor
	 *
	 * @return  void
	 */
	public function __construct() {
		parent::__construct();
	}

	/**
	 * Configure shortcode.
	 *
	 * @return  void
	 */
	public function element_config() {
		$this->config['shortcode']        = strtolower( __CLASS__ );
		$this->config['name']             = __( 'Progress Bar', IGPBL );
		$this->config['cat']              = __( 'Typography', IGPBL );
		$this->config['icon']             = 'icon-paragraph-text';
		$this->config['has_subshortcode'] = 'IG_Item_' . str_replace( 'IG_', '', __CLASS__ );

		// Define exception for this shortcode
		$this->config['exception'] = array(
			'default_content'  => __( 'Progress Bar', IGPBL ),
			'data-modal-title' => __( 'Progress Bar', IGPBL ),

			'admin_assets' => array(
				// Shortcode initialization
				'progressbar.js',
			),

			'frontend_assets' => array(
				// Bootstrap 3
				'ig-pb-bootstrap-css',
				'ig-pb-bootstrap-js',

				// Font IcoMoon
				'ig-pb-font-icomoon-css',

				// Shortcode style
				'progressbar_frontend.css',
			),
		);

		// Use Ajax to speed up element settings modal loading speed
		$this->config['edit_using_ajax'] = true;
	}

	/**
	 * Define shortcode settings.
	 *
	 * @return  void
	 */
	public function element_items() {
		$this->items = array(
			'content' => array(
				array(
					'name'    => __( 'Element Title', IGPBL ),
					'id'      => 'el_title',
					'type'    => 'text_field',
					'class'   => 'input-sm',
					'std'     => __( '', IGPBL ),
					'role'    => 'title',
					'tooltip' => __( 'Set title for current element for identifying easily', IGPBL )
				),
				array(
					'id'            => 'progress_bar_items',
					'type'          => 'group',
					'shortcode'     => ucfirst( __CLASS__ ),
					'sub_item_type' => $this->config['has_subshortcode'],
					'sub_items'     => array(
						array( 'std' => __( '', IGPBL ) ),
						array( 'std' => __( '', IGPBL ) ),
						array( 'std' => __( '', IGPBL ) ),
					),
				),
			),
			'styling' => array(
				array(
					'type' => 'preview',
				),
				array(
					'name'    => __( 'Presentation', IGPBL ),
					'id'      => 'progress_bar_style',
					'type'    => 'select',
					'class'   => 'input-sm',
					'std'     => IG_Pb_Helper_Type::get_first_option( IG_Pb_Helper_Type::get_progress_bar_style() ),
					'options' => IG_Pb_Helper_Type::get_progress_bar_style(),
					'tooltip' => __( 'Presentation', IGPBL )
				),
				array(
					'name'    => __( 'Show Icon', IGPBL ),
					'id'      => 'progress_bar_show_icon',
					'type'    => 'radio',
					'std'     => 'yes',
					'options' => array( 'yes' => __( 'Yes', IGPBL ), 'no' => __( 'No', IGPBL ) ),
					'tooltip' => __( 'Show selected icon', IGPBL )
				),
				array(
					'name'    => __( 'Show Title', IGPBL ),
					'id'      => 'progress_bar_show_title',
					'type'    => 'radio',
					'std'     => 'yes',
					'options' => array( 'yes' => __( 'Yes', IGPBL ), 'no' => __( 'No', IGPBL ) ),
					'tooltip' => __( 'Show Title', IGPBL )
				),
				array(
					'name'    => __( 'Show Percentage', IGPBL ),
					'id'      => 'progress_bar_show_percent',
					'type'    => 'radio',
					'std'     => 'yes',
					'options' => array( 'yes' => __( 'Yes', IGPBL ), 'no' => __( 'No', IGPBL ) ),
					'tooltip' => __( 'Show Percentage', IGPBL )
				),
				array(
					'name'    => __( 'Make Active', IGPBL ),
					'id'      => 'progress_bar_stack_active',
					'type'    => 'radio',
					'std'     => 'no',
					'options' => array( 'yes' => __( 'Yes', IGPBL ), 'no' => __( 'No', IGPBL ) ),
					'tooltip' => __( 'Make Active', IGPBL )
				),
			)
		);
	}

	/**
	 * Generate HTML code from shortcode content.
	 *
	 * @param   array   $atts     Shortcode attributes.
	 * @param   string  $content  Current content.
	 *
	 * @return  string
	 */
	public function element_shortcode_full( $atts = null, $content = null ) {
		$arr_params   = shortcode_atts( $this->config['params'], $atts );
		$html_element = '';
		if ( $arr_params['progress_bar_stack_active'] == 'yes' ) {
			$content = str_replace( 'pbar_item_style="solid"', 'pbar_item_style="striped"', $content );
		}

		$sub_shortcode = IG_Pb_Helper_Shortcode::remove_autop( $content );
		$items         = explode( '<!--seperate-->', $sub_shortcode );
		// remove empty element
		$items         = array_filter( $items );
		$initial_open  = ( ! isset( $initial_open ) || $initial_open > count( $items ) ) ? 1 : $initial_open;
		foreach ( $items as $idx => $item ) {
			$open        = ( $idx + 1 == $initial_open ) ? 'in' : '';
			$items[$idx] = $item;
		}
		$sub_shortcode = implode( '', $items );

		$sub_htmls     = do_shortcode( $sub_shortcode );
		if ( $arr_params['progress_bar_show_icon'] == 'no' ) {
			$pattern   = '\\[(\\[?)(icon)(?![\\w-])([^\\]\\/]*(?:\\/(?!\\])[^\\]\\/]*)*?)(?:(\\/)\\]|\\](?:([^\\[]*+(?:\\[(?!\\/\\2\\])[^\\[]*+)*+)\\[\\/\\2\\])?)(\\]?)';
			$sub_htmls = preg_replace( "/$pattern/s", '', $sub_htmls );
		} else {
			$sub_htmls = str_replace( '[icon]', '', $sub_htmls );
			$sub_htmls = str_replace( '[/icon]', '', $sub_htmls );
		}
		if ( $arr_params['progress_bar_show_title'] == 'no' ) {
			$pattern   = '\\[(\\[?)(text)(?![\\w-])([^\\]\\/]*(?:\\/(?!\\])[^\\]\\/]*)*?)(?:(\\/)\\]|\\](?:([^\\[]*+(?:\\[(?!\\/\\2\\])[^\\[]*+)*+)\\[\\/\\2\\])?)(\\]?)';
			$sub_htmls = preg_replace( "/$pattern/s", '', $sub_htmls );
		} else {
			$sub_htmls = str_replace( '[text]', '', $sub_htmls );
			$sub_htmls = str_replace( '[/text]', '', $sub_htmls );
		}
		if ( $arr_params['progress_bar_show_percent'] == 'no' ) {
			$pattern   = '\\[(\\[?)(percentage)(?![\\w-])([^\\]\\/]*(?:\\/(?!\\])[^\\]\\/]*)*?)(?:(\\/)\\]|\\](?:([^\\[]*+(?:\\[(?!\\/\\2\\])[^\\[]*+)*+)\\[\\/\\2\\])?)(\\]?)';
			$sub_htmls = preg_replace( "/$pattern/s", '', $sub_htmls );
		} else {
			$sub_htmls = str_replace( '[percentage]', '', $sub_htmls );
			$sub_htmls = str_replace( '[/percentage]', '', $sub_htmls );
		}
		if ( $arr_params['progress_bar_show_percent'] == 'no' AND $arr_params['progress_bar_show_title'] == 'no' AND $arr_params['progress_bar_show_icon'] == 'no' ) {
			$pattern   = '\\[(\\[?)(sub_content)(?![\\w-])([^\\]\\/]*(?:\\/(?!\\])[^\\]\\/]*)*?)(?:(\\/)\\]|\\](?:([^\\[]*+(?:\\[(?!\\/\\2\\])[^\\[]*+)*+)\\[\\/\\2\\])?)(\\]?)';
			$sub_htmls = preg_replace( "/$pattern/s", '', $sub_htmls );
		}
		$script = '<script type="text/javascript">
	(function($) {
		$(document).ready(function() {
			$(".progress-bar" ).each(function () {
				bar_width = $(this).attr("aria-valuenow");

				$(this).width(bar_width + "%");
			});
		});
	})(jQuery);
</script>';
		if ( $arr_params['progress_bar_style'] == 'stacked' ) {
			$sub_htmls   = str_replace( '{active}', '', $sub_htmls );
			$active      = ( $arr_params['progress_bar_stack_active'] == 'yes' ) ? ' progress-striped active' : '';
			$stacked 	 = ' stacked';
			$html_titles = '';
			$pattern     = '\\[(\\[?)(sub_content)(?![\\w-])([^\\]\\/]*(?:\\/(?!\\])[^\\]\\/]*)*?)(?:(\\/)\\]|\\](?:([^\\[]*+(?:\\[(?!\\/\\2\\])[^\\[]*+)*+)\\[\\/\\2\\])?)(\\]?)';
			preg_match_all( "/$pattern/s", $sub_htmls, $matches );
			$sub_htmls   = preg_replace( "/$pattern/s", '', $sub_htmls );
			foreach ( $matches as $i => $items ) {
				if ( is_array( $items ) ) {
					foreach ( $items as $j => $item ) {
						if ( $item != '' AND strpos( $item, '[sub_content]' ) !== false ) {
							$item        = str_replace( '[sub_content]', '', $item );
							$item        = str_replace( '[/sub_content]', '', $item );
							$html_titles .= $item;
						}
					}
				}
			}
			$html_element = $html_titles;
			$html_element .= "<div class='progress{$active}{$stacked}'>";
			$html_element .= $sub_htmls;
			$html_element .= '</div>';
		} else {
			$sub_htmls = str_replace( '[sub_content]', '', $sub_htmls );
			$sub_htmls = str_replace( '[/sub_content]', '', $sub_htmls );
			if ( $arr_params['progress_bar_stack_active'] == 'yes' ) {
				$sub_htmls = str_replace( '{active}', ' active', $sub_htmls );
			} else {
				$sub_htmls = str_replace( '{active}', '', $sub_htmls );
			}
			$html_element = $sub_htmls;
		}

		return $this->element_wrapper( $html_element . $script, $arr_params );
	}
}

endif;
