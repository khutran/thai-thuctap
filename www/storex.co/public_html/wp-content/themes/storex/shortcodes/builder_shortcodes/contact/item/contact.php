<?php

if ( ! class_exists( 'IG_Item_Contact' ) ) {
	/**
	 * Create child Tab element
	 *
	 * @package  IG PageBuilder Shortcodes
	 * @since    1.0.0
	 */
	class IG_Item_Contact extends IG_Pb_Shortcode_Child {

		public function __construct() {
			parent::__construct();
		}

		/**
		 * DEFINE configuration information of shortcode
		 */
		public function element_config() {
			$this->config['shortcode'] = strtolower( __CLASS__ );
			$this->config['exception'] = array(
				'data-modal-title' => esc_html__( 'Configure social Button', 'storex' ),

			);
		}

		/**
		 * DEFINE setting options of shortcode
		 */
		public function element_items() {
			$this->items = array(
				'Notab' => array(
					array(
						'name'  => esc_html__( 'Title', 'storex' ),
						'id'    => 'title',
						'type'  => 'text_field',
						'class' => 'input-sm',
						'role'  => 'title',
                        'tooltip' => esc_html__( 'Set text for tooltip on hover', 'storex' ),
					),
					array(
						'name'    => esc_html__( 'URL for button', 'storex' ),
						'id'      => 'url',
						'type'    => 'text_field',
						'role'    => 'url',
						'class'   => 'input-sm',
						'std'     => esc_html__( 'http://', 'storex' ),
						'tooltip' => esc_html__( 'Enter an url', 'storex' )
					),
					array(
						'name'    => esc_html__( 'Icon for button', 'storex' ),
						'id'      => 'icon',
						'type'    => 'text_field',
						'role'    => 'icon',
						'class'   => 'input-sm',
						'std'     => esc_html__( 'Example: facebook, twitter, gplus', 'storex' ),
						'tooltip' => esc_html__( 'Enter name of icon according to FontAwesome icons', 'storex' )
					),
				)
			);
		}

		/**
		 * DEFINE shortcode content
		 *
		 * @param type $atts
		 * @param type $content
		 */
		public function element_shortcode_full( $atts = null, $content = null ) {
            $arr_params = ( shortcode_atts( $this->config['params'], $atts ) );
			extract( $arr_params );
			return "<a href='{$url}' target='_blank' rel='nofollow' title='{$title}'><i class='fa fa-{$icon}'></i></a><!--separate-->";
		}

	}

}
