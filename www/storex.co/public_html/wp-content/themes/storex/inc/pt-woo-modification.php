<?php

/* ------------- Woocommerce modifications ------------ */

/* Contents:
			1.  Style & Scripts
			2.  Fixing woocommerce price filter
			3.  Product columns filter
			4.  Products per page filter
			5.  Custom catalog order
			6.  Store Banner
			7.  Woocommerce Main content wrapper
			8.  Shop Bredcrumbs
			9.  Modifying Pagination args
			10. Changing 'add to cart' buttons text
			11. Modifying Product Loop layout
			12. Modifying Single Product layout
			13. Related Products
			14. Up-sells modification
			15. Custom chekout fields order output
			16. Cross-sells output 
 */
 
 if ( class_exists('Woocommerce') ) {
 
/*  1. Style & Scripts (start) */
 
	// Deactivating Woocommerce styles(start)
	
		if ( version_compare( WOOCOMMERCE_VERSION, "2.1" ) >= 0 ) {
			add_filter( 'woocommerce_enqueue_styles', '__return_false' );
		} else {
			define( 'WOOCOMMERCE_USE_CSS', false );
		}
		
	// Deactivating Woocommerce styles(end)
	
	// Adding new styles (start)
	
		if ( ! function_exists( 'storex_woo_custom_style' ) ) {
			function storex_woo_custom_style() {
				wp_register_style( 'storex-woo-styles', get_template_directory_uri() .'/woo-styles.css', null, 1.0, 'screen' );
				wp_enqueue_style( 'storex-woo-styles' ); 
			}
		}
		add_action( 'wp_enqueue_scripts', 'storex_woo_custom_style' );
		
	// Adding new styles (end)
	
	// Disable pretty photo scripts & styles (start)
	
		function storex_deregister_javascript() {
			wp_deregister_script( 'prettyPhoto' );
			wp_deregister_script( 'prettyPhoto-init' );
		}
		add_action( 'wp_print_scripts', 'storex_deregister_javascript', 1000 );
		function storex_deregister_styles() {
			wp_deregister_style( 'woocommerce_prettyPhoto_css' );
		}
		add_action( 'wp_print_styles', 'storex_deregister_styles', 100 );
		
	// Disable pretty photo scripts & styles (end)
	
/*  1. Style & Scripts (end)*/
 
 
/*  2. Fixing woocommerce price filter (start)*/

	function storex_price_filter_init() {
		if (function_exists('WC')) {	
			$suffix = defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? '' : '';

			wp_register_script( 'wc-price-slider', WC()->plugin_url() . '/assets/js/frontend/price-slider' . $suffix . '.js', array( 'jquery-ui-slider' ), WC_VERSION, true );

			wp_localize_script( 'wc-price-slider', 'woocommerce_price_slider_params', array(
				'currency_symbol' 	=> get_woocommerce_currency_symbol(),
				'currency_pos'      => get_option( 'woocommerce_currency_pos' ),
				'min_price'			=> isset( $_GET['min_price'] ) ? esc_attr( $_GET['min_price'] ) : '',
				'max_price'			=> isset( $_GET['max_price'] ) ? esc_attr( $_GET['max_price'] ) : ''
			) );

			//add_filter( 'loop_shop_post_in', array( $this, 'price_filter' ) );
		}
	}
	add_action( 'init', 'storex_price_filter_init' );

/*  2. Fixing woocommerce price filter (end)*/
 
/* 3. Product columns filter (start)*/
 
	if ( ! function_exists( 'storex_loop_shop_columns' ) ) {
		function storex_loop_shop_columns(){
			$qty = (get_option('store_columns') != '') ? get_option('store_columns') : '3';
			if ( 'layout-one-col' == pt_show_layout() ) { $qty = 4; }
			return $qty;
		}
	}
	add_filter('loop_shop_columns', 'storex_loop_shop_columns');

/* 3. Product columns filter (end)*/

/* 4. Products per page filter (start) */
 
    function storex_woocommerce_catalog_page_ordering() {
        $product_quntifier = 4;
        $pagers = array(1, 2, 3);
        $current = '';


		if(get_option('store_per_page')){
			$product_quntifier = get_option('store_per_page');
		}

        $current = $product_quntifier;
        if (isset($_COOKIE['pager'])) $current = $_COOKIE['pager'];
        if (isset($_GET['pager'])) $current = $_GET['pager'];

        for( $i = 0; $i < count($pagers); $i++ ){
            $pagers[$i] = $pagers[$i] * $product_quntifier;
        }
        ?>
        <div class="paginator-product">
            <span class="shop-label"><?php esc_html_e('View', 'storex') ?></span>
            <ul class="pagination-per-page">
            <?php foreach($pagers as $pager) : ?>
                <li><?php if ($current != $pager) : ?>
                    <a href="?pager=<?php echo $pager; ?>">
                    <?php endif; ?>
                        <?php printf(esc_html__("%s", 'storex'), $pager); ?>
                    <?php if ($current != $pager) : ?>
                    </a>
                    <?php endif; ?>
					<span class="delimiter">/</span>
                </li>
            <?php endforeach; ?>
                <li>
                    <?php if ($current != 'all') : ?>
                    <a href="?pager=all">
                    <?php endif ?>
                        <?php printf(esc_html__("All", 'storex')); ?>
                    <?php if ($current != 'all') : ?>
                    </a>
                    <?php endif; ?>
                </li>
            </ul>
        </div>
        <?php
    }
	
	add_action( 'woocommerce_before_shop_loop', 'storex_woocommerce_catalog_page_ordering', 20 );

    add_filter( 'loop_shop_per_page', 'storex_shop_per_page', 20 );

    function storex_shop_per_page(){

        if (isset($_GET['pager'])) {
            setcookie('pager', $_GET['pager'], time()+3600);
            return ($_GET['pager'] == 'all' ? 9999 : $_GET['pager'] );
        }

        if (isset($_COOKIE['pager'])) return ( $_COOKIE['pager'] == 'all' ? 9999 : $_COOKIE['pager'] );

		return get_option('store_per_page');
    }

	function storex_result_output_product_wrap_start(){ ?>
		<div class="result-output-product-wrap">
	<?php }
	add_action( 'woocommerce_before_shop_loop', 'storex_result_output_product_wrap_start', 19 );
	
	
	function storex_result_output_product_wrap_end(){ ?>
		</div>
	<?php }
	
	add_action( 'woocommerce_before_shop_loop', 'storex_result_output_product_wrap_end', 21 );
/* 4. Products per page filter (end) */
 
/*  5. Custom catalog order (start) */

	if ( ! function_exists( 'pt_default_catalog_orderby' ) ) {
		function storex_default_catalog_orderby(){
			return 'date'; // Can also use title and price		
		}
	}
	add_filter('woocommerce_default_catalog_orderby', 'storex_default_catalog_orderby');
	
/*  5. Custom catalog order (end) */

/* 6. Store Banner (start) */
	add_action('woocommerce_before_main_content', 'storex_store_banner', 1);
	if ( ! function_exists( 'pt_store_banner' ) ) {
		function storex_store_banner() {
			if ( is_shop() ) {

                if ( (get_option('store_banner')) === 'on' ) {

                    $img_url = (get_option('store_banner_img') != '') ? get_option('store_banner_img') : '';
					$background_button = (get_option('store_banner_button_bg') != '') ? get_option('store_banner_button_bg') : '';
                    $title = (get_option('store_banner_title') != '') ? get_option('store_banner_title') : '';
                    $description = (get_option('store_banner_description') != '') ? get_option('store_banner_description') : '';
                    $url = (get_option('store_banner_url') != '') ? get_option('store_banner_url') : '#';
					$button_text = (get_option('store_banner_button_text') != '') ? get_option('store_banner_button_text') : 'READ MORE';
                    $custom_bg = (get_option('store_banner_custom_bg') != '') ? get_option('store_banner_custom_bg') : '';
					$custom_bg_color = (get_option('store_banner_custom_bg_color') != '') ? get_option('store_banner_custom_bg_color') : '';
                    if ( $custom_bg != '' || $custom_bg_color != '') :?>
					
                    <div class="store-banner"  style="background: url(<?php echo esc_url($custom_bg);?>) center center no-repeat <?php echo esc_attr($custom_bg_color);?>;">
                    <?php else :?>
					<div class="store-banner">
					<?php endif;?>
					
					<div class="container store-banner-inner" style="background: url(<?php  echo esc_url($img_url)?>)  no-repeat transparent;">
                    <div class="row"><div class="banner-text col-sm-12 col-md-12 col-xs-12" >
                    <h3 class="banner-title"><?php echo esc_attr($title);?></h3>
                    <p class="banner-description"><?php echo esc_attr($description);?></p>
					<a href="<?php echo esc_url($url);?>" class="banner-button" title="Click to view all special products" rel="bookmark" style="background:<?php echo esc_attr($background_button);?>"><?php echo esc_attr($button_text);?></a>
                    </div></div><div class="vertical-helper"></div></div></div>
                    
             <?php
                }
			}
		}
}
/* 6. Store Banner (end) */

/* 7. Woocommerce Main content wrapper (start)*/

	remove_action( 'woocommerce_before_main_content', 'woocommerce_output_content_wrapper', 10);
	remove_action( 'woocommerce_after_main_content', 'woocommerce_output_content_wrapper_end', 10);
	
		function storex_theme_wrapper_start() {
		// Check if site turned to boxed version
		$boxed = ''; $boxed_element = ''; $row_class = '';
		if (get_option('site_layout')=='boxed') {$boxed = 'container'; $boxed_element = 'col-md-12 col-sm-12'; $row_class = 'row';}

		// Get number of columns for store page
		$qty = (get_option('store_columns') != '') ? get_option('store_columns') : '3';
		if ( 'layout-one-col' == pt_show_layout() ) { $qty = 4; }

		if (!$boxed || $boxed=='') { ?><div class="container"> <?php } ?>
		<div class="row">
		
		<?php
		if ( pt_show_layout()=='layout-one-col' ) { $content_class = "col-xs-12 col-md-12 col-sm-12"; } 
		elseif ( pt_show_layout()=='layout-two-col-left' ) { $content_class = "site-content col-xs-12 col-md-9 col-sm-8 col-md-push-3 col-sm-push-4"; }
		else { $content_class = "col-xs-12 col-md-9 col-sm-9"; } ?>

		<div id="content" class="site-content woocommerce columns-<?php echo esc_attr($qty);?> <?php echo esc_attr($content_class);?>" role="main">
	
	<?php }
	
		function storex_theme_wrapper_end() {
		// Check if site turned to boxed version
		$boxed = ''; $boxed_element = ''; $row_class = '';
		if (get_option('site_layout')=='boxed') {$boxed = 'container'; $boxed_element = 'col-md-12 col-sm-12'; $row_class = 'row';} ?>

		</div><!-- #content -->
		<?php get_sidebar(); ?>
		</div>
		<?php if (!$boxed || $boxed=='') { ?> </div></div> <?php } 
	}
	
	add_action('woocommerce_before_main_content', 'storex_theme_wrapper_start', 10);
	add_action('woocommerce_after_main_content', 'storex_theme_wrapper_end', 10);
	
/* 7. Woocommerce Main content wrapper (end)*/

/* 8. Shop Bredcrumbs (start)*/

	add_action( 'woocommerce_before_main_content', 'storex_shop_breadcrumbs_wrap_begin', 1 );
		function storex_shop_breadcrumbs_wrap_begin(){
		$rb_color = pt_get_post_pageribbon($post->ID);
				// Check if site turned to boxed version
		$boxed = ''; $boxed_element = ''; $row_class = ''; $container='';
		if (get_option('site_layout')=='boxed') {$boxed = 'container'; $boxed_element = 'col-md-12 col-sm-12'; $row_class = 'row';} ?>
			
			
			 <div class="header-stripe" style="background-color:<?php echo esc_attr($rb_color);?>">
			<?php
				if (!$boxed || $boxed=='') {
			?> 
				<div class="container">  <?php } ?>
            	 	<div class="row">
            	 			<div class="col-md-4 col-sm-4 col-sx-12">
								
		<?php }

	add_action('woocommerce_before_main_content', 'storex_header_stripe', 4);
	
		if ( ! function_exists( 'storex_header_stripe' ) ) {
			function storex_header_stripe() { ?>
				<div class="col-md-4 col-sm-4 col-sx-12">
				<?php if(get_option('back_to_home_button')&&get_option('back_to_home_button')=='on' ): ?>
					<a class="back-to-home" href="<?php echo esc_url(home_url( '/' )); ?>">&#8592; <?php esc_html_e( 'Back to Home', 'storex' ); ?></a>
				<?php endif ?>
				</div>
			<?php }
		}

	add_filter('woocommerce_show_page_title', 'storex_remove_page_shop_title');

	function storex_remove_page_shop_title(){
		return false;
	}

	function storex_page_shop_title(){ ?>
		</div><div class="col-md-4 col-sm-4 col-sx-12"><h1 class="title"><?php echo esc_attr(woocommerce_page_title(false));?></h1></div>
	<?php }
	
	add_action( 'woocommerce_before_main_content', 'storex_page_shop_title', 3 );


	add_action( 'woocommerce_before_main_content', 'storex_shop_breadcrumbs_wrap_end', 5 );
	
		function storex_shop_breadcrumbs_wrap_end(){
				// Check if site turned to boxed version
		$boxed = ''; $boxed_element = ''; $row_class = ''; $container='';
		if (get_option('site_layout')=='boxed') {$boxed = 'container'; $boxed_element = 'col-md-12 col-sm-12'; $row_class = 'row';}?>
			
			</div></div>
			<?php
			if (!$boxed || $boxed=='') {?> </div> <?php }
				
		}

	// Breadcrumbs
	remove_action( 'woocommerce_before_main_content', 'woocommerce_breadcrumb', 20 );
	if ( (get_option('store_breadcrumbs')) === 'on' ) {
		add_action( 'woocommerce_before_main_content', 'woocommerce_breadcrumb', 2 );
	}
	
/* 8. Shop Bredcrumbs (end)*/

/* 9. Modifying Pagination args (start)*/

	function storex_new_pagination_args($args) {
		$args['prev_text'] =wp_kses(__('<i class="fa fa-angle-left"></i>', 'storex'), $allowed_html=array('i' => array('class'=>array())) ); 
		$args['next_text'] =wp_kses(__('<i class="fa fa-angle-right"></i>', 'storex'), $allowed_html=array('i' => array('class'=>array())) ); 
		return $args;
	}
	
	add_filter('woocommerce_pagination_args','storex_new_pagination_args');

/* 9. Modifying Pagination args (end)*/

/* 10. Changing 'add to cart' buttons text (start)*/

	// Changing by product type on archive pages

		function storex_custom_woocommerce_product_add_to_cart_text() {
			global $product;
			$product_type = $product->product_type;
			switch ( $product_type ) {
				case 'external':
					return esc_html__('Buy Product', 'storex');
				break;
				case 'grouped':
					return  esc_html__('Add to Cart', 'storex');
				break;
				case 'simple':
					return  esc_html__('Add to Cart', 'storex');
				break;
				case 'variable':
					return esc_html__('Add to Cart', 'storex');
				break;
				default:
					$text = esc_html__('Read More', 'storex');
					return '<i title="'.$text.'" class="fa fa-search"></i>';
			}
		}

	add_filter( 'woocommerce_product_add_to_cart_text' , 'storex_custom_woocommerce_product_add_to_cart_text' );


	// Changing on single product page
	if ( ! function_exists( 'storex_custom_single_add_to_cart_button_text' ) ) {
		function storex_custom_single_add_to_cart_button_text() {
			$text = esc_html__('Add to Cart', 'storex');
			return esc_attr($text);
		}
	}
	add_filter( 'woocommerce_product_single_add_to_cart_text', 'storex_custom_single_add_to_cart_button_text' ); 

}

