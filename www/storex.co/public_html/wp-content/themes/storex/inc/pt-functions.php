<?php /*-------Storex Theme Functions----------*/


/* Contents:
	1.  Replaces the excerpt "more" text
	2.  Storex custom media fields function
	3.  Storex Meta output functions
	4.  Storex Views counter function
	5.  Storex Fix captions width function
	6.  Storex Adding inline CSS styles
	7.  Theme url functions
	8.  Storex get attached images
	9.  Storex comment function
	10. Storex pagination for gallery
	11. Add Max excerpt function for Mega Post
	12. Add Meta Box for PT Mega Post
 */
 
 
/* 1. Replaces the excerpt "more" text  (start)*/

	function storex_excerpt_more() {
		if ( !get_option('blog_read_more_text')=='') {
			return get_option('blog_read_more_text');
	}
		else { return esc_html__('Read More', 'storex'); }
	}

add_filter('storex_more', 'storex_excerpt_more');

/* 1. Replaces the excerpt "more" text  (end)*/

/* 2. Storex custom media fields function (start)*/
	
	function storex_custom_media_fields( $form_fields, $post ) {
		$form_fields['portfolio_filter'] = array(
			'label' => 'Portfolio Filters',
			'input' => 'text',
			'value' => get_post_meta( $post->ID, 'portfolio_filter', true ),
			'helps' => 'Used only for Portfolio and Gallery Pages Isotope filtering',
	);
		return $form_fields;
	}

	add_filter( 'attachment_fields_to_edit', 'storex_custom_media_fields', 10, 2 );

	function storex_custom_media_fields_save( $post, $attachment ) {
		if( isset( $attachment['portfolio_filter'] ) )
			update_post_meta( $post['ID'], 'portfolio_filter', $attachment['portfolio_filter'] );
		if( isset( $attachment['hover_style'] ) )
			update_post_meta( $post['ID'], 'hover_style', $attachment['hover_style'] );
		return $post;
	}
	add_filter( 'attachment_fields_to_save', 'storex_custom_media_fields_save', 10, 2 );

/* 2. Storex custom media fields function  (end)*/

/* 3. Storex Meta output functions (start)*/
	
	if ( ! function_exists( 'storex_entry_publication_time' ) ) {
		function storex_entry_publication_time() { ?>
		<div class="time-wrapper">
			<?php printf('<time class="entry-date" datetime="%1$s">%3$s<span class="day">%2$s</span></time>',
				esc_attr( get_the_date('c') ),
				esc_html( get_the_date('j') ),
				esc_html( get_the_date('M') )
			); ?>
		</div>
		<?php }
	}
	
	if ( ! function_exists( 'storex_entry_comments_counter' ) ) {
		function storex_entry_comments_counter() { ?>
			<div class="post-comments"><span class="comments"><?php esc_html_e('Comments: &nbsp;', 'storex'); ?></span>
				<?php comments_popup_link( '0', '1', '%', 'comments-link', 'Commenting: OFF'); ?>
			</div>
		<?php }
	}
	
	if ( ! function_exists( 'storex_entry_post_cats' ) ) {
		function storex_entry_post_cats() {
			$storex_categories_list = get_the_category_list( esc_html__( ', ', 'storex' ) );
			if ( $storex_categories_list ) { ?> 
			<div class="post-cats"><span class="category"><?php esc_html_e('Categories :', 'storex'); ?></span> <?php echo $storex_categories_list; ?></div>
			<?php }
		}
	}
	
	if ( ! function_exists( 'storex_entry_author' ) ) {
		function storex_entry_author() {
			$storex_author = sprintf( '<div class="post-author">by<a href="%1$s" title="%2$s" rel="author">  %3$s</a></div>',
				esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ),
				esc_attr( sprintf( esc_html__( 'View all posts by %s', 'storex' ), get_the_author() ) ),
				get_the_author()
			);
		echo $storex_author; 
		}
	}
	
	if ( ! function_exists( 'storex_entry_post_views' ) ) {
		function storex_entry_post_views() {
			global $post;
			$storex_views = get_post_meta ($post->ID,'views',true);
			if ($storex_views) { 
				return '<div class="post-views" title="Total Views"><i class="fa fa-eye"></i>'.esc_attr($storex_views).'</div>';
			 } else { 
				return '<div class="post-views" title="Total Views"><i class="fa fa-eye"></i>0</div>';
			 }
		}
	}
	
	function storex_entry_post_tags() {
		$storex_tag_list = get_the_tag_list( '', esc_html__( ', ', 'storex' ) );
			if ( $storex_tag_list ) { ?> 
			<div class="post-tags"><span><?php esc_html_e('Tag:', 'storex'); ?></span> <?php echo get_the_tag_list( '', esc_html__( ', ', 'storex' ) ); ?></div>
			<?php }
	}
