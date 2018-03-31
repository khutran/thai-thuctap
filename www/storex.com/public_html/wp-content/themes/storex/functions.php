<?php
/**
 * PlumTree functions and definitions.
 *
 * Sets up the theme and provides some helper functions, which are used
 * in the theme as custom template tags. Others are attached to action and
 * filter hooks in WordPress to change core functionality.
 *
 */
 
 /* Contents:
	1.  Set up the content width value based on the theme's design
	2.  Set up php DIR variable
	3.  Adding additional image sizes
	4.  Plumtree_setup
	5.  Enqueue scripts and styles for the front-end
	6.  Plumtree Init Sidebars
	7.  Removing autop filter
	8.  Include widgets
	9.  Additional functions
	10. Adding pagebuilder custom shortcodes
	11. Required functions
	12. Shortcodes
*/

/* 1. Set up the content width value based on the theme's design. (start)*/

	if (!isset( $content_width )) $content_width = 1200;

/* 1. Set up the content width value based on the theme's design. (end)*/

/* 2. Set up php DIR variable (start)*/

	if (!defined(__DIR__)) define ('__DIR__', dirname(__FILE__));

/* 2. Set up php DIR variable (end)*/

/* 3. Adding additional image sizes (start)*/

	if ( function_exists( 'add_image_size' ) ) { 
		add_image_size( 'storex-related-thumb', 370, 216, true );
		add_image_size( 'storex-carousel-medium', 660, 720, false);
		add_image_size( 'storex-carousel-large', 760, 500, true);
		add_image_size( 'storex-pt-recent-post', 520, 520, false);
		add_image_size( 'storex-pt-portfolio-thumb', 720, 9999);		
	}

/* 3. Adding additional image sizes (end) */

/* 4. Plumtree_setup (start)*/
	if ( ! function_exists( 'storex_setup' ) ) :
		function storex_setup() {
			// Translation availability
			load_theme_textdomain( 'storex', get_template_directory() . '/languages' );

			// Add RSS feed links to <head> for posts and comments.
			add_theme_support( 'automatic-feed-links' );

			add_theme_support( "title-tag" );
			
			add_theme_support( "custom-header");

			// Enable support for Post Thumbnails.
			add_theme_support( 'post-thumbnails' );

			set_post_thumbnail_size( 820, 420, true);

			// Nav menus.
			register_nav_menus( array(
				'header-top-nav'   => esc_html__( 'Top Menu', 'storex' ),
				'primary-nav'      => esc_html__( 'Primary Menu (Under Logo)', 'storex' ),
				'footer-nav'       => esc_html__( 'Footer Menu', 'storex' )
			) );

			// Switch default core markup for search form, comment form, and comments to output valid HTML5.
			add_theme_support( 'html5', array(
				'search-form', 'comment-form', 'comment-list', 'gallery', 'caption'
			) );

			// Enable support for Post Formats.
			add_theme_support( 'post-formats', array(
				'aside', 'image', 'video', 'audio', 'quote', 'link', 'gallery',
			) );
	
			// This theme allows users to set a custom background.
			add_theme_support( 'custom-background', array(
				'default-color' => 'FFFFFF',
			) );

			// Enable woocommerce support
			add_theme_support( 'woocommerce' );
	
			// Enable layouts support
			$pt_layouts = array(
				array('value' => 'one-col', 'label' => '1 Column (no sidebars)', 'icon' => get_template_directory_uri().'/assets/one-col.png'),
				array('value' => 'two-col-left', 'label' => '2 Columns, sidebar on left', 'icon' => get_template_directory_uri().'/assets/two-col-left.png'),
				array('value' => 'two-col-right', 'label' => '2 Columns, sidebar on right', 'icon' => get_template_directory_uri().'/assets/two-col-right.png'),
			);
			add_theme_support( 'plumtree-layouts', apply_filters('pt_default_layouts', $pt_layouts) ); 
		}
	endif;
// storex_setup
add_action( 'after_setup_theme', 'storex_setup' );

/* 4. Plumtree_setup (end) */

