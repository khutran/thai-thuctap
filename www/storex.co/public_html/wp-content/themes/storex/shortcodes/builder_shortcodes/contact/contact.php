<?php

if ( ! class_exists( 'IG_Contact' ) ) :

class IG_Contact extends IG_Pb_Shortcode_Parent {

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
		$this->config['name'] = esc_html__( 'PT Member Contact', 'storex' );
		$this->config['has_subshortcode'] = 'IG_Item_' . str_replace( 'IG_', '', __CLASS__ );
        $this->config['edit_using_ajax'] = true;
        $this->config['exception'] = array(
			'default_content'  => esc_html__( 'PT Member Contact',  'storex' ),
			'data-modal-title' => esc_html__( 'PT Member Contact',  'storex' ),
		);
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
					'name'    => esc_html__( 'Element Title', 'storex' ),
					'id'      => 'el_title',
					'type'    => 'text_field',
					'class'   => 'input-sm',
					'std'     => esc_html__( 'Team Contact', 'storex' ),
					'role'    => 'title',
					'tooltip' => esc_html__( 'Set title for current element for identifying easily', 'storex' )
				),
				array(
					'name'    => esc_html__( 'Image File', 'storex' ),
					'id'      => 'image_file',
					'type'    => 'select_media',
					'std'     => '',
					'class'   => 'jsn-input-large-fluid',
					'tooltip' => esc_html__( 'Choose image', 'storex' )
				),
				array(
					'name'    => esc_html__( 'Team Member Name', 'storex' ),
					'id'      => 'name',
					'type'    => 'text_field',
					'class'   => 'input-sm',
				),
				array(
					'name'    => esc_html__( 'Team Member Occupation', 'storex' ),
					'id'      => 'occupation',
					'type'    => 'text_field',
					'class'   => 'input-sm',
				),
				array(
					'name'    => esc_html__( 'Team Member Short Biography', 'storex' ),
					'id'      => 'biography',
					'type'    => 'text_field',
					'class'   => 'input-sm',
				),
				array(
					'name'          => esc_html__( 'Buttons', 'storex' ),
					'id'            => 'button_items',
					'type'          => 'group',
					'shortcode'     => ucfirst( __CLASS__ ),
					'sub_item_type' => $this->config['has_subshortcode'],
					'sub_items'     => array(
						array( 'std' => '' ),
					),
					'tooltip' 		=> esc_html__( 'Add social network to your contact', 'storex' )
				),

			),
			
			'styling' => array(
				array(
					'name'    => esc_html__( 'Hover Effect',  'storex' ),
					'id'      => 'hover_eff',
					'type'    => 'radio',
					'std'     => 'hover',
					'options' => array( 'hover' => esc_html__( 'Hover',  'storex' ), ' ' => esc_html__( 'Without Hover',  'storex' ) ),
                    'tooltip' => esc_html__( 'Set hover effect',  'storex' ),
				),

			),

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
		$arr_params     = shortcode_atts( $this->config['params'], $atts );
		extract( $arr_params );
		$html_output = '';

		// Container Styles
		$container_class = 'pt-member-contact '.$hover_eff.'';
		$container_class = ( ! empty( $container_class ) ) ? ' class="' . $container_class . '"' : '';

		// Main Elements
		$image = '';
		if ( $image_file ) {
			$image = "<div class='contact-img-wrapper'><div class='background'></div><img src='{$image_file}' alt='{$name}' /></div>";
		}
		$heading = '';
		if ( $name ) {
			$heading = "<h3>{$name}</h3>";
		}
		$sub_heading = '';
		if ( $occupation ) {
			$sub_heading = "<span>{$occupation}</span>";
		}
		$short_bio = '';
		if ( $biography ) {
			$short_bio = "<div class='short-biography'><p>{$biography}</p>";
		}

		$sub_shortcode  = IG_Pb_Helper_Shortcode::remove_autop( $content );
		$items          = explode( '<!--separate-->', $sub_shortcode );
		$items          = array_filter( $items );

		if ($items) {
			$buttons    = "" . implode( '', $items ) . '';
			$social_contacts =  "<div class='contact-btns'>".$buttons."</div></div>";
		}

		// Shortcode output
		$html_output .= "<div{$container_class}>";
		$html_output .= $image;
		$html_output .= "<div class='text-wrapper'>";
		$html_output .= $heading.$sub_heading.$short_bio.$social_contacts;
		$html_output .= "</div></div>";

		return $this->element_wrapper( $html_output, $arr_params );
	}
}

endif;
