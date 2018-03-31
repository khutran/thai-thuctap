<?php
/**
 * The template for displaying Category pages
 *
 * @link http://codex.wordpress.org/Template_Hierarchy
 *
 * @package WordPress
 * @subpackage Twenty_Fourteen
 * @since Twenty Fourteen 1.0
 */

get_header(); ?>

<?php // Check if site turned to boxed version
	  $boxed = ''; $boxed_element = ''; $row_class = '';
	  if (get_option('site_layout')=='boxed') {$boxed = 'container'; $boxed_element = 'col-md-12 col-sm-12'; $row_class = 'row';}
	 $rb_color = pt_get_post_pageribbon($post->ID);
?>

	<div class="header-stripe" style="background-color: <?php echo esc_attr($rb_color); ?>">
		<?php if (!$boxed || $boxed=='') : ?><div class="container"><?php endif; ?>
			<div class="row">
				<div class="col-md-4 col-sm-4 col-xs-12">
					<?php if ( get_option('site_breadcrumbs')=='on') {pt_breadcrumbs();}?>    
				</div>
				
				<div class="col-md-4 col-sm-4 col-xs-12">
					<h1 class="title"><?php printf( esc_html__( 'Category Archives: %s', 'storex' ), single_cat_title( '', false ) ); ?></h1>
				</div>
				
				<div class="col-md-4 col-sm-4 col-xs-12">
				<?php if(get_option('back_to_home_button') && get_option('back_to_home_button')=='on' ): ?>
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

			<?php if ( have_posts() ) : ?>

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
					// Previous/next page navigation.
					$blog_pagination = get_option('blog_pagination');
					if ( ($wp_query->max_num_pages > 1) && ($blog_pagination == 'infinite') ) : ?>
						<span class="pt-get-more-posts"><?php esc_html_e( 'Show More Posts', 'storex'); ?></span>
					<?php else : ?>
						<?php storex_content_nav(); ?>
					<?php endif; 
				else :
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