/* 3. Storex Meta output functions (end)*/

/* 4. Storex Views counter function (start)*/

	if ( ! function_exists( 'storex_postviews' ) ) {
		function storex_postviews() {  
      
    /* ------------ Settings -------------- */  
		$meta_key       = 'views';  	// The meta key field, which will record the number of views.  
		$who_count      = 0;            // Whose visit to count? 0 - All of them. 1 - Only the guests. 2 - Only registred users.  
		$exclude_bots   = 1;            // Exclude bots, robots, spiders, and other mischief? 0 - no. 1 - yes.  
      
		global $user_ID, $post;  
			if(is_singular()) {  
				$id = (int)$post->ID;  
				static $post_views = false;  
				if($post_views) return true;   
				$post_views = (int)get_post_meta($id,$meta_key, true);  
				$should_count = false;  
				switch( (int)$who_count ) {  
					case 0: $should_count = true;  
						break;  
					case 1:  
						if( (int)$user_ID == 0 )  
							$should_count = true;  
						break;  
					case 2:  
						if( (int)$user_ID > 0 )  
							$should_count = true;  
						break;  
				}  
            if( (int)$exclude_bots==1 && $should_count ){  
                $useragent = $_SERVER['HTTP_USER_AGENT'];  
                $notbot = "Mozilla|Opera"; //Chrome|Safari|Firefox|Netscape - all equals Mozilla  
                $bot = "Bot/|robot|Slurp/|yahoo";  
                if ( !preg_match("/$notbot/i", $useragent) || preg_match("!$bot!i", $useragent) )  
                    $should_count = false;  
            }  
            if($should_count)  
                if( !update_post_meta($id, $meta_key, ($post_views+1)) ) add_post_meta($id, $meta_key, 1, true);  
        }  
			return true;  
		} 
	}
add_action('wp_head', 'storex_postviews'); 

/* 4. Storex Views counter function (end)*/

/* 5 Storex Fix captions width function (start)*/
	if ( ! function_exists( 'storex_fixed_caption_width' ) ) {
		function storex_fixed_caption_width($attr, $content = null) {
			// New-style shortcode with the caption inside the shortcode with the link and image tags.
			if ( ! isset( $attr['caption'] ) ) {
				if ( preg_match( '#((?:<a [^>]+>\s*)?<img [^>]+>(?:\s*</a>)?)(.*)#is', $content, $matches ) ) {
					$content = $matches[1];
					$attr['caption'] = trim( $matches[2] );
				}
			}
		// Allow plugins/themes to override the default caption template.
			$output = apply_filters('img_caption_shortcode', '', $attr, $content);
			if ( $output != '' )
			return $output;

			extract(shortcode_atts(array(
				'id'	=> '',
				'align'	=> 'alignnone',
				'width'	=> '',
				'caption' => ''
			), $attr));

			if ( 1 > (int) $width || empty($caption) )
				return $content;

			if ( $id ) $id = 'id="' . esc_attr($id) . '" ';

			return '<div ' . $id . 'class="wp-caption ' . esc_attr($align) . '" style="width: ' . $width . 'px">'
			. do_shortcode( $content ) . '<p class="wp-caption-text">' . $caption . '</p></div>';
		}
	}
	add_shortcode('wp_caption', 'storex_fixed_caption_width');
	add_shortcode('caption', 'storex_fixed_caption_width');

/* 5 Storex Fix captions width function (end)*/

/* 6 Storex Adding inline CSS styles (start)*/

