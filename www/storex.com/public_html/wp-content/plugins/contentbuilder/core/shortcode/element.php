<?php
/**
 * @version	$Id$
 * @package	IG PageBuilder
 * @author	 InnoGears Team <support@www.innogears.com>
 * @copyright  Copyright (C) 2012 www.innogears.com. All Rights Reserved.
 * @license	GNU/GPL v2 or later http://www.gnu.org/licenses/gpl-2.0.html
 *
 * Websites: http://www.www.innogears.com
 * Technical Support:  Feedback - http://www.www.innogears.com
 */
/*
 * Parent class for normal elements
 */

class IG_Pb_Shortcode_Element extends IG_Pb_Shortcode_Common {

	public function __construct() {
		$this->type = 'element';
		$this->config['el_type'] = 'element';

		$this->element_config();

		// add shortcode
		add_shortcode( $this->config['shortcode'], array( &$this, 'element_shortcode' ) );

	}

	/**
	 * Method to call neccessary functions for initialyzing the backend
	 */
	public function init_element()
	{
		$this->element_items();
		$this->element_items_extra();
		$this->shortcode_data();

		do_action( 'ig_pb_element_init' );

		parent::__construct();

		// enqueue assets for current element in backend (modal setting iframe)
		if ( IG_Pb_Helper_Functions::is_modal_of_element( $this->config['shortcode'] ) ) {
			add_action( 'pb_admin_enqueue_scripts', array( &$this, 'enqueue_assets_modal' ) );
		}

		// enqueue assets for current element in backend (preview iframe)
		if ( IG_Pb_Helper_Functions::is_preview() ) {
			add_action( 'pb_admin_enqueue_scripts', array( &$this, 'enqueue_assets_frontend' ) );
		}
	}

	/**
     * Custom assets for frontend
     */
	public function custom_assets_frontend() {
		// enqueue custom assets here
	}

	/**
     * Enqueue scripts for frontend
     */
	public function enqueue_assets_frontend() {
		IG_Pb_Helper_Functions::shortcode_enqueue_assets( $this, 'frontend_assets', '_frontend' );
	}

	/**
     * Enqueue scripts for modal setting iframe
     *
     * @param type $hook
     */
	public function enqueue_assets_modal( $hook ) {
		IG_Pb_Helper_Functions::shortcode_enqueue_assets( $this, 'admin_assets', '' );
	}

	/**
     * Define configuration information of shortcode
     */
	public function element_config() {

	}

	/**
     * Define setting options of shortcode
     */
	public function element_items() {

	}

	/**
     * Add more options to all elements
     */
	public function element_items_extra() {
		$shotcode_name = $this->config['shortcode'];

		$disable_el = array(
			'name' => __( 'Disable', IGPBL ),
			'id' => 'disabled_el',
			'type' => 'radio',
			'std' => 'no',
			'options' => array( 'yes' => __( 'Yes', IGPBL ), 'no' => __( 'No', IGPBL ) ),
			'wrap_class' => 'form-group control-group hidden clearfix',
		);

		// if not child element
		if ( strpos( $shotcode_name, 'item_' ) === false ) {
			$css_suffix = array(
				'name'    => __( 'CSS Class', IGPBL ),
				'id'      => 'css_suffix',
				'type'    => 'text_field',
				'std'     => __( '', IGPBL ),
				'tooltip' => __( 'Add custom css class for the wrapper div of this element', IGPBL )
			);
			$id_wrapper = array(
				'name'    => __( 'ID', IGPBL ),
				'id'      => 'id_wrapper',
				'type'    => 'text_field',
				'std'     => __( '', IGPBL ),
				'tooltip' => __( 'Add custom id for the wrapper div of this element', IGPBL ),
			);
		}

		// Copy style from other element.
		$style_copy = array(
			'name'    => __( 'Copy Style from...', IGPBL ),
			'id'      => 'copy_style_from',
			'type'    => 'select',
			'options' => array( '0' => __( 'Select element', IGPBL ) ),
			'std'     => __( '0', IGPBL ),
			'tooltip' => __( 'Copy Styling prameters from other same type element', IGPBL ),
		);

		if ( isset ( $this->items['styling'] ) ) {
			$this->items['styling'] = array_merge(
				$this->items['styling'], array(
					$css_suffix,
					$id_wrapper,
					$disable_el,
					// always at the end of array
					array(
						'name'			=> __( 'Margin', IGPBL ),
						'container_class' 	=> 'combo-group',
						'id'			=> 'div_margin',
						'type'			=> 'margin',
						'extended_ids'	=> array( 'div_margin_top', 'div_margin_bottom' ),
						'div_margin_top'	=> array( 'std' => '10' ),
						'div_margin_bottom'	=> array( 'std' => '10' ),
						'margin_elements'	=> 't, b',
						'tooltip' 			=> __( 'Set margin size', 	IGPBL )
					),
				)
			);

			array_unshift( $this->items['styling'], $style_copy );
		} else {
			if ( isset ( $this->items['Notab'] ) ) {
				$this->items['Notab'] = array_merge(
					$this->items['Notab'], array(
						$css_suffix,
						$id_wrapper,
						$disable_el,

					)
				);
			}
		}
	}

