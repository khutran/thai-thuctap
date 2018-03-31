<?php
/**
 * Cart Page
 *
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     2.3.8
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

wc_print_notices();

do_action( 'woocommerce_before_cart' ); ?>

<form action="<?php echo esc_url( WC()->cart->get_cart_url() ); ?>" method="post">

<?php do_action( 'woocommerce_before_cart_table' ); ?>

<table class="shop_table cart" cellspacing="0">
	<thead>
		<tr>
			<th class="product-remove">&nbsp;</th>
			<th class="product-thumbnail"><?php esc_html_e( 'Product', 'storex' ); ?></th>
			<th class="product-name"></th>
			<th class="product-avail"><?php esc_html_e( 'Avail', 'storex' ); ?></th>
			<th class="product-price"><?php esc_html_e( 'Price', 'storex' ); ?></th>
			<th class="product-quantity"><?php esc_html_e( 'Quantity', 'storex' ); ?></th>
			<th class="product-subtotal"><?php esc_html_e( 'Total', 'storex' ); ?></th>
			
		</tr>
	</thead>
	<tbody>
		<?php do_action( 'woocommerce_before_cart_contents' ); ?>

		<?php
		foreach ( WC()->cart->get_cart() as $cart_item_key => $cart_item ) {
			$_product     = apply_filters( 'woocommerce_cart_item_product', $cart_item['data'], $cart_item, $cart_item_key );
			$product_id   = apply_filters( 'woocommerce_cart_item_product_id', $cart_item['product_id'], $cart_item, $cart_item_key );

			if ( $_product && $_product->exists() && $cart_item['quantity'] > 0 && apply_filters( 'woocommerce_cart_item_visible', true, $cart_item, $cart_item_key ) ) {
				?>
				<tr class="<?php echo esc_attr( apply_filters( 'woocommerce_cart_item_class', 'cart_item', $cart_item, $cart_item_key ) ); ?>">

				<td class="product-remove">
						<?php
							echo apply_filters( 'woocommerce_cart_item_remove_link', sprintf( '<a href="%s" class="remove" title="%s">&times;</a>', esc_url( WC()->cart->get_remove_url( $cart_item_key ) ), esc_html__( 'Remove this item', 'storex' ) ), $cart_item_key );
						?>
				</td>
				
					<td class="product-thumbnail">
						<?php
							$thumbnail = apply_filters( 'woocommerce_cart_item_thumbnail', $_product->get_image(), $cart_item, $cart_item_key );

							if ( ! $_product->is_visible() )
								echo $thumbnail;
							else
								printf( '<a href="%s">%s</a>', $_product->get_permalink( $cart_item ), $thumbnail );
						?>
					</td>

					<td class="product-name">
						<?php
							if ( ! $_product->is_visible() )
								echo apply_filters( 'woocommerce_cart_item_name', $_product->get_title(), $cart_item, $cart_item_key ) . '&nbsp;';
							else
								echo apply_filters( 'woocommerce_cart_item_name', sprintf( '<a href="%s">%s </a>', $_product->get_permalink( $cart_item ), $_product->get_title() ), $cart_item, $cart_item_key );

							// Meta data
							echo WC()->cart->get_item_data( $cart_item );

               				// Backorder notification
               				if ( $_product->backorders_require_notification() && $_product->is_on_backorder( $cart_item['quantity'] ) )
               					echo '<p class="backorder_notification">' . esc_html__( 'Available on backorder', 'storex' ) . '</p>';
						?>
					</td>

					<td class="avail"><i class="fa fa-check"></i></td>
					
					<td class="product-price">
						<?php
							echo apply_filters( 'woocommerce_cart_item_price', WC()->cart->get_product_price( $_product ), $cart_item, $cart_item_key );
						?>
					</td>

					<td class="product-quantity">
						<?php
							if ( $_product->is_sold_individually() ) {
								$product_quantity = sprintf( '1 <input type="hidden" name="cart[%s][qty]" value="1" />', $cart_item_key );
							} else {
								$product_quantity = woocommerce_quantity_input( array(
									'input_name'  => "cart[{$cart_item_key}][qty]",
									'input_value' => $cart_item['quantity'],
									'max_value'   => $_product->backorders_allowed() ? '' : $_product->get_stock_quantity(),
									'min_value'   => '0'
								), $_product, false );
							}

							echo apply_filters( 'woocommerce_cart_item_quantity', $product_quantity, $cart_item_key );
						?>
					</td>

					<td class="product-subtotal">
						<?php
							echo apply_filters( 'woocommerce_cart_item_subtotal', WC()->cart->get_product_subtotal( $_product, $cart_item['quantity'] ), $cart_item, $cart_item_key );
						?>
						<div class="clear"></div>
					</td>
					
				</tr>
				<?php
			}
		}

		do_action( 'woocommerce_cart_contents' );
		?>
		<tr class="coupon-cart_totals">
			<td colspan="4"  class="actions">

				<?php if ( WC()->cart->coupons_enabled() ) { ?>
					<div class="coupon">

						<label for="coupon_code"><?php esc_html_e( 'Coupon', 'storex' ); ?></label> <input type="text" name="coupon_code" class="input-text" id="coupon_code" value="" placeholder="<?php esc_html_e( 'Coupon code', 'storex' ); ?>" /> <input type="submit" class="button" name="apply_coupon" value="<?php esc_html_e( 'Apply Coupon', 'storex' ); ?>" />

						<?php do_action( 'woocommerce_cart_coupon' ); ?>

					</div>
				<?php } ?>
				
				<?php do_action( 'woocommerce_cart_actions' ); ?>

				<?php wp_nonce_field( 'woocommerce-cart' ); ?>
			</td>
			
			<td colspan="3" class="cart-totals">
			<?php do_action( 'woocommerce_cart_collaterals' ); ?>
			</td>
		</tr>

		<?php do_action( 'woocommerce_after_cart_contents' ); ?>
	</tbody>
</table>

<?php do_action( 'woocommerce_after_cart_table' ); ?>
		
<div class="update-cart-checkout">
	<?php    $shop_page_url = get_permalink( woocommerce_get_page_id( 'shop' ) );
                if ($shop_page_url) {
                    echo '<a class="button-go-shop" rel="bookmark" href="' . esc_url($shop_page_url) . '">' . esc_html__('Continue Shopping', 'storex') . '</a>';
                }?>
<div class="wc-proceed-to-checkout">
	<?php do_action( 'woocommerce_proceed_to_checkout' ); ?>
</div>

	<input type="submit" class="button update_cart" name="update_cart" value="<?php esc_html_e( 'Update Cart', 'storex' ); ?>" />
</div>

</form>
	<div class="row">
		<div class="cart-collaterals col-xs-12 col-md-6">
			<?php woocommerce_shipping_calculator(); ?>
		</div>
	</div>

<?php do_action( 'woocommerce_after_cart' ); ?>
