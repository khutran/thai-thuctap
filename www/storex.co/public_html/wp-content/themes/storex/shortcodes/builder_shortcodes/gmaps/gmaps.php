<?php


if ( ! class_exists( 'IG_Gmaps' ) ) {

	class IG_Gmaps extends IG_Pb_Shortcode_Parent {

		public function __construct() {
			parent::__construct();
		}

		public function element_config() {
			$this->config['shortcode'] = strtolower( __CLASS__ );
			$this->config['shortcode'] = strtolower( __CLASS__ );
			$this->config['name']      = esc_html__( 'Google Maps',  'storex' );
			$this->config['cat']       = esc_html__( 'Typography',  'storex' );
			$this->config['icon']      = 'icon-paragraph-text';
			$this->config['exception'] = array(
				'default_content'  => esc_html__( 'Google Maps',  'storex' ),
				'require_js'       => array( ),
				'data-modal-title' => esc_html__( 'Google Map',  'storex' )
			);
            $this->config['edit_using_ajax'] = true;
		}

		public function element_items() {
			$this->items = array(
				'content' => array(
					array(
						'name'    => esc_html__( 'Element Title',  'storex' ),
						'id'      => 'el_title',
						'type'    => 'text_field',
						'class'   => 'jsn-input-xxlarge-fluid',
						'std'     => '',
						'role'    => 'title',
						'tooltip' => esc_html__( 'Set title for current element for identifying easily',  'storex' )
					),


                    array(
                        'name'       => esc_html__( 'Width', 'storex' ),
                        'id'         => 'm_width',
                        'type'       => 'text_field',
                        'type_input' => 'number',
                        'class'      => 'input-mini',
                        'std'        => '100%',

                        'tooltip'    => esc_html__( 'Set maps width', 'storex' )
                    ),

                    array(
                        'name'       => esc_html__( 'Height', 'storex' ),
                        'id'         => 'm_height',
                        'type'       => 'text_field',
                        'type_input' => 'number',
                        'class'      => 'input-mini',
                        'std'        => '513px',

                        'tooltip'    => esc_html__( 'Set maps width', 'storex' )
                    ),


                    array(
                        'name'       => esc_html__( 'Latitude', 'storex' ),
                        'id'         => 'gm_lat',
                        'type'       => 'text_append',
                        'type_input' => 'number',
                        'class'      => 'input-mini',
                        'std'        => '41.895465',
                        'validate'   => 'number',
                        'tooltip'    => esc_html__( 'Set Latitude coordinate', 'storex' )
                    ),

                    array(
                        'name'       => esc_html__( 'Longitude', 'storex' ),
                        'id'         => 'gm_long',
                        'type'       => 'text_append',
                        'type_input' => 'number',
                        'class'      => 'input-mini',
                        'std'        => '12.482324',
                        'validate'   => 'number',
                        'tooltip'    => esc_html__( 'Set Longitude coordinate', 'storex' )
                    ),

                    array(
                        'id'      => 'b_text',
                        'name'    => esc_html__( 'Bubble Text',  'storex' ),
                        'type'    => 'text_field',
                        'class'   => 'jsn-input-medium-fluid',
                        'std'     => '',
                        'tooltip' => esc_html__( 'Set the pricing table info',  'storex' )
                    ),

                    array(
                        'name'       => esc_html__( 'Default Zoom', 'storex' ),
                        'id'         => 'd_zoom',
                        'type'       => 'text_append',
                        'type_input' => 'number',
                        'class'      => 'input-mini',
                        'std'        => '15',
                        'validate'   => 'number',
                        'tooltip'    => esc_html__( 'Set defaul zoom value coordinate', 'storex' )
                    ),


                    array(
                        'name'       => esc_html__( 'Zoom Control ON/OFF', 'storex' ),
                        'id'         => 'z_onoff',
                        'type'       => 'radio',
                        'std'        => 'off',
                        'options'    => array( 'yes' => esc_html__( 'Yes', 'storex' ), 'no' => esc_html__( 'No', 'storex' ) ),

                    ),

                    array(
                        'name'       => esc_html__( 'Pan Control ON/OFF', 'storex' ),
                        'id'         => 'p_onoff',
                        'type'       => 'radio',
                        'std'        => 'off',
                        'options'    => array( 'yes' => esc_html__( 'Yes', 'storex' ), 'no' => esc_html__( 'No', 'storex' ) ),

                    ),

                    array(
                        'name'       => esc_html__( 'Stree View Control ON/OFF', 'storex' ),
                        'id'         => 'st_onoff',
                        'type'       => 'radio',
                        'std'        => 'off',
                        'options'    => array( 'yes' => esc_html__( 'Yes', 'storex' ), 'no' => esc_html__( 'No', 'storex' ) ),

                    ),

                    array(
                        'name'       => esc_html__( 'Map Type Control ON/OFF', 'storex' ),
                        'id'         => 'mt_onoff',
                        'type'       => 'radio',
                        'std'        => 'off',
                        'options'    => array( 'yes' => esc_html__( 'Yes', 'storex' ), 'no' => esc_html__( 'No', 'storex' ) ),

                    ),

                    array(
                        'name'       => esc_html__( 'Overview Map Control ON/OFF', 'storex' ),
                        'id'         => 'om_onoff',
                        'type'       => 'radio',
                        'std'        => 'off',
                        'options'    => array( 'yes' => esc_html__( 'Yes', 'storex' ), 'no' => esc_html__( 'No', 'storex' ) ),

                    ),

                    array(
                        'id'      => 'address',
                        'name'    => esc_html__( 'Address',  'storex' ),
                        'type'    => 'text_field',
                        'class'   => 'jsn-input-medium-fluid',
                        'std'     =>'',
                        'tooltip' => esc_html__( 'Set desired address to show on the map',  'storex' )
                    ),



				),
				'styling' => array(

				)
			);
		}

		public function element_shortcode_full( $atts = null, $content = null ) {
			$html_element = '';
			$arr_params   = shortcode_atts( $this->config['params'], $atts );
			extract( $arr_params );

            $zoomControl = $z_onoff == 'on' ? 'true' : 'false';

            $panControl = $p_onoff == 'on' ? 'true' : 'false';

            $streetViewControl = $st_onoff == 'on' ? 'true' : 'false';

            $mapTypeControl = $mt_onoff == 'on' ? 'true' : 'false';

            $overviewMapControl = $om_onoff == 'on' ? 'true' : 'false';

            $html_element = "[gmap data=\"lat: {$gm_lat}, lng: {$gm_long}, bubbletext:{$b_text}, zoom: {$d_zoom}, zoomControl : {$zoomControl}, panControl : {$panControl}, streetViewControl : {$streetViewControl}, mapTypeControl: {$mapTypeControl}, overviewMapControl: {$overviewMapControl}, address:{$address} \" width=\"{$m_width}\" height=\"{$m_height}\"]";

            return $html_element;

		}

	}

}