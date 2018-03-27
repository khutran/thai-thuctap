<?php
/**
 * Plumtree Payment Icons
 *
 * Configurable payment icons widget.
 *
 * @author TransparentIdeas
 * @package Plum Tree
 * @subpackage Widgets
 * @since 0.01
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

add_action( 'widgets_init', create_function( '', 'register_widget( "pt_pay_icons_widget" );' ) );

class pt_pay_icons_widget extends WP_Widget {
	
	public function __construct() {
		parent::__construct(
	 		'pt_pay_icons_widget', // Base ID
			esc_html__('PT Payment Icons', 'storex'), // Name
			array( 'description' => esc_html__( 'Plum Tree special widget. Add payment methods icons', 'storex' ), ) 
		);
	}

	public function form( $instance ) {

		$defaults = array( 
			'title' 		=> 'We Accept',
			'precontent'    => '',
			'postcontent'   => '',
			'americanexpress'	=> false,
			'discover'			=> false,
			'maestro'			=> false,
			'mastercard'		=> false,
			'paypal'			=> false,
			'visa'				=> false,
			'westernunion'		=> false,
			'giftcards'			=> false,
			'cash'				=> false,
			'bitcoin'			=> false,
		);

		$instance = wp_parse_args( (array) $instance, $defaults ); 
	?>
		<p>
			<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php esc_html_e( 'Title:', 'storex' ); ?></label> 
			<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo $instance['title']; ?>" />
		</p>
		<p>
			<label for="<?php echo $this->get_field_id ('precontent'); ?>"><?php esc_html_e('Pre-Content', 'storex'); ?></label>
			<textarea class="widefat" id="<?php echo $this->get_field_id('precontent'); ?>" name="<?php echo $this->get_field_name('precontent'); ?>" rows="2" cols="25"><?php echo $instance['precontent']; ?></textarea>
		</p>

		<?php 
		$params = array( 
			'americanexpress' 		=> esc_html__( 'American Express', 'storex' ), 
			'discover'				=> esc_html__( 'Discover', 'storex' ),
			'maestro'				=> esc_html__( 'Maestro', 'storex' ),
			'mastercard'			=> esc_html__( 'Mastercard', 'storex' ),
			'paypal'				=> esc_html__( 'PayPal', 'storex' ),
			'visa'					=> esc_html__( 'Visa', 'storex' ),
			'westernunion'			=> esc_html__( 'Western Union', 'storex' ),
			'giftcards'				=> esc_html__( 'Gift Cards', 'storex' ),
			'cash'					=> esc_html__( 'Cash', 'storex' ),
			'bitcoin'				=> esc_html__( 'Bitcoin', 'storex' ),
		);

		foreach ($params as $key => $value) { ?>
			<p style="display:inline-block; width:40%; padding-right:5%; margin:0;">
				<input type="checkbox" class="checkbox" id="<?php echo $this->get_field_id( $key ); ?>" name="<?php echo $this->get_field_name( $key ); ?>" <?php checked( (bool) $instance[ $key ] ); ?> />
				<label for="<?php echo $this->get_field_id( $key ); ?>"><?php echo $value; ?></label>
			</p>
		<?php } ?>

		<p>
			<label for="<?php echo $this->get_field_id ('postcontent'); ?>"><?php esc_html_e('Post-Content', 'storex'); ?></label>
			<textarea class="widefat" id="<?php echo $this->get_field_id('postcontent'); ?>" name="<?php echo $this->get_field_name('postcontent'); ?>" rows="2" cols="25"><?php echo $instance['postcontent']; ?></textarea>
		</p>

		<?php 
	}

	public function update( $new_instance, $old_instance ) {
		
		$instance = $old_instance;

		$instance['title'] = strip_tags( $new_instance['title'] );
		$instance['precontent'] = stripslashes( $new_instance['precontent'] );
		$instance['postcontent'] = stripslashes( $new_instance['postcontent'] );
		$instance['americanexpress'] = ( $new_instance['americanexpress'] );
		$instance['discover'] = ( $new_instance['discover'] );
		$instance['maestro'] = ( $new_instance['maestro'] );
		$instance['mastercard'] = ( $new_instance['mastercard'] );
		$instance['paypal'] = ( $new_instance['paypal'] );
		$instance['visa'] = ( $new_instance['visa'] );
		$instance['westernunion'] = ( $new_instance['westernunion'] );
		$instance['giftcards'] = ( $new_instance['giftcards'] );
		$instance['cash'] = ( $new_instance['cash'] );
		$instance['bitcoin'] = ( $new_instance['bitcoin'] );

		return $instance;
	}

	public function widget( $args, $instance ) {

		global $wpdb;

		extract( $args );

		$title = apply_filters( 'widget_title', $instance['title'] );
		$americanexpress = (isset($instance['americanexpress']) ? $instance['americanexpress'] : false );
		$discover = (isset($instance['discover']) ? $instance['discover'] : false );
		$maestro = (isset($instance['maestro']) ? $instance['maestro'] : false );
		$mastercard = (isset($instance['mastercard']) ? $instance['mastercard'] : false );
		$paypal = (isset($instance['paypal']) ? $instance['paypal'] : false );
		$visa = (isset($instance['visa']) ? $instance['visa'] : false );
		$westernunion = (isset($instance['westernunion']) ? $instance['westernunion'] : false );
		$giftcards = (isset($instance['giftcards']) ? $instance['giftcards'] : false );
		$cash = (isset($instance['cash']) ? $instance['cash'] : false );
		$bitcoin = (isset($instance['bitcoin']) ? $instance['bitcoin'] : false );
		$precontent = (isset($instance['precontent']) ? $instance['precontent'] : '' );
		$postcontent = (isset($instance['postcontent']) ? $instance['postcontent'] : '' );

		echo $before_widget;
		if ( ! empty( $title ) )
			echo $before_title . $title . $after_title;

		if ( ! empty( $precontent ) ) 
			echo '<div class="precontent">'.esc_attr($precontent).'</div>';
		?>

			<ul class="pt-widget-pay-icons">
				<?php if( $americanexpress ) : ?>
					<li class="option-title">
						<img src="<?php echo get_template_directory_uri() .'/widgets/pay-icons/img/americanexpress-icon.png'; ?>" alt="<?php esc_html_e('American Express', 'storex'); ?>" title="<?php esc_html_e('American Express', 'storex'); ?>"  />
					</li>
				<?php endif; ?>
				<?php if( $discover ) : ?>
					<li class="option-title">
						<img src="<?php echo get_template_directory_uri() .'/widgets/pay-icons/img/discover-icon.png'; ?>" alt="<?php esc_html_e('Discover', 'storex'); ?>" title="<?php esc_html_e('Discover', 'storex'); ?>"  />
					</li>
				<?php endif; ?>
				<?php if( $maestro ) : ?>
					<li class="option-title">
						<img src="<?php echo get_template_directory_uri() .'/widgets/pay-icons/img/maestro-icon.png';?>" alt="<?php esc_html_e('Maestro', 'storex'); ?>" title="<?php esc_html_e('Maestro', 'storex'); ?>"  />
					</li>
				<?php endif; ?>
				<?php if( $mastercard ) : ?>
					<li class="option-title">
						<img src="<?php echo get_template_directory_uri() .'/widgets/pay-icons/img/mastercard-icon.png';?>" alt="<?php esc_html_e('MasterCard', 'storex'); ?>" title="<?php esc_html_e('MasterCard', 'storex'); ?>"/>
					</li>
				<?php endif; ?>
				<?php if( $paypal ) : ?>
					<li class="option-title">
						<img src="<?php echo get_template_directory_uri() .'/widgets/pay-icons/img/paypal-icon.png'?>" alt="<?php esc_html_e('PayPal', 'storex'); ?>" title="<?php esc_html_e('PayPal', 'storex'); ?>"/>
					</li>
				<?php endif; ?>
				<?php if( $visa ) : ?>
					<li class="option-title">
						<img src="<?php echo get_template_directory_uri() .'/widgets/pay-icons/img/visa-icon.png'?>" alt="<?php esc_html_e('Visa', 'storex'); ?>" title="<?php esc_html_e('Visa', 'storex'); ?>"/>
					</li>
				<?php endif; ?>
				<?php if( $westernunion ) : ?>
					<li class="option-title">
						<img src="<?php echo get_template_directory_uri() .'/widgets/pay-icons/img/westernunion-icon.png'?>" alt="<?php esc_html_e('Western Union', 'storex'); ?>" title="<?php esc_html_e('Western Union', 'storex'); ?>"  />
					</li>
				<?php endif; ?>
				<?php if( $giftcards ) : ?>
					<li class="option-title">
						<img src="<?php echo get_template_directory_uri() .'/widgets/pay-icons/img/giftcards-icon.png'?>" alt="<?php esc_html_e('Gift Cards', 'storex'); ?>" title="<?php esc_html_e('Gift Cards', 'storex'); ?>"  />
					</li>
				<?php endif; ?>
				<?php if( $cash ) : ?>
					<li class="option-title">
						<img src="<?php echo get_template_directory_uri() .'/widgets/pay-icons/img/cash-icon.png'?>" alt="<?php esc_html_e('Cash', 'storex'); ?>" title="<?php esc_html_e('Cash', 'storex'); ?>"  />
					</li>
				<?php endif; ?>
				<?php if( $bitcoin ) : ?>
					<li class="option-title">
						<img src="<?php echo get_template_directory_uri() .'/widgets/pay-icons/img/bitcoin-icon.png'?>" alt="<?php esc_html_e('Bitcoin', 'storex'); ?>" title="<?php esc_html_e('Bitcoin', 'storex'); ?>"  />
					</li>
				<?php endif; ?>

			</ul>

		<?php 
		if ( ! empty( $postcontent ) )
			echo '<div class="postcontent">'.esc_attr($postcontent).'</div>';

		echo $after_widget;
	}
}

// Adding styles
function print_pt_pay_icons_widget_styles(){
	wp_enqueue_style( 'plumtree-pay-icons-widget', get_template_directory_uri() .'/widgets/pay-icons/css/class-pt-widget-pay-icons.css', true);
}

add_action( 'wp_enqueue_scripts', 'print_pt_pay_icons_widget_styles');