/* 10. Changing 'add to cart' buttons text (end)*/

/* 11. Modifying Product Loop layout (start)*/

		/* List/grid view switcher (start)*/
		
	function storex_view_switcher() { ?>
		<div id="button-group-switcher" class="pt-view-switcher">
			<span class="pt-grid active" data-layout-mode-value="fitRows" title="<?php esc_html_e('Grid View', 'storex') ?>"><i class="fa fa-th"></i>
			</span>
			<span class="pt-list" data-layout-mode-value="vertical" title= "<?php esc_html_e('List View', 'storex')?>"><i class="fa fa-bars"></i>
			</span>
		</div>
		
	<?php }
		if ( (get_option('list_grid_switcher')) === 'on' ) {
			add_action( 'woocommerce_before_shop_loop', 'storex_view_switcher', 25 );
		}

	
		/* List/grid view switcher (start)*/

		function storex_buttons_wrapper_start(){ ?>
			<div class="buttons-wrapper">
	<?php }
		add_action( 'woocommerce_after_shop_loop_item', 'storex_buttons_wrapper_start', 1);
		
		function storex_buttons_wrapper_end(){ ?>
			</div>
	<?php }
		add_action( 'woocommerce_after_shop_loop_item', 'storex_buttons_wrapper_end', 31);
		
		
	/* output compare*/
		if( ( class_exists('YITH_Woocompare_Frontend') ) && ( get_option('yith_woocompare_compare_button_in_products_list') == 'yes' ) ) {
			remove_action( 'woocommerce_after_shop_loop_item', array( $yith_woocompare->obj, 'add_compare_link'), 20 );
			add_action( 'woocommerce_after_shop_loop_item', array( $yith_woocompare->obj, 'add_compare_link'), 30  );
		}
		
		
	// add to wishlist button
		function storex_new_wishlist() { 
			if ( ( class_exists( 'YITH_WCWL_Shortcode' ) ) && ( get_option('yith_wcwl_enabled') == true ) ) {
				$atts = array(
			            'per_page' => 10,
			             'pagination' => 'no', 
			    );
			echo YITH_WCWL_Shortcode::add_to_wishlist($atts);
			}
		}

		add_action( 'woocommerce_after_shop_loop_item', 'storex_new_wishlist', 1);
		
	// add category to product loop
