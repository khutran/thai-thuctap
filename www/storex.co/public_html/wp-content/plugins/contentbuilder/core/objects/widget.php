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
if ( ! class_exists( 'IG_Pb_Objects_Widget' ) ) {

	class IG_Pb_Objects_Widget extends WP_Widget {

		/**
		 * constructor
		 *
		 * @access public
		 * @return void
		 */
		public function __construct() {
			parent::__construct(
		 		'ig_widget_pagebuilder', // Base ID
				__( 'PageBuilder Element', IGPBL ), // Name
				array('description' => __( 'Presentation of any PageBuilder element', IGPBL ),
					  'classname' => 'ig-widget-pagebuilder',
				)
			);
		}

		/**
		 * widget function
		 *
		 * @see WP_Widget::widget()
		 * @access public
		 * @param array $args
		 * @param array $instance
		 * @return void
		 */
		function widget( $args, $instance ) {
			extract( $args );
			$title = $shortcode = '';
			// process shortcode
			if ( isset( $instance['ig_widget_shortcode'] ) ) {
				$shortcode = $instance['ig_widget_shortcode'];
				if ( ! $title ) {
					$str_title = substr( $shortcode, strpos( $shortcode, 'el_title=--quote--' ) );
					$str_title = str_replace( 'el_title=--quote--', '', $str_title );
					$title     = substr( $str_title, 0, strpos( $str_title, '--quote--' ) );
				}
				$shortcode = str_replace( '--quote--', '"', $shortcode );
				$shortcode = str_replace( '--open_square--', '[', $shortcode );
				$shortcode = str_replace( '--close_square--', ']', $shortcode );
			}
			if ( ! $title ) {
				global $Ig_Pb;
				$elements = $Ig_Pb->get_elements();
				if ( isset( $elements['element'] ) ) {
					foreach ( $elements['element'] as $idx => $element ) {
						// don't show sub-shortcode
						if ( ! isset( $element->config['name'] ) )
							continue;
						if ( isset( $instance['ig_element'] ) && $element->config['shortcode'] == $instance['ig_element'] ) {
							$title = $element->config['name'];
						}
					}
				}
			}
			// process widget title
			$title = apply_filters( 'widget_title', empty($instance['ig_element'] ) ? __( 'PageBuilder Element', IGPBL ) : $title, $instance, $this->id_base );
			echo balanceTags( $before_widget );
			if ( $title ) {
				echo balanceTags( $before_title . $title . $after_title );
			}
			echo '<div class="jsn-bootstrap3">';
			echo balanceTags( do_shortcode( $shortcode ) );
			echo '</div>';
			echo balanceTags( $after_widget );
		}

		/**
		 * update pagebuilder widget element
		 *
		 * @see WP_Widget::update()
		 */
		function update( $new_instance, $old_instance ) {
			$instance                        = $old_instance;
			$instance['ig_element']          = strip_tags( $new_instance['ig_element'] );
			$instance['ig_widget_shortcode'] = $new_instance['ig_widget_shortcode'];

			return $instance;
		}

		/**
		 * form function.
		 *
		 * @see WP_Widget::form()
		 * @access public
		 * @param array $instance
		 * @return void
		 */
		function form( $instance ) {
			// Default
			$instance            = wp_parse_args( (array ) $instance, array( 'ig_element' => '', 'ig_widget_shortcode' => '' ) );
			$title               = '';
			$selected_value      = esc_attr( $instance['ig_element'] );
			$ig_widget_shortcode = $instance['ig_widget_shortcode'];

			global $Ig_Pb;
			$elements      = $Ig_Pb->get_elements();
			$elements_html = array();
			if ( $elements ) {
				foreach ( $elements['element'] as $idx => $element ) {
					// don't show sub-shortcode
					if ( ! isset( $element->config['name'] ) )
						continue;
					if ( $element->config['shortcode'] == $selected_value ) {
						$elements_html[] = '<option value="' . $element->config['shortcode'] . '" selected="selected">' . $element->config['name'] . '</option>';
						$title           = $element->config['name'];
					} else {
						$elements_html[] = '<option value="' . $element->config['shortcode'] . '">' . $element->config['name'] . '</option>';
					}
				}
			}
			?>
			<div class="jsn-bootstrap3">

				<div class="ig-widget-setting">
			<?php
			if ( ! $elements ) {
				echo '<p>' . sprintf( __( 'No elements have been created yet!  ', IGPBL ) ) . '</p>';
				return;
			}
			?>
					<label for="<?php echo esc_attr( $this->get_field_id( 'ig_element' ) ); ?>"><?php _e( 'Element', IGPBL ) ?></label>
					<div class="form-group control-group clearfix combo-group ig-widget-box">
						<div class="controls">
							<div class="combo-item">
								<select class="ig_widget_select_elm" id="<?php echo esc_attr( $this->get_field_id( 'ig_element' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'ig_element' ) ); ?>">
			<?php
			// shortcode elements
			foreach ( $elements_html as $idx => $element ) {
				echo balanceTags( $element );
			}
			?>
								</select>
							</div>
							<div class="combo-item">
								<a id="ig_widget_edit_btn" class="ig_widget_edit_btn btn btn-icon" data-shortcode="<?php echo esc_attr( $selected_value ) ?>"><i class="icon-pencil"></i><i class="jsn-icon16 jsn-icon-loading" id="ig-widget-loading" style="display:none"></i></a>
							</div>
							<input class="ig_shortcode_widget" type="hidden" id="<?php echo esc_attr( $this->get_field_id( 'ig_widget_shortcode' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'ig_widget_shortcode' ) ); ?>" value="<?php echo esc_attr( $ig_widget_shortcode ); ?>" />
							<div class="jsn-section-content jsn-style-light hidden" id="form-design-content">
								<div class="ig-pb-form-container jsn-layout">
									<input type="hidden" id="ig-select-media" value="" />
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
			<?php
		}

	}

}
