<?php
/**
 * @version    $Id$
 * @package    Content Builder
 * @author
 * @copyright  Copyright (C) 2012  . All Rights Reserved.
 * @license    GNU/GPL v2 or later http://www.gnu.org/licenses/gpl-2.0.html
 *
 * Websites: http://www.
 * Technical Support:  Feedback - http://www.
 */

if ( ! class_exists( 'IG_Row' ) ) :

class IG_Row extends IG_Pb_Shortcode_Layout {

	public function __construct() {
		parent::__construct();
	}

	/**
	 * DEFINE configuration information of shortcode
	 */
	function element_config() {
		$this->config['shortcode'] = strtolower( __CLASS__ );

		// Define exception for this shortcode
		$this->config['exception'] = array(
			'admin_assets' => array(
				// Shortcode initialization
				'row.js',
			),
		);

		// Use Ajax to speed up element settings modal loading speed
		$this->config['edit_using_ajax'] = true;
	}

	/**
	 * contain setting items of this element ( use for modal box )
	 *
	 */
	function element_items() {
		$this->items = array(
			'Notab' => array(
				array(
					'name'    => __( 'Width', IGPBL ),
					'id'      => 'width',
					'type'    => 'radio',
					'std'     => 'boxed',
					'options' => array( 'boxed' => __( 'Boxed', IGPBL ), 'full' => __( 'Full', IGPBL ) , 'no_cont' => __('Without Container', IGPBL))
				),

                array(
                    'name'    => __( 'Row Class', plumtree ),
                    'id'      => 'r_class',
                    'type'    => 'select',
                    'std'     => 'none',
                    'options' => array(
                        'none' => __( 'None', plumtree ),
                        'yellow-box' => __( 'Yellow Box', plumtree ),
                        'grey-box' => __( 'Grey Box', plumtree ),
                        'white-box' => __( 'White Box', plumtree ),
                        's-off' => __( 'S-Off', plumtree ),
                        'green-box' => __( 'Green Box', plumtree ),
                        'blue-box' => __( 'Blue Box', plumtree ),
                        'y-box' => __( 'Y-Box', plumtree ),
                        'l-box' => __( 'L-Box', plumtree ),
                    ),
                ),

                array(
                    'name'    => __( 'Text Alignment', plumtree ),
                    'id'      => 't_class',
                    'type'    => 'select',
                    'std'     => 'none',
                    'options' => array(
                        'none' => __( 'None', plumtree ),
                        'text-align-left' => __( 'Left', plumtree ),
                        'text-align-right' => __( 'Right', plumtree ),
                        'text-align-center' => __( 'Center', plumtree )
                    ),
                ),

				array(
					'name'       => __( 'Background', IGPBL ),
					'id'         => 'background',
					'type'       => 'select',
					'std'        => 'none',
					'class'		 => 'input-sm',
					'options'    => array(
						'none'     => __( 'None', IGPBL ),
						'solid'    => __( 'Solid Color', IGPBL ),
						'gradient' => __( 'Gradient Color', IGPBL ),
						'pattern'  => __( 'Pattern', IGPBL ),
						'image'    => __( 'Image', IGPBL )
					),
					'has_depend' => '1',
				),
				array(
					'name' => __( 'Solid Color', IGPBL ),
					'type' => array(
						array(
							'id'           => 'solid_color_value',
							'type'         => 'text_field',
							'class'        => 'input-small',
							'std'          => '#FFFFFF',
							'parent_class' => 'combo-item',
						),
						array(
							'id'           => 'solid_color_color',
							'type'         => 'color_picker',
							'std'          => '#ffffff',
							'parent_class' => 'combo-item',
						),
					),
					'container_class' => 'combo-group',
					'dependency'      => array( 'background', '=', 'solid' ),
				),
				array(
					'name'       => __( 'Gradient Color', IGPBL ),
					'id'         => 'gradient_color',
					'type'       => 'gradient_picker',
					'std'        => '0% #FFFFFF,100% #000000',
					'dependency' => array( 'background', '=', 'gradient' ),
				),
				array(
					'id'              => 'gradient_color_css',
					'type'            => 'text_field',
					'std'             => '',
					'input_type'      => 'hidden',
					'container_class' => 'hidden',
					'dependency'      => array( 'background', '=', 'gradient' ),
				),
				array(
					'name'       => __( 'Gradient Direction', IGPBL ),
					'id'         => 'gradient_direction',
					'type'       => 'select',
					'std'        => 'vertical',
					'options'    => array( 'vertical' => __( 'Vertical', IGPBL ), 'horizontal' => __( 'Horizontal', IGPBL ) ),
					'dependency' => array( 'background', '=', 'gradient' ),
				),
				array(
					'name'       => __( 'Pattern', IGPBL ),
					'id'         => 'pattern',
					'type'       => 'select_media',
					'std'        => '',
					'class'      => 'jsn-input-large-fluid',
					'dependency' => array( 'background', '=', 'pattern' ),
				),
				array(
					'name'    => __( 'Repeat', IGPBL ),
					'id'      => 'repeat',
					'type'    => 'radio_button_group',
					'std'     => 'full',
					'options' => array(
						'full'       => __( 'Full', IGPBL ),
						'vertical'   => __( 'Vertical', IGPBL ),
						'horizontal' => __( 'Horizontal', IGPBL ),
					),
					'dependency' => array( 'background', '=', 'pattern' ),
				),
				array(
					'name'       => __( 'Image', IGPBL ),
					'id'         => 'image',
					'type'       => 'select_media',
					'std'        => '',
					'class'      => 'jsn-input-large-fluid',
					'dependency' => array( 'background', '=', 'image' ),
				),
				array(
					'name'    => __( 'Stretch', IGPBL ),
					'id'      => 'stretch',
					'type'    => 'radio_button_group',
					'std'     => 'none',
					'options' => array(
						'none'    => __( 'None', IGPBL ),
						'full'    => __( 'Full', IGPBL ),
						'cover'   => __( 'Cover', IGPBL ),
						'contain' => __( 'Contain', IGPBL ),
					),
					'dependency' => array( 'background', '=', 'pattern' ),
				),
				array(
					'name'       => __( 'Position', IGPBL ),
					'id'         => 'position',
					'type'       => 'radio',
					'label_type' => 'image',
					'dimension'  => array( 23, 23 ),
					'std'        => 'center center',
					'options'    => array(
						'left top'      => array( 'left top' ),
						'center top'    => array( 'center top' ),
						'right top'     => array( 'right top', 'linebreak' => true ),
						'left center'   => array( 'left center' ),
						'center center' => array( 'center center' ),
						'right center'  => array( 'right center', 'linebreak' => true ),
						'left bottom'   => array( 'left bottom' ),
						'center bottom' => array( 'center bottom' ),
						'right bottom'  => array( 'right bottom' ),
					),
					'dependency' => array( 'background', '=', 'image' ),
				),
				array(
					'name'       => __( 'Enable Paralax', IGPBL ),
					'id'         => 'paralax',
					'type'       => 'radio',
					'std'        => 'no',
					'options'    => array( 'yes' => __( 'Yes', IGPBL ), 'no' => __( 'No', IGPBL ) ),
					'dependency' => array( 'background', '=', 'pattern__#__image' ),
				),

                array(
                    'name'       => __( 'Relative', IGPBL ),
                    'id'         => 'relative',
                    'type'       => 'radio',
                    'std'        => 'no',
                    'options'    => array( 'yes' => __( 'Yes', IGPBL ), 'no' => __( 'No', IGPBL ) ),

                ),

                array(
                    'name'       => __( 'Video background', IGPBL ),
                    'id'         => 'video_b',
                    'type'       => 'radio',
                    'std'        => 'no',
                    'options'    => array( 'no' => __( 'No', IGPBL ), 'yes' => __( 'Yes', IGPBL ) ),
                    'has_depend' => '1',

                ),

                array(
                    'name'       => __( 'Video Screenshot', IGPBL ),
                    'id'         => 'v_scrn',
                    'type'       => 'select_media',
                    'std'        => '',
                    'class'      => 'jsn-input-large-fluid',
                    'dependency' => array( 'video_b', '=', 'yes' ),
                ),

                array(
                    'name'       => __( 'Video WebM', IGPBL ),
                    'id'         => 'v_webm',
                    'type'       => 'select_media',
                    'filter_type' => 'video',
                    'media_type'  => 'video',
                    'std'        => '',
                    'class'      => 'jsn-input-large-fluid',
                    'dependency' => array( 'video_b', '=', 'yes' ),
                ),

                array(
                    'name'       => __( 'Video OGV', IGPBL ),
                    'id'         => 'v_ogv',
                    'type'       => 'select_media',
                    'filter_type' => 'video',
                    'media_type'  => 'video',
                    'std'        => '',
                    'class'      => 'jsn-input-large-fluid',
                    'dependency' => array( 'video_b', '=', 'yes' ),
                ),

                array(
                    'name'       => __( 'Video MP4', IGPBL ),
                    'id'         => 'v_mp4',
                    'type'       => 'select_media',
                    'filter_type' => 'video',
                    'media_type'  => 'video',
                    'std'        => '',
                    'class'      => 'jsn-input-large-fluid',
                    'dependency' => array( 'video_b', '=', 'yes' ),
                ),

				array(
					'name' => __( 'Border', IGPBL ),
					'type' => array(
						array(
							'id'           => 'border_width_value_',
							'type'         => 'text_append',
							'input_type'   => 'number',
							'class'        => 'input-mini',
							'std'          => '0',
							'append'       => 'px',
							'validate'     => 'number',
							'parent_class' => 'combo-item',
						),
						array(
							'id'           => 'border_style',
							'type'         => 'select',
							'class'        => 'input-sm',
							'std'          => 'solid',
							'options'      => IG_Pb_Helper_Type::get_border_styles(),
							'parent_class' => 'combo-item',
						),
						array(
							'id'           => 'border_color',
							'type'         => 'color_picker',
							'std'          => '#000',
							'parent_class' => 'combo-item',
						),
					),
					'container_class' => 'combo-group',
				),
				array(
					'name'               => __( 'Padding', IGPBL ),
					'container_class'    => 'combo-group',
					'id'                 => 'div_padding',
					'type'               => 'margin',
					'extended_ids'       => array( 'div_padding_top', 'div_padding_bottom', 'div_padding_right', 'div_padding_left' ),
					'div_padding_top'    => array( 'std' => '30' ),
					'div_padding_bottom' => array( 'std' => '30' ),
					'div_padding_right'  => array( 'std' => '30' ),
					'div_padding_left'   => array( 'std' => '30' ),
				),
				array(
					'name'    => __( 'CSS Class', IGPBL ),
					'id'      => 'css_suffix',
					'type'    => 'text_field',
					'std'     => __( '', IGPBL ),
					'tooltip' => __( 'Add custom css class for the wrapper div of this element', IGPBL ),
				),
				array(
					'name'    => __( 'ID', IGPBL ),
					'id'      => 'id_wrapper',
					'type'    => 'text_field',
					'std'     => __( '', IGPBL ),
					'tooltip' => __( 'Add custom id for the wrapper div of this element', IGPBL ),
				),
			)
		);
	}

