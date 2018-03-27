<?php
/**
 * Template Name: Wide Page Template 
 *
 * Description: A page template that provides a key component of WordPress as a CMS
 * by meeting the need for a carefully crafted introductory page. The front page template
 * in Twenty Twelve consists of a page content area for adding text, images, video --
 * anything you'd like -- followed by front-page-only widgets in one or two columns.
 *
 * @package WordPress
 * @subpackage Plum_Tree
 * @since Plum Tree 0.1
 */

get_header(); ?>

<?php // Check if site turned to boxed version
	  $boxed = ''; $boxed_element = ''; $row_class = '';
	  if (get_option('site_layout')=='boxed') {$boxed = 'container'; $boxed_element = 'col-md-12 col-sm-12'; $row_class = 'row';}
	  $rb_color = pt_get_post_pageribbon($post->ID);
	  ?>

<div class="header-stripe" style="background-color:<?php echo esc_attr($rb_color); ?>">
		<?php if (!$boxed || $boxed=='') : ?><div class="container"><?php endif; ?>
			<div class="row">
				<div class="col-md-4 col-sm-4 col-xs-12">
					<?php if ( get_option('site_breadcrumbs')=='on') {pt_breadcrumbs();}?>
				</div>
				
				<div class="col-md-4 col-sm-4 col-xs-12">
					<h1 class="title"><?php the_title(); ?></h1>
				</div>
				
				<div class="col-md-4 col-sm-4 col-xs-12">
				<?php if(get_option('back_to_home_button') && get_option('back_to_home_button')=='on' ): ?>
					<a class="back-to-home" href="<?php echo esc_url(home_url( '/' )); ?>">&#8592; <?php esc_html_e( 'Back to Home', 'storex' ); ?></a>
				<?php endif ?>
				</div>
			</div>
		<?php if (!$boxed || $boxed=='') : ?></div><?php endif; ?>
		</div>

            <?php if ( have_posts() ) : ?>
                <?php while ( have_posts() ) : the_post(); ?>
	
				<section id="content" role="main" class="site-content"><!-- Main content -->
					<div class="entry-content"><?php the_content();?></div><!-- .entry-content -->
				<?php endwhile; ?>
            <?php endif; ?>

        </section><!-- Main content -->

</div><!-- #Main--> 

<?php get_footer(); ?>