/* 5. Enqueue scripts and styles for the front-end. (start)*/

	function storex_scripts() {
	//----Base CSS Styles-----------
		wp_enqueue_style( 'storex-basic', get_stylesheet_uri() );
		wp_enqueue_style( 'storex-hover-effects', get_template_directory_uri().'/css/hover-effects.css' );
		wp_enqueue_style( 'storex-icomoon-font', get_template_directory_uri().'/css/icomoon.css' );
		
	//----Base JS libraries
		wp_enqueue_script( 'hoverIntent', array('jquery') );
		wp_enqueue_script( 'storex-easings', get_template_directory_uri() . '/js/jquery.easing.1.3.min.js', array('jquery'), '1.3', true );	
		wp_enqueue_script( 'storex-lazy-load', get_template_directory_uri() . '/js/lazyload.min.js', array('jquery'), '1.9.3', true );
		wp_enqueue_script( 'storex-images-loaded', get_template_directory_uri() . '/js/imagesloaded.min.js', array('jquery'), '3.1.8', true );
		wp_enqueue_script( 'storex-basic-js', get_template_directory_uri() . '/js/helper.js', array('jquery'), '1.0', true );
		wp_enqueue_script( 'storex-bootstrap-js', get_template_directory_uri() . '/js/bootstrap.js', array('jquery'), '3.1.1', true);
		
		//----Load Waypoints---------------
	if(get_option ('stycky_menu')=='on'){          
		wp_enqueue_script('storex-waypoints-sticky', get_template_directory_uri() . '/js/jquery.waypoints.min.js', array('jquery'), '1.8', true);
		wp_enqueue_script('storex-sticky', get_template_directory_uri() . '/js/sticky.min.js', array('jquery'), '1.8', true);
		wp_enqueue_script('storex-sticky-helper', get_template_directory_uri() . '/js/sticky-helper.js', array('jquery'), '1.8', true);
	}
		
	wp_enqueue_script('storex-retina', get_template_directory_uri() . '/js/retina.min.js', array('jquery'), '1.8', true);
	
	wp_enqueue_script('storex-countdown', get_template_directory_uri() . '/js/jquery.countdown.min.js', array('jquery'), '1.0.1.', true );
	//----Load Bootsrap-------------
		wp_enqueue_style( 'storex-bootstrap-layout', get_template_directory_uri() . '/css/bootstrap.css' );
		wp_enqueue_style( 'storex-bootstrap-components', get_template_directory_uri() . '/css/bootstrap-theme.css' );
		wp_enqueue_style( 'storex-font-awesome', get_template_directory_uri() . '/css/font-awesome.min.css' );
	
	//----Comments script-----------
		if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
			wp_enqueue_script( 'comment-reply' );
		}
	}
	
	add_action( 'wp_enqueue_scripts', 'storex_scripts' );
	
/* 5. Enqueue scripts and styles for the front-end. (end)*/

