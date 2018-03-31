<?php
/**
 * Shipping Methods Display
 *
 * In 2.1 we show methods per package. This allows for multiple methods per order if so desired.
 *
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     2.3.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

?>
<tr class="shipping">
	<th><?php
		if ( $show_package_details ) {
			printf( esc_html__( 'Shipping #%d', 'storex' ), $index + 1 );
		} else {
			esc_html_e( 'Shipping and Handling', 'storex' );
		}
	?></th>
	<td>
		<?php if ( ! empty( $available_methods ) ) : ?>

			<?php if ( 1 === count( $available_methods ) ) :
				$method = current( $available_methods );

				echo wp_kses_post( wc_cart_totals_shipping_method_label( $method ) ); ?>
				<input type="hidden" name="shipping_method[<?php echo $index; ?>]" data-index="<?php echo $index; ?>" id="shipping_method_<?php echo $index; ?>" value="<?php echo esc_attr( $method->id ); ?>" class="shipping_method" />

			<?php elseif ( get_option( 'woocommerce_shipping_method_format' ) === 'select' ) : ?>

				<select name="shipping_method[<?php echo $index; ?>]" data-index="<?php echo $index; ?>" id="shipping_method_<?php echo $index; ?>" class="shipping_method">
					<?php foreach ( $available_methods as $method ) : ?>
						<option value="<?php echo esc_attr( $method->id ); ?>" <?php selected( $method->id, $chosen_method ); ?>><?php echo wp_kses_post( wc_cart_totals_shipping_method_label( $method ) ); ?></option>
					<?php endforeach; ?>
				</select>

			<?php else : ?>

				<ul id="shipping_method">
					<?php foreach ( $available_methods as $method ) : ?>
						<li>
							<input type="radio" name="shipping_method[<?php echo $index; ?>]" data-index="<?php echo $index; ?>" id="shipping_method_<?php echo $index; ?>_<?php echo sanitize_title( $method->id ); ?>" value="<?php echo esc_attr( $method->id ); ?>" <?php checked( $method->id, $chosen_method ); ?> class="shipping_method" />
							<label for="shipping_method_<?php echo $index; ?>_<?php echo sanitize_title( $method->id ); ?>"><?php echo wp_kses_post( wc_cart_totals_shipping_method_label( $method ) ); ?></label>
						</li>
					<?php endforeach; ?>
				</ul>

			<?php endif; ?>

		<?php elseif ( ! WC()->customer->get_shipping_state() || ! WC()->customer->get_shipping_postcode() ) : ?>

			<?php if ( is_cart() && get_option( 'woocommerce_enable_shipping_calc' ) === 'yes' ) : ?>

				<p><?php esc_html_e( 'Please use the shipping calculator to see available shipping methods.', 'storex' ); ?></p>

			<?php elseif ( is_cart() ) : ?>

				<p><?php esc_html_e( 'Please continue to the checkout and enter your full address to see if there are any available shipping methods.', 'storex' ); ?></p>

			<?php else : ?>

				<p><?php esc_html_e( 'Please fill in your details to see available shipping methods.', 'storex' ); ?></p>

			<?php endif; ?>

		<?php else : ?>

			<?php if ( is_cart() ) : ?>

				<?php echo apply_filters( 'woocommerce_cart_no_shipping_available_html',
					'<div class="woocommerce-info"><p>' . esc_html__( 'There don&lsquo;t seem to be available shipping methods. Please double check your address, or contact us if you need any help.', 'storex' ) . '</p></div>'
				); ?>

			<?php else : ?>

				<?php echo apply_filters( 'woocommerce_no_shipping_available_html',
					'<p>' . esc_html__( 'There don&lsquo;t seem to be available shipping methods. Please double check your address, or contact us if you need any help.', 'storex' ) . '</p>'
				); ?>

			<?php endif; ?>

		<?php endif; ?>

		<?php if ( $show_package_details ) : ?>
			<?php
				foreach ( $package['contents'] as $item_id => $values ) {
					if ( $values['data']->needs_shipping() ) {
						$product_names[] = $values['data']->get_title() . ' &times;' . $values['quantity'];
					}
				}

				echo '<p class="woocommerce-shipping-contents"><small>' .esc_html__( 'Shipping', 'storex' ) . ': ' . implode( ', ', $product_names ) . '</small></p>';
			?>
		<?php endif; ?>

		<?php if ( is_cart() ) : ?>
			<?php //woocommerce_shipping_calculator(); ?>
		<?php endif; ?>
	</td>
</tr>