	/**
	 * DEFINE html structure of shortcode in Page Builder area
	 *
	 * @param string $content
	 * @param string $shortcode_data: string stores params (which is modified default value) of shortcode
	 * @param string $el_title: Element Title used to identifying elements in IG PageBuilder
	 * @param int $index
	 * @param bool $inlude_sc_structure
	 * @param array $extra_params
	 * Ex:  param-tag=h6&param-text=Your+heading&param-font=custom&param-font-family=arial
	 * @return string
	 */
	public function element_in_pgbldr( $content = '', $shortcode_data = '', $el_title = '', $index = '', $inlude_sc_structure = true, $extra_params = array() ) {
		// Init neccessary data to render element in backend.
		$this->init_element();

		$shortcode		  = $this->config['shortcode'];
		$is_sub_element   = ( isset( $this->config['sub_element'] ) ) ? true : false;
		$parent_shortcode = ( $is_sub_element ) ? str_replace( 'ig_item_', '', $shortcode ) : $shortcode;
		$type			  = ! empty( $this->config['el_type'] ) ? $this->config['el_type'] : 'widget';

		// Empty content if this is not sub element
		if ( ! $is_sub_element )
			$content = '';

		$exception   = isset( $this->config['exception'] ) ? $this->config['exception'] : array();
		$content     = ( isset( $exception['default_content'] ) ) ? $exception['default_content'] : $content;
		$modal_title = '';
		// if is widget
		if ( $type == 'widget' ) {
			global $Ig_Pb_Widgets;
			if ( isset( $Ig_Pb_Widgets[$shortcode] ) && is_array( $Ig_Pb_Widgets[$shortcode] ) && isset( $Ig_Pb_Widgets[$shortcode]['identity_name'] ) ) {
				$modal_title = $Ig_Pb_Widgets[$shortcode]['identity_name'];
				$content     = $this->config['exception']['data-modal-title'] = $modal_title;
			}
		}

		// if content is still empty, Generate it
		if ( empty( $content ) ) {
			if ( ! $is_sub_element )
				$content = ucfirst( str_replace( 'ig_', '', $shortcode ) );
			else {
				if ( isset( $exception['item_text'] ) ) {
					if ( ! empty( $exception['item_text'] ) )
						$content = IG_Pb_Utils_Placeholder::add_placeholder( $exception['item_text'] . ' %s', 'index' );
				} else
					$content = IG_Pb_Utils_Placeholder::add_placeholder( ( __( ucfirst( $parent_shortcode ), IGPBL ) . ' ' . __( 'Item', IGPBL ) ) . ' %s', 'index' );
			}
		}
		$content = ! empty( $el_title ) ? ( $content . ': ' . "<span>$el_title</span>" ) : $content;

		// element name
		if ( $type == 'element' ) {
			if ( ! $is_sub_element )
				$name = ucfirst( str_replace( 'ig_', '', $shortcode ) );
			else
				$name = __( ucfirst( $parent_shortcode ), IGPBL ) . ' ' . __( 'Item', IGPBL );
		}
		else {
			$name = $content;
		}
		if ( empty($shortcode_data) )
			$shortcode_data = $this->config['shortcode_structure'];

		// Process index for subitem element
		if ( ! empty( $index ) ) {
			$shortcode_data = str_replace( '_IG_INDEX_' , $index, $shortcode_data );
		}

		$shortcode_data  = stripslashes( $shortcode_data );
		$element_wrapper = ! empty( $exception['item_wrapper'] ) ? $exception['item_wrapper'] : ( $is_sub_element ? 'li' : 'div' );
		$content_class   = ( $is_sub_element ) ? 'jsn-item-content' : 'ig-pb-element';
		$modal_title     = empty ( $modal_title ) ? ( ! empty( $exception['data-modal-title'] ) ? "data-modal-title='{$exception['data-modal-title']}'" : '' ) : $modal_title;
		$element_type    = "data-el-type='$type'";
		$edit_using_ajax = ! empty( $exception['edit_using_ajax'] ) ? sprintf( "data-using-ajax='%s'", esc_attr( $exception['edit_using_ajax'] ) ) : '';

		$data = array(
			'element_wrapper' => $element_wrapper,
			'modal_title' => $modal_title,
			'element_type' => $element_type,
			'edit_using_ajax' => $edit_using_ajax,
			'name' => $name,
			'shortcode' => $shortcode,
			'shortcode_data' => $shortcode_data,
			'content_class' => $content_class,
			'content' => $content,
			'action_btn' => empty( $exception['action_btn'] ) ? '' : $exception['action_btn'],
		);
		// Merge extra params if it exists.
		if ( ! empty( $extra_params ) ) {
			$data = array_merge( $data, $extra_params );
		}
		$extra = array();
		if ( isset( $this->config['exception']['disable_preview_container'] ) ) {
			$extra = array(
				'has_preview' => FALSE,
			);
		}
		$data = array_merge( $data, $extra );
		$html_preview = IG_Pb_Helper_Functions::get_element_item_html( $data, $inlude_sc_structure );
		return array(
			$html_preview
		);
	}