if ( !function_exists( 'storex_add_inline_styles' ) && get_option('site_custom_colors') == 'on' ) {

	function storex_add_inline_styles() {

		/* Variables */
		$main_menu_color=esc_attr(get_option('main_menu_color'));
		$main_menu_hover_color=esc_attr(get_option('main_menu_hover_color'));
		$top_panel_bg_option = esc_attr(get_option('top_panel_bg'));
		$top_panel_color_option = esc_attr(get_option('top_panel_color_text'));
		$top_panel_link_option = esc_attr(get_option('top_panel_link_color'));
		$top_panel_link_hover_option = esc_attr(get_option('top_panel_link_color_hover'));
		$main_font_color = esc_attr(get_option('main_color'));
		$footer_font_color = esc_attr(get_option('footer_color'));
		$footer_font_color_hover = esc_attr(get_option('footer_link_hover'));
		$link_color = esc_attr(get_option('link_color'));
		$link_hover_color = esc_attr(get_option('link_color_hover'));
		
		$content_headings_color = esc_attr(get_option('headings_content'));
		$sidebar_headings_color = esc_attr(get_option('headings_sidebar'));
		$footer_headings_color = esc_attr(get_option('headings_footer'));
		
		
		$button_color = esc_attr(get_option('button_color'));
		$button_hover_color = esc_attr(get_option('button_color_hover'));
		$button_text_color = esc_attr(get_option('button_color_text'));

		$out = '<style type="text/css">
				body {
					color: '.$main_font_color.';
				}
				.header-top {
					background:'.$top_panel_bg_option.';
					color:'.$top_panel_color_option.';
				}
				.header-top a{
					color:'.$top_panel_link_option.';
				}
				.header-top a:hover{
					color:'.$top_panel_link_hover_option.';
				}
				
				.widget {
					color: '.$main_font_color.';
				}
				.site-content a,
				.sidebar a {
					color: '.$link_color.' !important;
				}
				.site-content a:hover,
				.site-content a:focus,
				.site-content a:active,
				.sidebar a:hover,
				.sidebar a:focus,
				.sidebar a:active{
					color: '.$link_hover_color.' !important;
				}
				.entry-content h1,
				.entry-content h2,
				.entry-content h3,
				.entry-content h4,
				.entry-content h5,
				.entry-content h6,
				.entry-title,
				.entry-title a,
				.comment-reply-title,
				.comments-area h2,
				a.shipping-calculator-button{
					color: '.$content_headings_color.' !important;
				}
				.sidebar h1,
				.sidebar h2,
				.sidebar h3,
				.sidebar h4,
				.sidebar h5,
				.sidebar h6 {
					color: '.$sidebar_headings_color.' !important;
				}
				.site-footer,
				.site-footer .widget{
					color:'.$footer_font_color.';
				}
				.site-footer a {
					color: '.$footer_font_color_link.' !important;
				}
				.site-footer a:hover,
				.site-footer a:focus,
				.site-footer a:active{
					color: '.$footer_font_color_hover.' !important;
				}
				.site-footer h1,
				.site-footer h2,
				.site-footer h3,
				.site-footer h4,
				.site-footer h5,
				.site-footer h6 {
					color: '.$footer_headings_color.' !important;
				}
				.btn-default,
				button,
				input[type="button"],
				input[type="reset"],
				input[type="submit"],
				.button,
				a.button {
					background: '.$button_color.' !important;
					color: '.$button_text_color.' !important;
				}
				.btn-default:hover,
				button:hover,
				input[type="button"]:hover,
				input[type="reset"]:hover,
				input[type="submit"]:hover,
				.button:hover,
				a.button:hover {
					background: '.$button_hover_color.' !important;
					color: '.$button_text_color.' !important;
				}
				
				.header-primary-nav .wr-mega-menu.nav-menu li a,
				.wr-megamenu-container ul.wr-mega-menu li ul.sub-menu li a{
					color: '.$main_menu_color.'!important;
				}
					
				.header-primary-nav .wr-mega-menu.nav-menu > li:first-of-type a{
					color: '.$main_menu_hover_color.'!important;
				}
				.header-primary-nav .wr-mega-menu.nav-menu > li:first-of-type a:before{
					border-color: '.$main_menu_hover_color.'!important;
				}
				.header-primary-nav .wr-mega-menu.nav-menu li a:hover,
				.header-primary-nav .wr-mega-menu.nav-menu li a:active,
				.header-primary-nav .wr-mega-menu.nav-menu li a:focus,
				.wr-megamenu-container ul.wr-mega-menu li.wr-megamenu-item:hover > a.menu-item-link,
				.header-primary-nav .wr-mega-menu.nav-menu> li.current-menu-item >a,
				.wr-megamenu-container ul.wr-mega-menu li ul.sub-menu li a:hover {
					color: '.$main_menu_hover_color.'!important;
				}
				</style>';
		echo $out;
	}
}

