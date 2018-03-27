<?php
/**
 * Template Name: Front Page
 */

get_header(); ?>

<?php /* Check if site turned to boxed version */
      $boxed = ''; $boxed_element = ''; $row_class = '';
      if (get_option('site_layout')=='boxed') {$boxed = 'container'; $boxed_element = 'col-md-12 col-sm-12'; $row_class = 'row';}
?>

<?php if ($boxed || !$boxed=='') : ?><div class="container">
    <div class="row"><?php endif; ?>

        <div id="content" class="site-content " role="main">

            <?php if ( have_posts() ) : ?>
                <?php while ( have_posts() ) : the_post(); ?>
                    <div class="entry-content">
                        <?php the_content(); ?>
                    </div><!-- .entry-content -->
                <?php endwhile; ?>
            <?php endif; ?>
		
		
		<div class="front-widget-area <?php echo $boxed_element;?>">
			<?php if (!$boxed || $boxed=='') : ?><div class="container">
				<div class="row"><?php endif; ?>
				
					<div class="col-xs-12 col-sm-6 col-md-3">
						<?php if(is_active_sidebar('front-page-bottom-sidebar-1')): ?>
							<?php dynamic_sidebar('front-page-bottom-sidebar-1'); ?>
						<?php endif;?>
					</div>
				
					<div class="col-xs-12 col-sm-6 col-md-3">
						<?php if(is_active_sidebar('front-page-bottom-sidebar-2')): ?>
							<?php dynamic_sidebar('front-page-bottom-sidebar-2'); ?>
						<?php endif;?>	
					</div>
				
					<div class="col-xs-12 col-sm-6 col-md-3">
						<?php if(is_active_sidebar('front-page-bottom-sidebar-3')): ?>
							<?php dynamic_sidebar('front-page-bottom-sidebar-3'); ?>
						<?php endif;?>	
					</div>
				
					<div class="col-xs-12 col-sm-6 col-md-3">
						<?php if(is_active_sidebar('front-page-bottom-sidebar-4')): ?>
							<?php dynamic_sidebar('front-page-bottom-sidebar-4'); ?>
						<?php endif; ?>
					</div>
					
				<?php if (!$boxed || $boxed=='') : ?></div>
			</div>
		</div><?php endif; ?>
        </div><!--.site-content-->
<?php if ($boxed || !$boxed=='') : ?>   </div>

</div><?php endif; ?>
</div><!--.main -->


<?php get_footer(); ?>