	/**
	 * DEFINE shortcode content
	 *
	 * @param array $atts
	 * @param string $content
	 */
	public function element_shortcode_full( $atts = null, $content = null ) {

	}

	/**
	 * return shortcode content: if shortcode is disable, return empty
	 *
	 * @param array $atts
	 * @param string $content
	 */
	public function element_shortcode( $atts = null, $content = null ) {
		$this->init_element();

		$prefix = IG_Pb_Helper_Functions::is_preview() ? 'pb_admin' : 'wp';

		// enqueue custom assets at footer of frontend/backend
		add_action( "{$prefix}_footer", array( &$this, 'custom_assets_frontend' ) );

		$arr_params = ( shortcode_atts( $this->config['params'], $atts ) );
		if ( $arr_params['disabled_el'] == 'yes' ) {
			if ( IG_Pb_Helper_Functions::is_preview() ) {
				return ''; //_e( 'This element is deactivated. It will be hidden at frontend', IGPBL );
			}
			return '';
		}

		// enqueue script for current element in frontend
		add_action( 'wp_footer', array( &$this, 'enqueue_assets_frontend' ), 1 );

		// get full shortcode content
		return $this->element_shortcode_full( $atts, $content );
	}

	/**
	 * Wrap output html of a shortcode
	 *
	 * @param array $arr_params
	 * @param string $html_element
	 * @param string $extra_class
	 * @return string
	 */
	public function element_wrapper( $html_element, $arr_params, $extra_class = '', $custom_style = '' ) {
		$shortcode_name = IG_Pb_Helper_Shortcode::shortcode_name( $this->config['shortcode'] );
		// extract margin here then insert inline style to wrapper div
		$styles = array();
		if ( ! empty ( $arr_params['div_margin_top'] ) ) {
			$styles[] = 'margin-top:' . intval( $arr_params['div_margin_top'] ) . 'px';
		}
		if ( ! empty ($arr_params['div_margin_bottom'] ) ) {
			$styles[] = 'margin-bottom:' . intval( $arr_params['div_margin_bottom'] ) . 'px';
		}
		$style = count( $styles ) ? implode( '; ', $styles ) : '';
		if ( ! empty( $style ) || ! empty( $custom_style ) ){
			$style = "style='$style $custom_style'";
		}

		$class        = "jsn-bootstrap3 ig-element-container ig-element-$shortcode_name";
		$extra_class .= ! empty ( $arr_params['css_suffix'] ) ? ' ' . esc_attr( $arr_params['css_suffix'] ) : '';
		$class       .= ! empty ( $extra_class ) ? ' ' . ltrim( $extra_class, ' ' ) : '';
		$extra_id     = ! empty ( $arr_params['id_wrapper'] ) ? ' ' . esc_attr( $arr_params['id_wrapper'] ) : '';
		$extra_id     = ! empty ( $extra_id ) ? "id='" . ltrim( $extra_id, ' ' ) . "'" : '';
		//return "<div $extra_id class='$class' $style>" . $html_element . '</div>';
        return $html_element;
	}