if ( get_option('site_custom_colors') == 'on' ) {
	add_action ( 'wp_head', 'storex_add_inline_styles' );
}

/* 6 Storex Adding inline CSS styles (end)*/

/* 7 Theme url functions (start)*/
	function storex_themes_url($path = '', $plugin = '') {

		$mu_plugin_dir = get_stylesheet_directory_uri();
		foreach ( array('path', 'plugin', 'mu_plugin_dir') as $var ) {
			$$var = str_replace('\\' ,'/', $$var); // sanitize for Win32 installs
			$$var = preg_replace('|/+|', '/', $$var);
		}

		if ( !empty($plugin) && 0 === strpos($plugin, $mu_plugin_dir) )
			$url = get_stylesheet_directory_uri();
		else
			$url = get_stylesheet_directory_uri();


		$url = set_url_scheme( $url );

		if ( !empty($plugin) && is_string($plugin) ) {
			$folder = dirname(theme_basename($plugin));

			if ( '.' != $folder )
				$url .= '/' . ltrim($folder, '/');
		}

		if ( $path && is_string( $path ) )
			$url .= '/' . ltrim($path, '/');


    /**
     * Filter the URL to the plugins directory.
     *
     * @since 2.8.0
     *
     * @param string $url    The complete URL to the plugins directory including scheme and path.
     * @param string $path   Path relative to the URL to the plugins directory. Blank string
     *                       if no path is specified.
     * @param string $plugin The plugin file path to be relative to. Blank string if no plugin
     *                       is specified.
     */
	 
		return apply_filters( 'plugins_url', $url, $path, $plugin );
	}

	function storex_theme_basename( $file ) {
		global $wp_plugin_paths;

		foreach ( $wp_plugin_paths as $dir => $realdir ) {
			if ( strpos( $file, $realdir ) === 0 ) {
				$file = $dir . substr( $file, strlen( $realdir ) );
			}
		}

		$file = wp_normalize_path( $file );
		$plugin_dir = wp_normalize_path( get_template_directory() );
		$mu_plugin_dir = wp_normalize_path( get_template_directory() );

		$file = preg_replace('#^' . preg_quote($plugin_dir, '#') . '/|^' . preg_quote($mu_plugin_dir, '#') . '/#','',$file); // get relative path from plugins dir

		$file = trim($file, '/');
		return $file;
	}

	function storex_theme_dir_url( $file ) {
		return trailingslashit( themes_url( '', $file ) );
	}
	
/* 7 Theme url functions (end)*/

/* 8 Storex get attached images (start)*/

// ----- Storex get attached images
	if ( ! function_exists( 'storex_attached_image' ) ) :
/**
 * Print the attached image with a link to the next attached image.
 *
 * @since Twenty Fourteen 1.0
 */
	function storex_attached_image() {
		$post                = get_post();
		$attachment_size     = apply_filters( 'twentyfourteen_attachment_size', array( 810, 810 ) );
		$next_attachment_url = wp_get_attachment_url();

	/*
	 * Grab the IDs of all the image attachments in a gallery so we can get the URL
	 * of the next adjacent image in a gallery, or the first image (if we're
	 * looking at the last image in a gallery), or, in a gallery of one, just the
	 * link to that image file.
	 */
		$attachment_ids = get_posts( array(
			'post_parent'    => $post->post_parent,
			'fields'         => 'ids',
			'numberposts'    => -1,
			'post_status'    => 'inherit',
			'post_type'      => 'attachment',
			'post_mime_type' => 'image',
			'order'          => 'ASC',
			'orderby'        => 'menu_order ID',
		) );

	// If there is more than 1 attachment in a gallery...
		if ( count( $attachment_ids ) > 1 ) {
			foreach ( $attachment_ids as $attachment_id ) {
				if ( $attachment_id == $post->ID ) {
					$next_id = current( $attachment_ids );
					break;
				}
			}

		// get the URL of the next image attachment...
			if ( $next_id ) {
				$next_attachment_url = get_attachment_link( $next_id );
			}

		// or get the URL of the first image attachment.
			else {
				$next_attachment_url = get_attachment_link( array_shift( $attachment_ids ) );
			}
		}

		printf( '<a href="%1$s" rel="attachment">%2$s</a>',
			esc_url( $next_attachment_url ),
			wp_get_attachment_image( $post->ID, $attachment_size )
		);
	}
	endif;
	
/* 8 Storex get attached images (end)*/

