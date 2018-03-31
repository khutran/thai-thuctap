<?php
/**
 * Shopping Cart Widget
 *
 * Displays shopping cart widget
 *
 * @author 		WooThemes Extended By TransparentIdeas
 * @category 	Widgets
 * @package 	WooCommerce/Widgets
 * @version 	2.0.1
 * @extends 	WP_Widget
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class PT_Widget_Cart extends WP_Widget {

		/**
	 * constructor
	 *
	 * @access public
	 * @return void
	 */
	function __construct() {
		parent::__construct(
			'woocommerce_widget_cart', // Base ID
			 esc_html__( 'WooCommerce Cart (PlumTree Themed)', 'storex' ), // Name
			array( 'description' => esc_html__( "Display the user's Cart in the sidebar.", 'storex' ),
					'classname' => 'woocommerce widget_shopping_cart',
			) // Args
		);
	}


	/**
	 * widget function.
	 *
	 * @see WP_Widget
	 * @access public
	 * @param array $args
	 * @param array $instance
	 * @return void
	 */
	function widget( $args, $instance ) {
		global $woocommerce;

		extract( $args );

        if (get_option('catalog_mode') == 'on') return;

		if ( is_cart() || is_checkout() ) return;
		
		if( get_option('cart_count') == 'on' ){
			$title = apply_filters('widget_title', empty( $instance['title'] ) ? esc_html__( 'Cart', 'storex' ) : $instance['title'], $instance, $this->id_base );
		}
		
		$hide_if_empty = empty( $instance['hide_if_empty'] ) ? 0 : 1;

		echo $before_widget;
		
		if( get_option('cart_count') == 'on' ){
			$cart_count = '<a class="cart-contents"><span class="count">'. WC()->cart->cart_contents_count.' items - '. WC()->cart->get_cart_subtotal().'</span></a>';
        }
		else $cart_count = '';

        echo '<div class="inner-cart-content">';

		
		echo '<div class="heading"><div class="cart-icon">'.$cart_count_icon.'</div><div class="cart-widget-title">' .$title. '</div>'.$cart_count.'</div>';

		if ( $hide_if_empty )
			echo '<div class="hide_cart_widget_if_empty">';

		
			// Insert cart widget placeholder - code in woocommerce.js will update this on page load
			echo '<div class="widget_shopping_cart_content"></div>';
	

		if ( $hide_if_empty )
			echo '</div>';

		echo '</div>';

		?>

		<?php echo $after_widget;

		if ( $hide_if_empty && sizeof( WC()->cart->get_cart() ) == 0 ) { ?>
            <script type="text/javascript">
				jQuery('.hide_cart_widget_if_empty').closest('.widget_shopping_cart').hide();

				jQuery('body').bind('adding_to_cart', function(){
					jQuery('.hide_cart_widget_if_empty').closest('.widget_shopping_cart').fadeIn();
				});
			</script>
		<?php }
	}


	/**
	 * update function.
	 *
	 * @see WP_Widget->update
	 * @access public
	 * @param array $new_instance
	 * @param array $old_instance
	 * @return array
	 */
	function update( $new_instance, $old_instance ) {
		$instance['title'] = strip_tags( stripslashes( $new_instance['title'] ) );
		$instance['hide_if_empty'] = empty( $new_instance['hide_if_empty'] ) ? 0 : 1;
		return $instance;
	}


	/**
	 * form function.
	 *
	 * @see WP_Widget->form
	 * @access public
	 * @param array $instance
	 * @return void
	 */
	function form( $instance ) {
		$hide_if_empty = empty( $instance['hide_if_empty'] ) ? 0 : 1;
		?>
		<p><label for="<?php echo $this->get_field_id('title'); ?>"><?php esc_html_e( 'Title:', 'storex' ) ?></label>
		<input type="text" class="widefat" id="<?php echo esc_attr( $this->get_field_id('title') ); ?>" name="<?php echo esc_attr( $this->get_field_name('title') ); ?>" value="<?php if (isset ( $instance['title'])) {echo esc_attr( $instance['title'] );} ?>" /></p>

		<p><input type="checkbox" class="checkbox" id="<?php echo esc_attr( $this->get_field_id('hide_if_empty') ); ?>" name="<?php echo esc_attr( $this->get_field_name('hide_if_empty') ); ?>"<?php checked( $hide_if_empty ); ?> />
		<label for="<?php echo $this->get_field_id('hide_if_empty'); ?>"><?php esc_html_e( 'Hide if cart is empty', 'storex' ); ?></label></p>
		<?php
	}

}

function register_pt_widget_cart() {  
    register_widget( 'PT_Widget_Cart' );  
} 

if (class_exists('Woocommerce'))
add_action( 'widgets_init', 'register_pt_widget_cart' );

/**
 * Adding product counter.
 */

function pt_header_add_to_cart_fragment( $fragments ) {
	global $woocommerce;

	ob_start();
	?>
    <?php if ( get_option('cart_count') == 'on' ) : ?>
	<span class="count-icon"><?php echo(WC()->cart->cart_contents_count)?></span>
    <a class="cart-contents" href="<?php echo WC()->cart->get_cart_url(); ?>" title="<?php esc_html_e('View your shopping cart', 'storex'); ?>"><?php echo sprintf(esc_html(_n('%d item', '%d items', WC()->cart->cart_contents_count, 'storex')), WC()->cart->cart_contents_count);?> - <?php echo WC()->cart->get_cart_total(); ?></a>
	<?php endif; ?>
    <?php
	$fragments['a.cart-contents'] = ob_get_clean();
	return $fragments;
}

if (class_exists('Woocommerce'))
add_filter('add_to_cart_fragments', 'pt_header_add_to_cart_fragment');

