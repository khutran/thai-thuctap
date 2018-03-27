<?php

if ( ! function_exists( 'storex_content_nav' ) ) :
/**
 * Display navigation to next/previous set of posts when applicable.
 * Based on paging nav function from Twenty Fourteen
 */

function storex_content_nav() {
	// Don't print empty markup if there's only one page.
	if ( $GLOBALS['wp_query']->max_num_pages < 2 ) {
		return;
	}

	$paged        = get_query_var( 'paged' ) ? intval( get_query_var( 'paged' ) ) : 1;
	$pagenum_link = html_entity_decode( get_pagenum_link() );
	$query_args   = array();
	$url_parts    = explode( '?', $pagenum_link );

	if ( isset( $url_parts[1] ) ) {
		wp_parse_str( $url_parts[1], $query_args );
	}

	$pagenum_link = remove_query_arg( array_keys( $query_args ), $pagenum_link );
	$pagenum_link = trailingslashit( $pagenum_link ) . '%_%';

	$format  = $GLOBALS['wp_rewrite']->using_index_permalinks() && ! strpos( $pagenum_link, 'index.php' ) ? 'index.php/' : '';
	$format .= $GLOBALS['wp_rewrite']->using_permalinks() ? user_trailingslashit( 'page/%#%', 'paged' ) : '?paged=%#%';

	// Set up paginated links.
	$links = paginate_links( array(
		'base'     => $pagenum_link,
		'format'   => $format,
		'total'    => $GLOBALS['wp_query']->max_num_pages,
		'current'  => $paged,
		'mid_size' => 2,
		'add_args' => array_map( 'urlencode', $query_args ),
		'prev_text' => wp_kses(__('<i class="fa fa-angle-left"></i>', 'storex'), $allowed_html=array('i' => array('class'=>array())) ),
		'next_text' => wp_kses(__('<i class="fa fa-angle-right"></i>', 'storex'), $allowed_html=array('i' => array('class'=>array())) ),
		'type'      => 'plain',
	) );

	if ( $links ) :

	?>
	<nav class="navigation paging-navigation">
		<h1 class="screen-reader-text"><?php esc_html_e( 'Posts navigation', 'storex' ); ?></h1>
			<?php echo $links; ?>
	</nav><!-- .navigation -->
	<?php
	endif;
}
endif;

if ( ! function_exists( 'storex_post_nav' ) ) :
/**
 * Display navigation to next/previous post when applicable.
 */
function storex_post_nav() {
	// Don't print empty markup if there's nowhere to navigate.
	$previous = ( is_attachment() ) ? get_post( get_post()->post_parent ) : get_adjacent_post( false, '', true );
	$next     = get_adjacent_post( false, '', false );

	if ( ! $next && ! $previous ) {
		return;
	}

	?>
	<nav class="single-post-navi">
		<h1 class="screen-reader-text"><?php esc_html_e( 'Post navigation', 'storex' ); ?></h1>
		<div class="nav-links">
			<?php
			if ( is_attachment() ) :
				previous_post_link( '%link', wp_kses(__( '<span class="meta-nav">Published In</span>', 'storex' ), $allowed_html=array('span' => array('class'=>array())) ));
			else :
				previous_post_link( wp_kses('<span class="prev">%link</span>', $allowed_html=array('span' => array('class'=>array())) ), esc_html__( 'Previous Post', 'storex' ) );
				next_post_link( wp_kses('<span class="next">%link</span>', $allowed_html=array('span' => array('class'=>array()))), esc_html__( 'Next Post', 'storex' ) );
			endif;
			?>
		</div><!-- .nav-links -->
	</nav><!-- .navigation -->
	<?php
}
endif;

if ( !function_exists( 'storex_comments_nav' ) ) :
/**
 * Display comments navigation (2 styles available = numeric nav or newest/oldest nav).
 */
function storex_comments_nav($nav_type) {
	if ($nav_type == 'numeric') { ?>
        <nav class="navigation comment-numeric-navigation">
            <h1 class="screen-reader-text section-heading"><?php esc_html_e( 'Comment navigation', 'storex' ); ?></h1>
            <span class="page-links-title"><?php esc_html_e('Comments Navigation:', 'storex'); ?></span>
            <?php paginate_comments_links( array(
				'prev_text' => wp_kses(__('<i class="fa fa-angle-left"></i>', 'storex'), $allowed_html=array('i' => array('class'=>array())) ),
				'next_text' => wp_kses(__('<i class="fa fa-angle-right"></i>', 'storex'),$allowed_html=array('i' => array('class'=>array())) ),
              	'type'      => 'plain',
              )); ?>  			
       	</nav>
	<?php } elseif ($nav_type == 'newold') { ?>
        <nav class="navigation comment-navigation">
            <h1 class="screen-reader-text section-heading"><?php esc_html_e( 'Comment navigation', 'storex' ); ?></h1>
            <div class="prev"><?php previous_comments_link( esc_html__( 'Older Comments', 'storex' ) ); ?></div>
            <div class="next"><?php next_comments_link( esc_html__( 'Newer Comments', 'storex' ) ); ?></div>
        </nav>
	<?php }
}
endif;