/* 9 Storex comment function (start)*/

	if ( ! function_exists( 'storex_comments' ) ) {
		function storex_comments( $comment, $args, $depth ) {
			$GLOBALS['comment'] = $comment;
			switch ( $comment->comment_type ) :
				case 'pingback' :
				case 'trackback' :
				// Display trackbacks differently than normal comments.
		?>
		
		<li <?php comment_class(); ?> id="comment-<?php comment_ID(); ?>">
			<p><?php esc_html_e( 'Pingback:', 'storex' ); ?> <?php comment_author_link(); ?> <?php edit_comment_link(esc_html__( '(Edit)', 'storex' ), '<span class="edit-link">', '</span>' ); ?></p>
		<?php
			break;
			default :
			// Proceed with normal comments.
			global $post;
		?>
		<li <?php comment_class(); ?> id="li-comment-<?php comment_ID(); ?>">
			<article id="comment-<?php comment_ID(); ?>" class="comment">
				<?php echo get_avatar( $comment, 67 ); ?>
				<header class="comment-meta comment-author vcard">
				<h2>
					<?php
						printf( '<cite class="fn comment-author">%1$s %2$s</cite>',
							get_comment_author_link(),
							// If current post author is also comment author, make it known visually.
							( $comment->user_id === $post->post_author ) ? '<span> ' . esc_html__( 'Post author', 'storex' ) . '</span>' : ''
						);?>
				</h2>
						<time class="comment-meta-time" datetime="<?php comment_date('Y-m-d') ?>T<?php comment_time('H:iP') ?>"><?php comment_date('Y.m.d') ?><?php esc_html__(', at ', 'storex');?><?php comment_time('g:i a') ?></time>
					
					<?php edit_comment_link(esc_html__( 'Edit', 'storex' ), '<p class="edit-link">', '</p>' ); ?>
				</header><!-- .comment-meta -->

				<?php if ( '0' == $comment->comment_approved ) : ?>
					<p class="comment-awaiting-moderation"><?php esc_html_e( 'Your comment is awaiting moderation.', 'storex' ); ?></p>
				<?php endif; ?>

				<div class="comment-content comment">
					<?php comment_text(); ?>
				</div><!-- .comment-content -->

				<div class="reply">
					<?php comment_reply_link( array_merge( $args, array( 'reply_text' => esc_html__( 'Reply', 'storex' ), 'depth' => $depth, 'max_depth' => $args['max_depth'] ) ) ); ?>
				</div><!-- .reply -->
				<div class="clear"></div>
			</article><!-- #comment-## -->
		<?php
			break;
		endswitch; // end comment_type check
	}
}

/* 9 Storex comment function (end)*/

/* 10 Storex pagination for gallery (start)*/

	function pt_add_next_and_number($args){
		if($args['next_or_number'] == 'next_and_number'){
			global $page, $numpages, $multipage, $more, $pagenow;
			$args['next_or_number'] = 'number';
			$prev = '';
			$next = '';
			if ( $multipage ) {
				if ( $more ) {
					$i = $page - 1;
					if ( $i && $more ) {
						$prev .= _wp_link_page($i);
						$prev .= $args['link_before']. $args['previouspagelink'] . $args['link_after'] . '</a>';
					}
					$i = $page + 1;
					if ( $i <= $numpages && $more ) {
						$next .= _wp_link_page($i);
						$next .= $args['link_before']. $args['nextpagelink'] . $args['link_after'] . '</a>';
					}
				}
			}
			$args['before'] = $args['before'].$prev;
			$args['after'] = $next.$args['after'];   
		}
		return $args;
	}

	add_filter('wp_link_pages_args','pt_add_next_and_number');

	function pt_custom_wp_link_pages( $args = '' ) {
		$defaults = array(
			'before' => '<div class="pagination">', 
			'after' => '</div>',
			'text_before' => '',
			'text_after' => '',
			'next_or_number' => 'next_and_number',
			'previouspagelink' => '<i class="fa fa-angle-left"></i>',
			'nextpagelink'=>'<i class="fa fa-angle-right"></i>', 
			'pagelink' => '%',
			'echo' => 1
		);

	$r = wp_parse_args( $args, $defaults );
	$r = apply_filters( 'wp_link_pages_args', $r );
	extract( $r, EXTR_SKIP );

	global $page, $numpages, $multipage, $more, $pagenow;

	$output = '';
		if ( $multipage ) {
			if ( 'number' == $next_or_number ) {
				$output .= $before;
				for ( $i = 1; $i < ( $numpages + 1 ); $i = $i + 1 ) {
					$j = str_replace( '%', $i, $pagelink );
					$output .= ' ';
					if ( $i != $page || ( ( ! $more ) && ( $page == 1 ) ) )
						$output .= _wp_link_page( $i );
					else
						$output .= '<span class="current">';

					$output .= $text_before . $j . $text_after;
					if ( $i != $page || ( ( ! $more ) && ( $page == 1 ) ) )
						$output .= '</a>';
					else
						$output .= '</span>';
				}
				$output .= $after;
		} 
		else {
			if ( $more ) {
				$output .= $before;
				$i = $page - 1;
				if ( $i && $more ) {
					$output .= _wp_link_page( $i );
					$output .= $text_before . $previouspagelink . $text_after . '</a>';
				}
				$i = $page + 1;
				if ( $i <= $numpages && $more ) {
					$output .= _wp_link_page( $i );
					$output .= $text_before . $nextpagelink . $text_after . '</a>';
				}
				$output .= $after;
			}
		}
	}

	if ( $echo )
		echo $output;

	return $output;
}