	/**
	 *
	 * @param type $content		: inner shortcode elements of this row
	 * @param type $shortcode_data : not used
	 * @return string
	 */
	public function element_in_pgbldr( $content = '', $shortcode_data = '' ) {
		if ( empty($content) ) {
			$column = new IG_Column();
			$column_html = $column->element_in_pgbldr();
			$column_html = $column_html[0];
		} else {
			$column_html = IG_Pb_Helper_Shortcode::do_shortcode_admin( $content );
		}
		if ( empty($shortcode_data) )
			$shortcode_data = $this->config['shortcode_structure'];
		// remove [/ig_row][ig_column...] from $shortcode_data
		$shortcode_data = explode( '][', $shortcode_data );
		$shortcode_data = $shortcode_data[0] . ']';

		// Remove empty value attributes of shortcode tag.
		$shortcode_data	= preg_replace( '/\[*([a-z_]*[\n\s\t]*=[\n\s\t]*"")/', '', $shortcode_data );

		$custom_style = IG_Pb_Utils_Placeholder::get_placeholder( 'custom_style' );
		$row[] = '<div class="jsn-row-container ui-sortable row-fluid shortcode-container" ' . $custom_style . '>
						<textarea class="hidden" data-sc-info="shortcode_content" name="shortcode_content[]" >' . $shortcode_data . '</textarea>
						<div class="jsn-iconbar left">
							<a href="javascript:void(0);" title="' . __( 'Move Up', IGPBL ) . '" class="jsn-move-up disabled"><i class="icon-chevron-up"></i></a>
							<a href="javascript:void(0);" title="' . __( 'Move Down', IGPBL ) . '" class="jsn-move-down disabled"><i class=" icon-chevron-down"></i></a>
						</div>
						<div class="ig-row-content">
						' . $column_html . '
						</div>
						<div class="jsn-iconbar jsn-vertical">
							<a href="javascript:void(0);" class="add-container" title="' . __( 'Add column', IGPBL ) . '"><i class="icon-plus"></i></a>
							<a href="javascript:void(0);" title="Edit row" data-shortcode="' . $this->config['shortcode'] . '" class="element-edit row" data-use-ajax="' . ( $this->config['edit_using_ajax'] ? 1 : 0 ) . '"><i class="icon-pencil"></i></a>
							<a href="javascript:void(0);" class="item-delete row" title="' . __( 'Delete row', IGPBL ) . '"><i class="icon-trash"></i></a>
						</div>
						<textarea class="hidden" name="shortcode_content[]" >[/' . $this->config['shortcode'] . ']</textarea>
					</div>';
		return $row;
	}