/* 6. Plumtree Init Sidebars. (start)*/
	
		function storex_widgets_init() {
		// Default Sidebars
		register_sidebar( array(
			'name' => esc_html__( 'Blog Sidebar', 'storex' ),
			'id' => 'sidebar-blog',
			'description' => esc_html__( 'Appears on single blog posts and on Blog Page', 'storex' ),
			'before_widget' => '<div id="%1$s" class="widget %2$s">',
			'after_widget' => '</div>',
			'before_title' => '<h3 class="widget-title">',
			'after_title' => '</h3>',
		) );
		register_sidebar( array(
			'name' => esc_html__( 'Header Top Panel Sidebar', 'storex' ),
			'id' => 'top-sidebar',
			'description' => esc_html__( 'Located at the top of site', 'storex' ),
			'before_widget' => '<div id="%1$s" class="%2$s left-aligned">',
			'after_widget' => '</div>',
			'before_title' => '<!--',
			'after_title' => '-->',
		) );

		register_sidebar( array(
			'name' => esc_html__( 'Header (Logo group) sidebar', 'storex' ),
			'id' => 'hgroup-sidebar',
			'description' => esc_html__( 'Located to the right from header', 'storex' ),
			'before_widget' => '<div id="%1$s" class="widget %2$s">',
			'after_widget' => '</div>',
			'before_title' => '',
			'after_title' => '',
		) );

		register_sidebar( array(
			'name' => esc_html__( 'Front Page Bottom Sidebar#1', 'storex' ),
			'id' => 'front-page-bottom-sidebar-1',
			'description' => esc_html__( 'Appears when using the optional Front Page template with a page set as Static Front Page', 'storex' ),
			'before_widget' => '<div id="%1$s" class="widget %2$s">',
			'after_widget' => '</div>',
			'before_title' => '<h3 class="widget-title">',
			'after_title' => '</h3>',
		) );

		register_sidebar( array(
			'name' => esc_html__( 'Front Page Bottom Sidebar#2', 'storex' ),
			'id' => 'front-page-bottom-sidebar-2',
			'description' => esc_html__( 'Appears when using the optional Front Page template with a page set as Static Front Page', 'storex' ),
			'before_widget' => '<div id="%1$s" class="widget %2$s">',
			'after_widget' => '</div>',
			'before_title' => '<h3 class="widget-title">',
			'after_title' => '</h3>',
		) );
		
		register_sidebar( array(
			'name' => esc_html__( 'Front Page Bottom Sidebar#3', 'storex' ),
			'id' => 'front-page-bottom-sidebar-3',
			'description' => esc_html__( 'Appears when using the optional Front Page template with a page set as Static Front Page', 'storex' ),
			'before_widget' => '<div id="%1$s" class="widget %2$s">',
			'after_widget' => '</div>',
			'before_title' => '<h3 class="widget-title">',
			'after_title' => '</h3>',
		) );
		
		register_sidebar( array(
			'name' => esc_html__( 'Front Page Bottom Sidebar#4', 'storex' ),
			'id' => 'front-page-bottom-sidebar-4',
			'description' => esc_html__( 'Appears when using the optional Front Page template with a page set as Static Front Page', 'storex' ),
			'before_widget' => '<div id="%1$s" class="widget %2$s">',
			'after_widget' => '</div>',
			'before_title' => '<h3 class="widget-title">',
			'after_title' => '</h3>',
		) );
		
		register_sidebar( array(
			'name' => esc_html__( 'Pages Sidebar', 'storex' ),
			'id' => 'sidebar-pages',
			'description' => esc_html__( 'Appears on Pages', 'storex' ),
			'before_widget' => '<div id="%1$s" class="widget %2$s">',
			'after_widget' => '</div>',
			'before_title' => '<h3 class="widget-title">',
			'after_title' => '</h3>',
		) );

		register_sidebar( array(
			'name' => esc_html__( 'Shop Page Sidebar', 'storex' ),
			'id' => 'sidebar-shop',
			'description' => esc_html__( 'Appears on Products page', 'storex' ),
			'before_widget' => '<div id="%1$s" class="widget %2$s">',
			'after_widget' => '</div>',
			'before_title' => '<h3 class="widget-title">',
			'after_title' => '</h3>',
		) );

		register_sidebar( array(
			'name' => esc_html__( 'Single Product Page Sidebar', 'storex' ),
			'id' => 'sidebar-product',
			'description' => esc_html__( 'Appears on Single Products page', 'storex' ),
			'before_widget' => '<div id="%1$s" class="widget %2$s">',
			'after_widget' => '</div>',
			'before_title' => '<h3 class="widget-title">',
			'after_title' => '</h3>',
		) );

		// Footer Sidebars
		register_sidebar( array(
			'name' => esc_html__( 'Footer Top Sidebar Col#1', 'storex' ),
			'id' => 'footer-top-sidebar-1',
			'description' => esc_html__( 'Located in the top footer of the site', 'storex' ),
			'before_widget' => '<div id="%1$s" class="widget %2$s">',
			'after_widget' => '</div>',
			'before_title' => '<h3 class="widget-title">',
			'after_title' => '</h3>',
		) );

		register_sidebar( array(
			'name' => esc_html__( 'Footer Top Sidebar Col#2', 'storex' ),
			'id' => 'footer-top-sidebar-2',
			'description' => esc_html__( 'Located in the top footer of the site', 'storex' ),
			'before_widget' => '<div id="%1$s" class="widget %2$s">',
			'after_widget' => '</div>',
			'before_title' => '<h3 class="widget-title">',
			'after_title' => '</h3>',
		) );

		register_sidebar( array(
			'name' => esc_html__( 'Footer Middle Sidebar Col#1', 'storex' ),
			'id' => 'footer-sidebar-1',
			'description' => esc_html__( 'Located in the footer of the site', 'storex' ),
			'before_widget' => '<div id="%1$s" class="widget %2$s">',
			'after_widget' => '</div>',
			'before_title' => '<h3 class="widget-title">',
			'after_title' => '</h3>',
		) );
		
		register_sidebar( array(
			'name' => esc_html__( 'Footer Middle Sidebar Col#2', 'storex' ),
			'id' => 'footer-sidebar-2',
			'description' => esc_html__( 'Located in the footer of the site', 'storex' ),
			'before_widget' => '<div id="%1$s" class="widget %2$s">',
			'after_widget' => '</div>',
			'before_title' => '<h3 class="widget-title">',
			'after_title' => '</h3>',
		) );

		register_sidebar( array(
			'name' => esc_html__( 'Footer Middle Sidebar Col#3', 'storex' ),
			'id' => 'footer-sidebar-3',
			'description' => esc_html__( 'Located in the footer of the site', 'storex' ),
			'before_widget' => '<div id="%1$s" class="widget %2$s">',
			'after_widget' => '</div>',
			'before_title' => '<h3 class="widget-title">',
			'after_title' => '</h3>',
		) );

		register_sidebar( array(
			'name' => esc_html__( 'Footer Middle Sidebar Col#4', 'storex' ),
			'id' => 'footer-sidebar-4',
			'description' => esc_html__( 'Located in the footer of the site', 'storex' ),
			'before_widget' => '<div id="%1$s" class="widget %2$s">',
			'after_widget' => '</div>',
			'before_title' => '<h3 class="widget-title">',
			'after_title' => '</h3>',
		) );

		register_sidebar( array(
        'name' => esc_html__( 'Footer Bottom Sidebar', 'storex' ),
        'id' => 'footer-bottom',
        'description' => esc_html__( 'Located in the footer of the site', 'storex' ),
        'before_widget' => '<div id="%1$s" class="widget %2$s">',
        'after_widget' => '</div>',
        'before_title' => '<h3 class="widget-title">',
        'after_title' => '</h3>',
    ) );

		register_sidebar( array(
			'name' => esc_html__( 'Footer Bottom Sidebar(Shop)', 'storex' ),
			'id' => 'footer-bottom-shop',
			'description' => esc_html__( 'Located in the footer of the shop', 'storex' ),
			'before_widget' => '<div id="%1$s" class="widget %2$s">',
			'after_widget' => '</div>',
			'before_title' => '<h3 class="widget-title">',
			'after_title' => '</h3>',
		) );
	    if ( get_option('filters_sidebar')=='on' ) {		
		// Register sidebar
			register_sidebar( array(
			    'name' => esc_html__( 'Special Filters Sidebar', 'plumtree' ),
		        'id' => 'filters-sidebar',
		        'description' => esc_html__( 'Located at the top of the products page', 'plumtree' ),
		        'before_widget' => '<aside id="%1$s" class="widget %2$s">',
		        'after_widget' => '</aside>',
		        'before_title' => '<h3 class="dropdown-filters-title">',
		        'after_title' => '</h3>',
		    ) );
		}
		}
		add_action( 'widgets_init', 'storex_widgets_init' );
	
	