function storex_add_categories(){
	global $product;
	echo $product->get_categories( ', ', '<p class="category">' . wp_kses(_n( '', '', sizeof( get_the_terms( $post->ID, 'product_cat' ) ), 'storex' ), $allowed_html=array('a' => array('href'=>array()))) . ' ', '</p>' ); 	
}
	
	// add permalink for product title loop
function storex_add_wrap_title_start(){ ?>
	<a class="product-title" href="<?php the_permalink(); ?>" title="Click to learn more about <?php the_title(); ?>">
<?php
}

function storex_add_wrap_title_end(){ ?>
</a>
<?php }

remove_action( 'woocommerce_after_shop_loop_item_title', 'woocommerce_template_loop_rating', '5' );
add_action( 'woocommerce_shop_loop_item_title', 'storex_add_wrap_title_start', '9' );
add_action( 'woocommerce_shop_loop_item_title', 'storex_add_wrap_title_end', '11' );
add_action( 'woocommerce_shop_loop_item_title', 'storex_add_categories', '7' );
add_action( 'woocommerce_shop_loop_item_title', 'woocommerce_template_loop_rating', '8' );	
/* 11. Modifying Product Loop layout (end)*/

/*12. Modifying Single Product layout (start)*/
	
		// Compare button 
	if( ( class_exists('YITH_Woocompare_Frontend') ) && ( get_option('yith_woocompare_compare_button_in_product_page') == 'yes' ) ) {
		remove_action( 'woocommerce_single_product_summary', array( $yith_woocompare->obj, 'add_compare_link'), 35 );
		add_action( 'woocommerce_after_add_to_cart_button', array( $yith_woocompare->obj, 'add_compare_link'), 32  );
	}

	// Wishlist button 
	if ( ( class_exists( 'YITH_WCWL_Shortcode' ) ) && ( get_option('yith_wcwl_enabled') == true ) && ( get_option('yith_wcwl_button_position') == 'shortcode' ) ) {
		function output_wishlist_button() {
			echo do_shortcode( '[yith_wcwl_add_to_wishlist]' );
		}
		add_action( 'woocommerce_after_add_to_cart_button', 'output_wishlist_button', 31  );
	}
	
	// Social buttons
	if (get_option('pt_shares_for_product')=='on') {
		remove_action('woocommerce_single_product_summary', 'woocommerce_template_single_sharing', 50);
		add_action('woocommerce_single_product_summary', 'storex_share_buttons_output', 50);
	}

