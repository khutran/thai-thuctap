<?php


if ( ! class_exists( 'IG_Mega_Posts' ) ) {

	class IG_Mega_Posts extends IG_Pb_Shortcode_Parent {

		public function __construct() {
			parent::__construct();
		}

		public function element_config() {
			
			$this->config['shortcode'] = strtolower( __CLASS__ );
			$this->config['name']      = esc_html__( 'PT Mega Posts',  'storex' );
			$this->config['exception'] = array(
				'default_content'  => esc_html__( 'PT Mega Posts',  'storex' ),
				'data-modal-title' => esc_html__( 'PT Mega Posts',  'storex' ),
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
                        'name'    => esc_html__( 'Recent Post Output Type', 'storex' ),
                        'id'      => 'recent_type',
                        'type'    => 'select',
                        'std'     => 'recent_post_editor',
                        'options' => array(
                            'recent_post_editor' => esc_html__( 'Recent Post with Editor\'s choice', 'storex') ,
                            'recent_post' => 'Recent Post',
							'editors_choice' => 'Editor Choice',
                        ),
                        'has_depend' => '1',
                    ),
					array(
						'name'       => esc_html__( 'Recent Post Title',  'storex' ),
						'id'         => 'recent_post_title',
						'type'       => 'text_field',
						'std'        => '',
						'dependency' => array( 'recent_type', '=', 'recent_post' ),
					),
					array(
						'name'       => esc_html__( 'Total number of Recent Post to show',  'storex' ),
						'id'         => 'posts_qty',
						'type'       => 'text_append',
						'type_input' => 'number',
						'std'        => '',
						'dependency' => array( 'recent_type', '=', 'recent_post' ),
					),
					array(
						'name'    => esc_html__( 'Recent Post by Category slug',  'storex' ),
						'id'      => 'category',
						'type'    => 'text_field',
						'class'   => 'jsn-input-xxlarge-fluid',
						'std'     => '',
						'tooltip' => esc_html__( 'Enter specific category if needed', 'storex' ),
						'dependency' => array( 'recent_type',  '=', 'recent_post' ),
					),
				
					
					array(
						'name'       => esc_html__( 'Editor\'s Choice Title',  'storex' ),
						'id'         => 'editors_choice_title',
						'type'       => 'text_field',
						'std'        => '',
						'dependency' => array( 'recent_type', '=', 'editors_choice' ),
					),
					array(
						'name'    => esc_html__( 'Editor\'s Choice Image',  'storex' ),
						'id'      => 'editors_choice_img',
						'type'    => 'select_media',
						'std'     => '',
						'class'   => 'jsn-input-large-fluid',
						'tooltip' => esc_html__( 'Choose image',  'storex' ),
						'dependency' => array( 'recent_type', '=', 'editors_choice' ),
					),
					array(
						'name'       => esc_html__( 'Total number of Editor\'s Choice Post to show',  'storex' ),
						'id'         => 'editor_choice_qty',
						'type'       => 'text_append',
						'type_input' => 'number',
						'std'        => '',
						'dependency' => array( 'recent_type', '=', 'editors_choice' ),
					),
					/*array(
						'name' => esc_html__( 'Show "Read More" button',  'storex' ),
						'id'   => 'read_more_button_editor',
						'type' => 'radio',
						'std' => 'yes',
						'options' => array( 'yes' => esc_html__( 'Yes', 'storex' ), 'no' => esc_html__( 'No', 'storex' ) ),
						'dependency' => array( 'recent_type', '=', 'editors_choice' ),
					),*/
					

					array(
						'name'       => esc_html__( 'Recent Post Title',  'storex' ),
						'id'         => 'recent_post_title_',
						'type'       => 'text_field',
						'std'        => '',
						'dependency' => array( 'recent_type', '=', 'recent_post_editor' ),
					),
					array(
						'name'       => esc_html__( 'Total number of Recent Post to show',  'storex' ),
						'id'         => 'posts_qty_',
						'type'       => 'text_append',
						'type_input' => 'number',
						'std'        => '',
						'dependency' => array( 'recent_type', '=', 'recent_post_editor' ),
					),
					array(
						'name'    => esc_html__( 'Recent Post by Category slug',  'storex' ),
						'id'      => 'category_',
						'type'    => 'text_field',
						'class'   => 'jsn-input-xxlarge-fluid',
						'std'     => '',
						'tooltip' => esc_html__( 'Enter specific category if needed', 'storex' ),
						'dependency' => array( 'recent_type',  '=', 'recent_post_editor' ),
					),
					
					array(
						'name'       => esc_html__( 'Editor\'s Choice Title',  'storex' ),
						'id'         => 'editors_choice_title_',
						'type'       => 'text_field',
						'std'        => '',
						'dependency' => array( 'recent_type', '=', 'recent_post_editor' ),
					),
					array(
						'name'       => esc_html__( 'Editor\'s Choice Image',  'storex' ),
						'id'         => 'editors_choice_img_',
						'type'    => 'select_media',
						'std'     => '',
						'class'   => 'jsn-input-large-fluid',
						'tooltip' => esc_html__( 'Choose image',  'storex' ),
						'dependency' => array( 'recent_type', '=', 'recent_post_editor' ),
					),
					array(
						'name'       => esc_html__( 'Total number of Editor\'s Choice Post  to show',  'storex' ),
						'id'         => 'editor_choice_qty_',
						'type'       => 'text_append',
						'type_input' => 'number',
						'std'        => '',
						'dependency' => array( 'recent_type', '=', 'recent_post_editor' ),
					),
					/*array(
						'name'       => esc_html__( 'Show "Read More" button for Editor\'s Choice',  'storex' ),
						'id'         => 'read_more_button_editor_',
						'type' => 'radio',
						'std' => 'yes',
						'options' => array( 'yes' => esc_html__( 'Yes', 'storex' ), 'no' => esc_html__( 'No', 'storex' ) ),
						'dependency' => array( 'recent_type', '=', 'recent_post_editor' ),
					),*/
				),
			);
		}

	public function element_shortcode_full( $atts = null, $content = null ) {

			$arr_params     = shortcode_atts( $this->config['params'], $atts );
			extract( $arr_params );
			
			$container_class = 'pt-mega-posts '.$recent_type;
			$container_class = ( ! empty( $container_class ) ) ? ' class="' . $container_class . '"' : '';

			$html_output='';
			$html_output .= "<div{$container_class}>";

			// Variables
			$recent_posts_title = '';
			$recent_posts_qty = '';
			$recent_posts_cat = '';
			$editors_title = '';
			$editors_img = '';
			$editors_qty = '';
			//$editors_button = '';

			switch ($recent_type) {
				case 'recent_post_editor':
					$recent_posts_title = $recent_post_title_;
					$recent_posts_qty = $posts_qty_;
					$recent_posts_cat = $category_;
					$editors_title = $editors_choice_title_;
					$editors_img = $editors_choice_img_;
					$editors_qty = $editor_choice_qty_;
					//$editors_button = $read_more_button_editor_;
				break;

				case 'recent_post':
					$recent_posts_title = $recent_post_title;
					$recent_posts_qty = $posts_qty;
					$recent_posts_cat = $category;
				break;

				case 'editors_choice':
					$editors_title = $editors_choice_title;
					$editors_img = $editors_choice_img;
					$editors_qty = $editor_choice_qty;
					//$editors_button = $read_more_button_editor;
				break;
			}

			if($recent_type=='recent_post_editor'){
				$html_output .= "[pt-mega-posts recent_type='".$recent_type."' recent_posts_title='".$recent_posts_title."' recent_posts_qty='".$recent_posts_qty."' recent_posts_cat='".$recent_posts_cat."' editors_title='".$editors_title."' editors_img='".$editors_img."' editors_qty='".$editors_qty."']";
			}
			if($recent_type=='recent_post'){
			
				$html_output .= "[pt-mega-posts recent_type='".$recent_type."' recent_posts_title='".$recent_posts_title."' recent_posts_qty='".$recent_posts_qty."' recent_posts_cat='".$recent_posts_cat."']";
			}
			if($recent_type=='editors_choice'){
				$html_output .= "[pt-mega-posts recent_type='".$recent_type."' editors_title='".$editors_title."' editors_img='".$editors_img."' editors_qty='".$editors_qty."']";
			}

			$html_output .= '</div>';

			return $this->element_wrapper( $html_output, $arr_params );
		}
		
	}
}