/* 6. Plumtree Init Sidebars. (end)*/

/* 7. Add filter do_shortcode in the text widget. (start)*/

	add_filter('widget_text', 'do_shortcode');
	
/* 7.Add filter do_shortcode in the text widget. (end)*/

/* 8. Include widgets. (start)*/
	require_once( trailingslashit( get_template_directory() ).'/widgets/class-pt-widget-search.php');
	require_once(trailingslashit( get_template_directory() ).'widgets/class-pt-widget-contacts.php');
	if ( class_exists('Woocommerce') ) {
		require_once(trailingslashit( get_template_directory() ).'/widgets/class-pt-widget-shop-filters.php');
		require_once(trailingslashit( get_template_directory() ).'/widgets/class-pt-widget-cart.php');
	}
	require_once(trailingslashit( get_template_directory() ).'/widgets/social-networks/class-pt-widget-socials.php');
	require_once(trailingslashit( get_template_directory() ).'/widgets/class-pt-widget-recent-posts.php');
	require_once(trailingslashit( get_template_directory() ).'/widgets/class-pt-widget-collapsing-categories.php');
	require_once(trailingslashit( get_template_directory() ).'/widgets/pay-icons/class-pt-widget-pay-icons.php');
	
/* 8. Include widgets. (end)*/

