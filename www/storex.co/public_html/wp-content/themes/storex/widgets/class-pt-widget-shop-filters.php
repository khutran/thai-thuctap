<?php
/**
 * storex Shop Filters
 *
 * Shop filters output widget.
 *
 * @author TransparentIdeas
 * @package Plum tree
 * @subpackage Widgets
 * @since 0.01
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

add_action( 'widgets_init', create_function( '', 'register_widget( "pt_shop_filters_widget" );' ) );

class pt_shop_filters_widget extends WP_Widget {
	
	public function __construct() {
		parent::__construct(
	 		'pt_shop_filters_widget', // Base ID
			esc_html__('PT Shop Filters', 'storex'), // Name
			array('description' => esc_html__( "Storex special widget. Woocommerce shop filters based on attributes of your products.", 'storex' ), ) 
		);
	}

	public function form($instance) {
		$defaults = array(
			'title' => '',
			'show-count' => false,
			'dropdown-mode' => false,
		);
		$instance = wp_parse_args( (array) $instance, $defaults );
	?>
		<p>
			<label for="<?php echo $this->get_field_id('title'); ?>"><?php esc_html_e( 'Title: ', 'storex' ) ?></label>
			<input class="widefat" id="<?php echo esc_attr( $this->get_field_id('title') ); ?>" name="<?php echo esc_attr( $this->get_field_name('title') ); ?>" type="text" value="<?php echo esc_attr( $instance['title'] ); ?>" />
		</p>
		<p>
			<label for="<?php echo $this->get_field_id( 'attribute' ); ?>"><?php esc_html_e( 'Attribute:', 'storex' ) ?></label>
			<select id="<?php echo esc_attr( $this->get_field_id( 'attribute' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'attribute' ) ); ?>">
				<?php
				$attribute_taxonomies = wc_get_attribute_taxonomies();
				if ( $attribute_taxonomies )
					foreach ( $attribute_taxonomies as $tax )
						if ( taxonomy_exists( wc_attribute_taxonomy_name( $tax->attribute_name ) ) )
							echo '<option value="' . $tax->attribute_name . '" ' . selected( ( isset( $instance['attribute'] ) && $instance['attribute'] == $tax->attribute_name ), true, false ) . '>' . $tax->attribute_label . '</option>';
				?></select>
		</p>
		<p>
            <input type="checkbox" class="checkbox" id="<?php echo $this->get_field_id('show-count'); ?>" name="<?php echo $this->get_field_name('show-count'); ?>"<?php checked( (bool) $instance['show-count'] ); ?> />
            <label for="<?php echo $this->get_field_id('show-count'); ?>"><?php esc_html_e( 'Show products count?', 'storex' ); ?></label>
        </p>
		<p>
            <input type="checkbox" class="checkbox" id="<?php echo $this->get_field_id('dropdown-mode'); ?>" name="<?php echo $this->get_field_name('dropdown-mode'); ?>"<?php checked( (bool) $instance['dropdown-mode'] ); ?> />
            <label for="<?php echo $this->get_field_id('dropdown-mode'); ?>"><?php esc_html_e( 'Show as dropdown?', 'storex' ); ?></label>
        </p>
	<?php
	}

	public function update($new_instance, $old_instance) {
		$instance = $old_instance;

		$instance['title'] = strip_tags( $new_instance['title'] );
		$instance['attribute'] = stripslashes( $new_instance['attribute'] );
		$instance['show-count'] = $new_instance['show-count'];
		$instance['dropdown-mode'] = $new_instance['dropdown-mode'];
		return $instance;
	}

	public function widget($args, $instance) {

		global $woocommerce, $wp_query;
		
		extract($args);

		if ( ! is_post_type_archive( 'product' ) && ! is_tax( get_object_taxonomies( 'product' ) ) )
			return;

		$title 			= apply_filters('widget_title', $instance['title'], $instance, $this->id_base);
		$taxonomy 		= wc_attribute_taxonomy_name($instance['attribute']);
		$taxonomy_label	= $instance['attribute'];
		$show_count     = ( isset($instance['show-count']) ? $instance['show-count'] : false );
		$dropdown_mode  = ( isset($instance['dropdown-mode']) ? $instance['dropdown-mode'] : false );

		if ( ! taxonomy_exists( $taxonomy ) )
			return;

		$terms = get_terms( $taxonomy, array( 'hide_empty' => '1' ) );

		// Get id's of displayed products
		$product_list = $wp_query->posts; 
		$product_ids = array();
		foreach ($product_list as $product) {
		   $product_ids[] += $product->ID;
		}

		// Output data
		if ( count( $terms ) == 0 ) {
			echo $before_widget;
			echo '<p>'.esc_html__('No attributes specified', 'storex').'</p>';
			echo $after_widget;
		}
		elseif ( count( $terms ) > 0 ) {

			$before_filters = $before_widget;

			if ($dropdown_mode) {
				$before_filters .= '<div class="dropdown-filters">';
			}

			if ( empty( $title ) && !$dropdown_mode ) {
				$before_filters .=  $before_title . esc_html__('Shop by ', 'storex') .'<span class="filter-name">'. $taxonomy_label .'</span>'. $after_title;
			}
			elseif ( ! empty( $title ) ) {
				$before_filters .=  $before_title . $title . $after_title;
			}
			$str = mb_strtolower($taxonomy_label);

			$before_filters .=  '<ul data-isotope="filters" class="filters-group" data-filter-group="'.$str.'">';
			$before_filters .=  '<li data-filter="" class="filter is-checked"><span class="bullet"></span>All</li>';						

			$filters = '';
			foreach ( $terms as $term ) {

				// Get count based on current view - uses transients
				$transient_name = 'wc_ln_count_' . md5( sanitize_key( $taxonomy ) . sanitize_key( $term->term_taxonomy_id ) );
				if ( false === ( $_products_in_term = get_transient( $transient_name ) ) ) {
					$_products_in_term = get_objects_in_term( $term->term_id, $taxonomy );
					set_transient( $transient_name, $_products_in_term, YEAR_IN_SECONDS );
				}

				$count = sizeof( array_intersect( $_products_in_term, $product_ids ) );

				if ( $count > 0 ) {
					if (!$show_count) { $additioal_class = ' grid'; } else { $additioal_class = ''; }
					$filters .= '<li class="filter'.$additioal_class.'" data-filter=".'.$term->slug.'">';
					$filters .= '<span class="bullet"></span>';
					$filters .= '<span>'.$term->name.'</span>';
					if ($show_count) {
						$filters .= '<span class="counter">'.$count.'</span>';
					}
					$filters .= '</li>';	
				}

			}

			$after_filters = '</ul>';
			if ($dropdown_mode) {
				$after_filters .= '</div>';
			}
			$after_filters .= $after_widget;

			if ($filters !== '') {
				echo $before_filters.$filters.$after_filters;
			}

		}

	}
}
