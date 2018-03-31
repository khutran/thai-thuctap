<?php
/*
 * Template Name:  Page Stripe Template
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
				
				<div class="col-md-4 col-sm-4 col-sx-12">
					<h1 class="title"><?php the_title(); ?></h1>	
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

            <?php if ( have_posts() ) : ?>
                <?php while ( have_posts() ) : the_post(); ?>
                    <div class="entry-content">
                        <?php the_content(); ?>
                    </div><!-- .entry-content -->
                <?php endwhile; ?>
            <?php endif; ?>

        </div><!--.site-content-->
        <?php get_sidebar(); ?>
    </div>
<?php if (!$boxed || $boxed=='') : ?></div><?php endif; ?>
</div><!--.main -->
<?php get_footer(); ?>