/* 9. Additional functions (start)*/
	if ( !is_single() ) {
		require_once(trailingslashit( get_template_directory() ).'/extensions/isotope/isotope.php');
	}
	require_once(trailingslashit( get_template_directory() ).'/extensions/videobg/videobg.php');
	require_once(trailingslashit( get_template_directory() ).'/extensions/gmaps/gmaps.php');
	require_once(trailingslashit( get_template_directory() ).'/extensions/magnific/magnific.php');
	require_once(trailingslashit( get_template_directory() ).'/extensions/pagination/pagination.php');
	require_once(trailingslashit( get_template_directory() ).'/extensions/owl-carousel/owl-carousel.php');
	require_once(trailingslashit( get_template_directory() ).'/extensions/select2/select2.php');
	require_once(trailingslashit( get_template_directory() ).'/extensions/stellar/stellar.php');
	require_once(trailingslashit( get_template_directory() ).'/extensions/post-likes/post-like.php');
	if ( get_option('to_top_button')=='on' ) {
		require_once(trailingslashit( get_template_directory() ).'/extensions/totop/totop.php');
	}
	if ( get_option('blog_pagination')=='infinite' ) {
		require_once(trailingslashit( get_template_directory() ).'/extensions/infinite-blog/infinite-blog.php');
	}
	if ( get_option('site_breadcrumbs')=='on' || get_option('post_breadcrumbs')=='on' ) {
		require_once(trailingslashit( get_template_directory() ).'/extensions/breadcrumbs/breadcrumbs.php');
	}
	if ( get_option('blog_share_buttons')=='on' || get_option('pt_shares_for_product')=='on' ) {
		require_once(trailingslashit( get_template_directory() ).'/extensions/share-buttons/pt-share-buttons.php');
	}
	if ( class_exists('Woocommerce') ) {
		if ( get_option('use_pt_images_slider')=='on' ) {
			require_once(trailingslashit( get_template_directory() ).'/inc/pt-product-images.php');
		}
	}
	
/* 9. Additional functions (end)*/

/* 10. Adding pagebuilder custom shortcodes (start)*/

	if (class_exists('IG_Pb_Init')) {
		require_once(trailingslashit( get_template_directory() ).'/shortcodes/add_to_contentbuilder.php');
	}

/* 10. Adding pagebuilder custom shortcodes (end)*/

/* 11. Required functions (start)*/
	
	// Required functions
	require_once(trailingslashit( get_template_directory() ).'/inc/pt-theme-layouts.php');
	require_once(trailingslashit( get_template_directory() ).'/inc/pt-functions.php');
	require_once(trailingslashit( get_template_directory() ).'/inc/storex-google-fonts.php');
	require_once(trailingslashit( get_template_directory() ).'/ptpanel/ptpanel.php');
	require_once(trailingslashit( get_template_directory() ).'/inc/pt-admin.php');
	require_once(trailingslashit( get_template_directory() ).'/inc/pt-self-install.php');
	if ( class_exists('Woocommerce') ) {
		require_once(trailingslashit( get_template_directory() ).'/inc/pt-woo-modification.php');
	}
	require_once(trailingslashit( get_template_directory() ).'/inc/page-stripe.php');
/* 11. Required functions (end)*/

/* 12. Shortcodes (start)*/

	require_once(trailingslashit( get_template_directory() ).'/shortcodes/pt-contacts.php');
	require_once(trailingslashit( get_template_directory() ).'/shortcodes/pt-posts.php');
	require_once(trailingslashit( get_template_directory() ).'/shortcodes/pt-mega-posts.php');

/* 12. Shortcodes (end)*/