/*12. Modifying Single Product layout (end)*/


/* 13. Related Products (start)*/

	remove_action('woocommerce_after_single_product_summary', 'woocommerce_output_related_products', 20);

	function storex_output_related_products($args) {
		$related_qty = get_option('related_products_qty');
		$args['posts_per_page'] = $related_qty; // related products
		$args['columns'] = $related_qty; // arranged in columns
		return $args;
	}

	add_filter( 'woocommerce_output_related_products_args', 'storex_output_related_products' );

	if (get_option('show_related_products')=='on') {
		add_action('woocommerce_after_single_product_summary', 'woocommerce_output_related_products', 30);
	}

/* 13. Related Products (end)*/


/* 14. Up-sells modification (start)*/

	remove_action('woocommerce_after_single_product_summary', 'woocommerce_upsell_display', 15);
	
	if ( ! function_exists( 'storex_output_upsells' ) ) {
		function storex_output_upsells() {

			if ( 'layout-one-col' == pt_show_layout() ) { $per_page = 4; $cols = 4; }
			else { $per_page = 3; $cols = 3; }

			woocommerce_upsell_display( $per_page, $cols ); // Display $per_page products in $cols 
		}
	}
	
	add_action('woocommerce_after_single_product_summary', 'storex_output_upsells', 35);