	/**
	 * get params & structure of shortcode
	 */
	public function shortcode_data() {
		$this->config['params'] = IG_Pb_Helper_Shortcode::generate_shortcode_params( $this->items, null, null, false, true );
		$this->config['shortcode_structure'] = IG_Pb_Helper_Shortcode::generate_shortcode_structure( $this->config['shortcode'], $this->config['params'] );
	}

	/**
	 * define shortcode structure of element
	 */
	function element_shortcode( $atts = null, $content = null ) {
		$extra_class = $style = $custom_script = '';
		if ( isset( $atts ) && is_array( $atts ) ) {
			$arr_styles = array();

			switch ( $atts['width'] ) {
				case 'full':
					$extra_class = 'ig_fullwidth';
					// some overwrite css to enable row full width
					$script = "$('body').addClass('ig-full-width');";
					$custom_script = IG_Pb_Helper_Functions::script_box( $script );

					$arr_styles[] = '-webkit-box-sizing: content-box;-moz-box-sizing: content-box;box-sizing: content-box;width: 100%;';
					break;
				case 'boxed':
					///$arr_styles[] = "width: 100%;";
					break;
			}
			$background = '';
			switch ( $atts['background'] ) {
				case 'none':
					if ( $atts['width'] == 'full' )
						$background = 'background: none;';
					break;
				case 'solid':
					$solid_color = $atts['solid_color_value'];
					$background  = "background-color: $solid_color;";
					break;
				case 'gradient':
					$background = $atts['gradient_color_css'];
					break;
				case 'pattern':
					$pattern_img     = $atts['pattern'];
					$pattern_repeat  = $atts['repeat'];
					$pattern_stretch = $atts['stretch'];
					$background = "background-image:url(\"$pattern_img\");";
					switch ( $pattern_repeat ) {
						case 'full':
							$background_repeat = 'repeat';
							break;
						case 'vertical':
							$background_repeat = 'repeat-y';
							break;
						case 'horizontal':
							$background_repeat = 'repeat-x';
							break;
					}
					$background .= "background-repeat:$background_repeat;";

					switch ( $pattern_stretch ) {
						case 'none':
							$background_size = '';
							break;
						case 'full':
							$background_size = '100% 100%';
							break;
						case 'cover':
							$background_size = 'cover';
							break;
						case 'contain':
							$background_size = 'contain';
							break;
					}
					$background .= ! empty( $background_size ) ? "background-size:$background_size;" : '';

					break;
				case 'image':
					$image = $atts['image'];
					$image_position = $atts['position'];

					$background = "background-image:url(\"$image\");background-position:$image_position;";
					break;
			}
			$arr_styles[] = $background;

			if ( isset( $atts['paralax']) && $atts['paralax'] == 'yes' )
				$arr_styles[] = 'background-attachment:fixed;';

            if ( isset( $atts['relative']) && $atts['relative'] == 'yes' ){
                $arr_styles[] = 'position:relative;';
                $rel_class = 'rel';
            } else {
                $rel_class = '';
            }

			if ( isset( $atts['border_width_value_'] ) && intval( $atts['border_width_value_'] ) ) {
				$border       = array();
				$border[]     = $atts['border_width_value_'] . 'px';
				$border[]     = $atts['border_style'];
				$border[]     = $atts['border_color'];
				$border       = implode( ' ', $border );
				$arr_styles[] = "border-top:$border; border-bottom:$border;";
			}

			$arr_styles[] = "padding-top:{$atts['div_padding_top']}px;";
			$arr_styles[] = "padding-bottom:{$atts['div_padding_bottom']}px;";

			if ( $atts['width'] != 'full' ) {
				$arr_styles[] = "padding-left:{$atts['div_padding_left']}px;";
				$arr_styles[] = "padding-right:{$atts['div_padding_right']}px;";
			}

			$arr_styles = implode( '', $arr_styles );
			$style = ! empty( $arr_styles ) ? "style='$arr_styles'" : '';
		}
		$extra_class .= ! empty ( $atts['css_suffix'] ) ? ' ' . esc_attr( $atts['css_suffix'] ) : '';
		$extra_class  = ltrim( $extra_class, ' ' );
		$extra_id     = ! empty ( $atts['id_wrapper'] ) ? ' ' . esc_attr( $atts['id_wrapper'] ) : '';
		$extra_id     = ! empty ( $extra_id ) ? "id='" . ltrim( $extra_id, ' ' ) . "'" : '';

        $out = '';



        if ( isset($atts['background']) && ($atts['background'] != 'none') ) $out = "<div class=' {$rel_class} stellar ".@( $atts['r_class'] != 'none' ? $atts['r_class'] : "" )." ".@($atts['t_class'] != 'none' ? $atts['t_class'] : "")." ' $style ".( ( isset( $atts['paralax']) && $atts['paralax'] == 'yes' ) ? " data-stellar-background-ratio='0.5' " : "" )." >";
        elseif (isset($atts['r_class']) && ( $atts['r_class'] != 'none' ) ) $out = "<div class='{$rel_class} ".@( $atts['r_class'] != 'none' ? $atts['r_class'] : "" )."  ".@($atts['t_class'] != 'none' ? $atts['t_class'] : "")." ' {$style} >";
        elseif ( isset($atts['video_b']) && ($atts['video_b'] == 'yes') ) $out = "<div class=\"{$rel_class} ".@($atts['t_class'] != 'none' ? $atts['t_class'] : "")." \" data-function=\"videobg\" data-webm=\"{$atts['v_webm']}\" data-mp4=\"
        {$atts['v_mp4']}\" data-ogv=\"{$atts['v_ogv']}\" data-poster=\"{$atts['v_scrn']}\" >";

        if ( @array_key_exists('width', $atts) &&  ( $atts['width'] != 'no_cont' ) ) {

            if (BOOTSTRAP_VERSION == 2) $out .= $custom_script . "<div class=\"container-fluid\">" . "<div $extra_id class='row-fluid $extra_class'>" . IG_Pb_Helper_Shortcode::remove_autop( $content ) . '</div>' . '</div>';
            else $out .= $custom_script . "" . "<div class=\"container $extra_class \"><div $extra_id class='row $extra_class' >" . IG_Pb_Helper_Shortcode::remove_autop( $content ) . '</div></div>' . '';

        } else {

            if ( @$atts['width'] == 'no_cont' ) {
                $content = preg_replace('/\[ig_column span="(\w*)"\]/', '', $content);
                $content = preg_replace("/\[\/ig_column\]/", '', $content);
            }



            $out .= IG_Pb_Helper_Shortcode::remove_autop( do_shortcode($content));
        }


        if ( isset($atts['background']) && ($atts['background'] != 'none') ) $out .= '</div>';
        elseif (isset($atts['r_class']) && ( $atts['r_class'] != 'none' ) )  $out .= '</div>';
        elseif ( isset($atts['video_b']) && ($atts['video_b'] == 'yes') )  $out .= '</div>';

        return $out;

    }
}


endif;