/* 10 Storex pagination for gallery (end)*/

/* 11 Add Max excerpt function for Mega Post(start)*/

function storex_the_excerpt_max_charlength($charlength) {
	$excerpt = get_the_excerpt();
	$charlength++;

	if ( mb_strlen( $excerpt ) > $charlength ) {
		$subex = mb_substr( $excerpt, 0, $charlength - 5 );
		$exwords = explode( ' ', $subex );
		$excut = - ( mb_strlen( $exwords[ count( $exwords ) - 1 ] ) );
		if ( $excut < 0 ) {
			return mb_substr( $subex, 0, $excut );
		} else {
			return $subex;
		}
		return '[...]';
	} else {
		return $excerpt;
	}
}
/* 11 Add Max excerpt function for Mega Post(end)*/

/* 12. Add Meta Box for PT Mega Post (start)*/

/* Adds a box to the side column on the Post and Page edit screens. */
	
	add_action('add_meta_boxes', 'storex_mega_post_meta_box');
	add_action( 'save_post', 'storex_mega_post_save_meta_box_data' );

	function storex_mega_post_meta_box(){
		add_meta_box('storex_mega_post', esc_html__('PT Mega Post Meta', 'storex'), 'storex_mega_post_meta_box_callback', 'post', 'side');
	}
/* Prints the box content. */
	function storex_mega_post_meta_box_callback($post){
	// Add a nonce field so we can check for it later.
	global $post;
	wp_nonce_field( 'storex_mega_post_meta_box_callback', 'storex_mega_post_meta_box_nonce' );
	
	// Get previous meta data
	$value = get_post_meta( $post->ID); 
	$check = isset( $value['storex_mega_post_meta'] ) ? esc_attr( $value['storex_mega_post_meta'][0] ) : 'off';

	?>
	
	<div class="pt-mega-post">
		<label for="storex_mega_post_meta"><input type="checkbox" name="storex_mega_post_meta" id="storex_mega_post_meta" <?php checked( $check, 'on' ); ?> /><?php esc_html_e('Check if you want to add this post to Aditor\'s choice post in PT Mega Post(Contentbuilder)', 'storex' ) ?></label>
	</div>
	
	<?php  }

	/**
	* When the post is saved, saves our custom data.
	*
	* @param int $post_id The ID of the post being saved.
	*/
	function storex_mega_post_save_meta_box_data($post_id){
	// If this is an autosave, our form has not been submitted, so we don't want to do anything.
	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
		return;
	}
	

	// Check if our nonce is set.

	if (!isset($_POST['storex_mega_post_meta_box_nonce'])) return;

	if (( ! wp_verify_nonce( $_POST['storex_mega_post_meta_box_nonce'], 'storex_mega_post_meta_box_callback' ))) {
		return;
	}

	if ( ! current_user_can( 'edit_post', $post_id ) ) {
		return;
	}
	
	// Sanitize user input.

	$my_data = isset( $_POST['storex_mega_post_meta'] ) && $_POST['storex_mega_post_meta'] ? 'on' : 'off';
	// Update the meta field in the database.
		update_post_meta( $post_id, 'storex_mega_post_meta', $my_data );
	}
	
/* 12. Add Meta Box for PT Mega Post (start)*/
