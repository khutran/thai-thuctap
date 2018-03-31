<?php
/**
 * Plumtree Collapsing Categories
 *
 * Configurable collapsing categories output widget.
 *
 * @author TransparentIdeas
 * @package Plum tree
 * @subpackage Widgets
 * @since 0.01
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

if (class_exists('Woocommerce')) {
	add_action( 'widgets_init', create_function( '', 'register_widget( "pt_collapsing_categories" );' ) );
}

class pt_collapsing_categories extends WP_Widget {

	public function __construct() {
		parent::__construct(
	 		'pt_collapsing_categories', // Base ID
			esc_html__('PT Collapsing Categories', 'storex'), // Name
			array('description' => esc_html__( "Plum Tree special widget. Display configurable accordion with categories on your site.", "storex" ), ) 
		);
	}

	public function form($instance) {
		$defaults = array(
			'title' => 'Categories',
			'cats_count' => false,
			'cats_type' => 'category',
			'sortby' => 'name',
			'order' => 'DESC',
			'exclude_cats' => '',
		);
		$instance = wp_parse_args( (array) $instance, $defaults ); 
	?>

		<p>
		    <label for ="<?php echo $this->get_field_id('title'); ?>"><?php esc_html_e('Title: ','storex'); ?></label>
		    <input type="text" class="widefat"id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" value="<?php echo esc_attr( $instance['title'] ); ?>"/>
		</p>
		<p>
		    <input type="checkbox" name="<?php echo $this->get_field_name('cats_count'); ?>" <?php if (esc_attr( $instance['cats_count'] )) {
		                    echo 'checked="checked"';
		                } ?> class=""  size="4"  id="<?php echo $this->get_field_id('cats_count'); ?>" />
		    <label for ="<?php echo $this->get_field_id('cats_count'); ?>"><?php esc_html_e('Show count for categories','storex'); ?></label>
		</p>
		<p>
		    <label for="<?php echo $this->get_field_id('cats_type'); ?>"><?php esc_html_e('Categories type:','storex'); ?> 
		        <select class='widefat' id="<?php echo $this->get_field_id('cats_type'); ?>" name="<?php echo $this->get_field_name('cats_type'); ?>">
		          <option value='category'<?php echo ($instance['cats_type']=='category')?'selected="selected"':''; ?>><?php esc_html_e('Post Categories', 'storex'); ?></option>
		          <option value='product_cat'<?php echo ($instance['cats_type']=='product_cat')?'selected="selected"':''; ?>><?php esc_html_e('Product Categories', 'storex'); ?></option> 
		        </select>                
		    </label>
		</p>
		<p>
			<label for="<?php echo $this->get_field_id('sortby'); ?>"><?php esc_html_e('Sort by:','storex'); ?> 
		        <select class='widefat' id="<?php echo $this->get_field_id('sortby'); ?>" name="<?php echo $this->get_field_name('sortby'); ?>">
		          <option value='ID'<?php echo ($instance['sortby']=='ID')?'selected':''; ?>><?php esc_html_e('ID', 'storex'); ?></option>
		          <option value='name'<?php echo ($instance['sortby']=='name')?'selected="selected"':''; ?>><?php esc_html_e('Name', 'storex'); ?></option> 
		          <option value='slug'<?php echo ($instance['sortby']=='slug')?'selected="selected"':''; ?>><?php esc_html_e('Slug', 'storex'); ?></option> 
		        </select>                
		    </label>
		</p>
		<p>
		    <label for="<?php echo $this->get_field_id('order'); ?>"><?php esc_html_e('Order:','storex'); ?> 
		        <select class='widefat' id="<?php echo $this->get_field_id('order'); ?>" name="<?php echo $this->get_field_name('order'); ?>">
		          <option value='ASC'<?php echo ($instance['order']=='ASC')?'selected="selected"':''; ?>><?php esc_html_e('Ascending', 'storex'); ?></option>
		          <option value='DESC'<?php echo ($instance['order']=='DESC')?'selected="selected"':''; ?>><?php esc_html_e('Descending', 'storex'); ?></option> 
		        </select>                
		    </label>
		</p>
		<p>
		    <label for ="<?php echo $this->get_field_id('exclude_cats'); ?>"><?php echo esc_html__('Exclude Category (ID): ','storex');
		            ?></label>
		    <input type="text" class="widefat"id="<?php echo $this->get_field_id('exclude_cats'); ?>" name="<?php echo $this->get_field_name('exclude_cats'); ?>" value="<?php echo esc_attr($instance['exclude_cats']); ?>"/>
		    <small><?php esc_html_e('category IDs, separated by commas.', 'storex'); ?></small>
		</p>

	<?php }

	public function update($new_instance, $old_instance) {
		$instance = $old_instance;

		$instance['title'] = strip_tags( $new_instance['title'] );
		$instance['cats_count'] = $new_instance['cats_count'];
		$instance['cats_type'] = strip_tags( $new_instance['cats_type'] );
		$instance['sortby'] = strip_tags( $new_instance['sortby'] );
		$instance['order'] = strip_tags( $new_instance['order'] );
		$instance['exclude_cats'] = strip_tags( $new_instance['exclude_cats'] );

		return $instance;
	}

	public function widget($args, $instance) {
		extract($args);

		$title = apply_filters('widget_title', $instance['title'] );
		$show_count = ( isset($instance['cats_count']) ? $instance['cats_count'] : false );
		$cats_type = ( isset($instance['cats_type']) ? $instance['cats_type'] : 'category' );
		$sortby = ( isset($instance['sortby']) ? $instance['sortby'] : 'name' );
		$order = ( isset($instance['order']) ? $instance['order'] : 'DESC' );
		$exclude_cats = ( isset($instance['exclude_cats']) ? $instance['exclude_cats'] : '' );

		global $wp_query, $post, $product;	

		// Setup Current Category
		$current_cat   = false;
		$cat_ancestors = array();

		if ( is_tax('product_cat') || is_category() ) {
			$current_cat   = $wp_query->queried_object;
			$cat_ancestors = get_ancestors( $current_cat->term_id, $cats_type );
		}
		
		echo $before_widget;
		echo $before_title;
		if ($title) echo $title;
		echo $after_title;				

	    $args = array(
			'orderby'            => $sortby,
			'order'              => $order,
			'style'              => 'list',
			'show_count'         => $show_count,
			'hide_empty'         => true,
			'exclude'            => $exclude_cats,
			'hierarchical'       => 1,
			'title_li'           => '',
			'show_option_none'   => esc_html__( 'No categories', 'storex' ),
			'taxonomy'           => $cats_type,
	    );
	    $args['walker'] = new PT_Cats_List_Walker;
	    $args['current_category'] = ( $current_cat ) ? $current_cat->term_id : '';
		$args['current_category_ancestors'] = $cat_ancestors;

		echo '<ul class="collapse-categories">';

		wp_list_categories( $args );

		echo '</ul>';

        echo $after_widget;
       
    }

}

class PT_Cats_List_Walker extends Walker {

	private $curItem;

	var $tree_type = 'product_cat';
	var $db_fields = array ( 'parent' => 'parent', 'id' => 'term_id', 'slug' => 'slug' );

	/**
	 * @see Walker::start_lvl()
	 * @since 1.0
	 *
	 */
	public function start_lvl( &$output, $depth = 0, $args = array() ) {
		if ( 'list' != $args['style'] )
			return;

		$indent = str_repeat("\t", $depth);
		$data = get_object_vars($this->curItem);
		$parent_id = $data['term_id'];
		
		$output .= "$indent<ul id='children-of-{$parent_id}' class='children collapse";
		if ( $args['current_category_ancestors'] && $args['current_category'] && in_array( $parent_id, $args['current_category_ancestors'] ) ) {
			$output .= ' in'; }
		$output .= "'>\n";
	}

	/**
	 * @see Walker::end_lvl()
	 * @since 1.0
	 */
	public function end_lvl( &$output, $depth = 0, $args = array() ) {
		if ( 'list' != $args['style'] )
			return;

		$indent = str_repeat("\t", $depth);
		$output .= "$indent</ul>\n";
	}

	/**
	 * @see Walker::start_el()
	 * @since 1.0
	 */
	public function start_el( &$output, $cat, $depth = 0, $args = array(), $current_object_id = 0 ) {
		$this->curItem = $cat;

		// Adding extra classes if needed
		$output .= '<li class="cat-item cat-item-' . $cat->term_id;
		if ( $args['current_category'] == $cat->term_id ) {
			$output .= ' current-cat';
		}
		if ( $args['has_children'] && $args['hierarchical'] ) {
			$output .= ' cat-parent';
		}
		if ( $args['current_category_ancestors'] && $args['current_category'] && in_array( $cat->term_id, $args['current_category_ancestors'] ) ) {
			$output .= ' current-cat-parent';
		}
		$output .=  '">';

		/* Get link to category & Adding extra data to cat anchor */
		$term_link = get_term_link( (int) $cat->term_id, $cat->taxonomy );
		if ( is_wp_error( $term_link ) ) {
        	//continue;
    	}
    	$anchor = '';
    	if ( $args['current_category_ancestors'] && $args['current_category'] && in_array( $cat->term_id, $args['current_category_ancestors'] ) ) {
    		$anchor = '<a href="#children-of-'.$cat->term_id.'" class="show-children" data-toggle="collapse" aria-controls="children-of-'. $cat->term_id .'" aria-expanded="true"><span></span></a>';
    	}
    	if ( $args['has_children'] && $args['hierarchical'] ) {
			$anchor = '<a href="#children-of-'.$cat->term_id.'" class="show-children collapsed" data-toggle="collapse" aria-controls="children-of-'. $cat->term_id .'" aria-expanded="false"><span></span></a>';
		}

		$output .=  '<a class="category" href="' . $term_link . '">' . $cat->name . '</a>';

		/* Adding show subcategories button */
		$output .= $anchor;

		/* Adding counter if needed */
		if ( $args['show_count'] ) {
			$output .= ' <span class="count">(' . $cat->count . ')</span>';
		}
	}

	/**
	 * @see Walker::end_el()
	 * @since 1.0
	 */
	public function end_el( &$output, $cat, $depth = 0, $args = array() ) {
		$output .= "</li>\n";
	}

	/**
	 * Traverse elemen ts to create list from elements.
	 *
	 * Display one element if the element doesn't have any children otherwise,
	 * display the element and its children. Will only traverse up to the max
	 * depth and no ignore elements under that depth. It is possible to set the
	 * max depth to include all depths, see walk() method.
	 *
	 * This method shouldn't be called directly, use the walk() method instead.
	 *
	 * @since 1.0
	 */
	public function display_element( $element, &$children_elements, $max_depth, $depth = 0, $args, &$output ) {
		if ( ! $element || 0 === $element->count ) {
			return;
		}
		parent::display_element( $element, $children_elements, $max_depth, $depth, $args, $output );
	}
}