	/**
	 * Define html structure of shortcode in "Select Elements" Modal
	 *
	 * @param string $data_sort The string relates to Provider name to sort
	 * @return string
	 */
	public function element_button( $data_sort = '' ) {
		// Prepare variables
		$type  = 'element';
		$data_value = strtolower( $this->config['name'] );

		$extra = sprintf( 'data-value="%s" data-type="%s" data-sort="%s"', esc_attr( $data_value ), esc_attr( $type ), esc_attr( $data_sort ) );

		return self::el_button( $extra, $this->config );
	}

	/**
     * HTML output for a shortcode in Add Element popover
     *
     * @param string $extra
     * @param array $config
     * @return string
     */
	public static function el_button( $extra, $config ) {
		// Generate icon if necessary
		$icon = '';

		if ( isset( $config['icon'] ) ) {
			$icon = '<i class="jsn-icon16 icon-formfields jsn-' . $config['icon'] . '"></i> ';
		}

		// Generate data-iframe attribute if needed
		$attr = '';

		if ( isset( $config['edit_using_ajax'] ) && $config['edit_using_ajax'] ) {
			$attr = ' data-use-ajax="1"';
		}

		return '<li class="jsn-item"' . ( empty( $extra ) ? '' : ' ' . trim( $extra ) ) . '>
					<button data-shortcode="' . $config['shortcode'] . '" class="shortcode-item btn btn-default"' . $attr . '>
						' . $icon . $config['name'] . '
					</button>
				</li>';
	}

	/**
     * Get params & structure of shortcode
     */
	public function shortcode_data() {
		$params = IG_Pb_Helper_Shortcode::generate_shortcode_params( $this->items, null, null, false, true );
		// add Margin parameter for Not child shortcode
		if ( strpos( $this->config['shortcode'], '_item' ) === false ) {
			$this->config['params'] = array_merge( array( 'div_margin_top' => '10', 'div_margin_bottom' => '10', 'disabled_el' => 'no', 'css_suffix' => '', 'id_wrapper' => '' ), $params );
		}
		else {
			$this->config['params'] = $params;
		}
		$this->config['shortcode_structure'] = IG_Pb_Helper_Shortcode::generate_shortcode_structure( $this->config['shortcode'], $this->config['params'] );
	}

}
