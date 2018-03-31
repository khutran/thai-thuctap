<?php
/**
 * The template for displaying Search Results pages
 *
 * @package WordPress
 * @subpackage Twenty_Fourteen
 * @since Twenty Fourteen 1.0
 */

get_header(); ?>

<?php // Check if site turned to boxed version
	  $boxed = ''; $boxed_element = ''; $row_class = '';
	  if (get_option('site_layout')=='boxed') {$boxed = 'container'; $boxed_element = 'col-md-12 col-sm-12'; $row_class = 'row';}
?>

    <div class="header-stripe">
        <?php if (!$boxed || $boxed=='') : ?><div class="container"><?php endif; ?>
             <div class="row">
                <div class="col-md-4 col-sm-4 col-sx-12">
                    <?php if ( get_option('site_breadcrumbs')=='on') {pt_breadcrumbs();}?>       
                </div>
				
				<div class="col-md-4 col-sm-4 col-sx-12">
					<h1 class="title"><?php printf( esc_html__( 'Search Results for: %s', 'storex' ), get_search_query() ); ?></h1>
				</div>
				
				<div class="col-md-4 col-sm-4 col-sx-12">
				<?php if(get_option('back_to_home_button')&&get_option('back_to_home_button')=='on' ): ?>
					<a class="back-to-home" href="<?php echo esc_url(home_url( '/' )); ?>">&#8592; <?php esc_html_e( 'Back to Home', 'storex' ); ?></a>
				<?php endif ?>
				</div>
            </div>
        <?php if (!$boxed || $boxed=='') : ?></div><?php endif; ?>
    </div>


<?php if (!$boxed || $boxed=='') : ?><div class="container"><?php endif; ?>
	<div class="row">
		<?php if ( pt_show_layout()=='layout-one-col' ) { $content_class = "col-xs-12 col-md-12 col-sm-12"; } 
			  elseif ( pt_show_layout()=='layout-two-col-left' ) { $content_class = "col-xs-12 col-md-9 col-sm-9 col-md-push-3"; }
			  else { $content_class = "col-xs-12 col-md-9 col-sm-9"; } ?>

		<div id="content" class="site-content <?php echo esc_attr($content_class); ?>" role="main">

			<?php 
			global $query_string;
			query_posts( $query_string . "&s=$s" . '&posts_per_page=5' );
			$key = esc_html($s);

			if ( have_posts() ) : 
			?>
			
			<?php get_search_form(); ?>
			
				<?php
					// Start the Loop.
					while ( have_posts() ) : the_post();

						/*
						 * Include the post format-specific template for the content. If you want to
						 * use this in a child theme, then include a file called called content-___.php
						 * (where ___ is the post format) and that will be used instead.
						 */
						get_template_part( 'content', get_post_format() );

					endwhile;
					
					// Post navigation.
					
					$blog_pagination = esc_attr(get_option('blog_pagination'));
					if ( ($wp_query->max_num_pages > 1) && ($blog_pagination == 'infinite') ) : ?>
						<span class="pt-get-more-posts"><?php esc_html_e('Show More Posts', 'storex'); ?></span>
					<?php else : ?>
						<?php storex_content_nav(); ?>
					<?php endif; ?>

				<?php else :
					// If no content, include the "No posts found" template.
					get_template_part( 'content', 'none' );

				endif;
			?>

		</div><!-- #content -->
		<?php get_sidebar(); ?>
	</div>
<?php if (!$boxed || $boxed=='') : ?></div><?php endif; ?>
</div><!--.main -->
<?php get_footer(); ?>
