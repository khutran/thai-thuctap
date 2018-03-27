<?php
/**
 * @version	$Id$
 * @package	IG PageBuilder
 * @author	 InnoGears Team <support@innogears.com>
 * @copyright  Copyright (C) 2012 innogears.com. All Rights Reserved.
 * @license	GNU/GPL v2 or later http://www.gnu.org/licenses/gpl-2.0.html
 *
 * Websites: http://www.innogears.com
 * Technical Support:  Feedback - http://www.innogears.com
 */

if ( ! class_exists( 'IG_Alert' ) ) :

/**
 * Create Alert element.
 *
 * @package  IG PageBuilder Shortcodes
 * @since    1.0.0
 */
class IG_Alert extends IG_Pb_Shortcode_Element {
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
		$this->config['shortcode'] = strtolower( __CLASS__ );
		$this->config['name']      = __( 'Alert', 				IGPBL );
		$this->config['cat']       = __( 'Typography', 		IGPBL );
		$this->config['icon']      = 'icon-paragraph-text';

		// Define exception for this shortcode
		$this->config['exception'] = array(
			'frontend_assets' => array(
				// Bootstrap 3
				'ig-pb-bootstrap-css',
				'ig-pb-bootstrap-js',
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
					'name'  => __( 'Alert Content', IGPBL ),
					'id'    => 'alert_content',
						'type'  => 'editor',
					'role'  => 'content',
					'rows'  => '12',
					'std'   => IG_Pb_Helper_Type::lorem_text(),
					'tooltip' => __( 'Set content of elementm', IGPBL ),
				),
			),
			'styling' => array(
				array(
					'type' => 'preview',
				),
				array(
					'name'    => __( 'Style',IGPBL ),
					'id'      => 'alert_style',
					'type'    => 'select',
					'class'   => 'input-sm',
					'std'     => IG_Pb_Helper_Type::get_first_option( IG_Pb_Helper_Type::get_alert_type() ),
					'options' => IG_Pb_Helper_Type::get_alert_type(),
					'tooltip' => __( 'Set style for item', IGPBL )
				),
				array(
					'name'		=> __( 'Allow to close', 		IGPBL ),
					'id'		=> 'alert_close',
					'type'		=> 'radio',
					'std'		=> 'no',
					'options'	=> array( 'yes' => __( 'Yes', IGPBL ), 'no' => __( 'No', IGPBL ) ),
					'tooltip'	=> __( 'Whether the customers can close the alert or not', IGPBL ),
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
		$html_element  = '';
		$arr_params	   = ( shortcode_atts( $this->config['params'], $atts ) );
		$alert_style   = ( ! $arr_params['alert_style'] ) ? '' : $arr_params['alert_style'];
		$alert_close   = ( ! $arr_params['alert_close'] || $arr_params['alert_close'] == 'no' ) ? '' : '<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>';
		$alert_dismis  = ( ! $arr_params['alert_close'] || $arr_params['alert_close'] == 'no' ) ? '' : ' alert-dismissable';
		$html_element .= "<div class='alert {$alert_style}{$alert_dismis}'>";
		$html_element .= $alert_close;
		$html_element .= ( ! $content ) ? '' : $content;
		$html_element .= '</div>';
		return $this->element_wrapper( $html_element, $arr_params );
	}
}

endif;