/* 14. Up-sells modification (end)*/

/* 15. Custom chekout fields order output (start)*/
	
	// Add payment method heading
	function storex_payments_heading(){ ?>
		<h3 id="payment_heading"><?php esc_html__('Payment Methods', 'storex'); ?></h3>
	<?php }
	
	add_action( 'woocommerce_review_order_before_payment', 'storex_payments_heading');
	
	if ( ! function_exists( 'storex_default_address_fields' ) ) {
		function storex_default_address_fields( $fields ) {
		    $fields = array(
				'first_name' => array(
					'label'    => esc_html__( 'First Name', 'storex' ),
					'required' => true,
					'class'    => array( 'form-row-first' ),
				),
				'last_name' => array(
					'label'    => esc_html__( 'Last Name', 'storex' ),
					'required' => true,
					'class'    => array( 'form-row-last' ),
					'clear'    => true
				),
				'company' => array(
					'label' => esc_html__( 'Company Name', 'storex' ),
					'class' => array( 'form-row-wide' ),
				),
				'address_1' => array(
					'label'       => esc_html__( 'Address', 'storex' ),
					'placeholder' => esc_html_x( 'Street address', 'placeholder', 'storex' ),
					'required'    => true,
					'class'       => array( 'form-row-first', 'address-field' )
				),
				'address_2' => array(
					'label'       => esc_html__( 'Additional address info', 'storex' ),
					'placeholder' => esc_html_x( 'Apartment, suite, unit etc. (optional)', 'placeholder', 'storex' ),
					'class'       => array( 'form-row-last', 'address-field' ),
					'required'    => false,
					'clear'    	  => true
				),
				'country' => array(
					'type'     => 'country',
					'label'    => esc_html__( 'Country', 'storex' ),
					'required' => true,
					'class'    => array( 'form-row-first', 'address-field', 'update_totals_on_change' ),
				),
				'city' => array(
					'label'       => esc_html__( 'Town / City', 'storex' ),
					'placeholder' => esc_html__( 'Town / City', 'storex' ),
					'required'    => true,
					'class'       => array( 'form-row-last', 'address-field' )
				),
				'state' => array(
					'type'        => 'state',
					'label'       => esc_html__( 'State / County', 'storex' ),
					'placeholder' => esc_html__( 'State / County', 'storex' ),
					'required'    => true,
					'class'       => array( 'form-row-first', 'address-field' ),
					'validate'    => array( 'state' )
				),
				'postcode' => array(
					'label'       => esc_html__( 'Postcode / Zip', 'storex' ),
					'placeholder' => esc_html__( 'Postcode / Zip', 'storex' ),
					'required'    => true,
					'class'       => array( 'form-row-last', 'address-field' ),
					'clear'       => true,
					'validate'    => array( 'postcode' )
				)
			);
			return $fields;
		}
	}
	
	add_filter( 'woocommerce_default_address_fields' , 'storex_default_address_fields' );

/* 15. Custom chekout fields order output (end)*/


/* 16. Cross-sells output (start)*/
remove_action( 'woocommerce_cart_collaterals', 'woocommerce_cross_sell_display' );
add_action( 'woocommerce_after_cart', 'woocommerce_cross_sell_display' );
/* 16. Cross-sells output (end)*/

		
		