<?php
/*
 * The template for displaying image attachments
 */

// Retrieve attachment metadata.
$metadata = wp_get_attachment_metadata();

get_header();

?>

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

			<?php
				// Start the Loop.
				while ( have_posts() ) : the_post();
			?>
				<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

						<div class="entry-attachment"><!-- .entry-attachment (start)-->

							<div class="attachment-image">
								<div class="publication-time">
									<?php storex_entry_publication_time() ?>
								</div>
									<?php storex_attached_image(); ?>
							</div>
						
						</div><!-- .entry-attachment (end)-->
					
						<div class="entery-content"><!-- .entry-content (start)-->
						
							<?php edit_post_link( esc_html__( 'Edit', 'storex' ), '<span class="edit-link">', '</span>' ); ?>
							
							<header><!-- .entry-header (start)-->
								<h2 class="entry-title"><?php the_title(); ?></h2>
							</header><!-- .entry-header (end)-->

							<?php if ( has_excerpt() ) : ?>
								<div class="entry-caption"><!-- .entry-caption (start)-->
									<?php the_excerpt(); ?>
								</div><!-- .entry-caption (end)-->
							<?php endif; ?>
							
													<?php if ( ! empty( $post->post_content ) ) : ?>
						<div class="entry-description">
							<?php echo $post->post_content; ?>
						</div><!-- .entry-description -->
						<?php endif; ?>
						
							<div class="entry-meta"><!-- .entry-meta (start)-->
								<div class="comments"><?php storex_entry_comments_counter(); ?></div>
								<div class="source"><i class="fa fa-arrow-circle-o-right"></i><span class="source-title"><?php esc_html_e('Source Image:&nbsp;', 'storex'); ?></span>
									<?php 
									$metadata = wp_get_attachment_metadata();
									printf( '<span class="attachment-meta full-size-link"><a href="%1$s" title="%2$s">%3$s (%4$s &times; %5$s)</a></span>',
										esc_url( wp_get_attachment_url() ),
										esc_attr__( 'Link to full-size image', 'storex' ),
										esc_html__( 'Full resolution', 'storex' ),
										$metadata['width'],
										$metadata['height']
									);
									?>
								</div>
							</div><!-- .entry-meta (end)-->
							
						</div><!-- .entry-content (end)-->

					<?php if( get_option('post_pagination')=='on' ) { ?>
						<nav id="image-navigation" class="navigation image-navigation"><!-- #image-navigation (start)-->
							<div class="nav-links"><!-- .nav-links (start)-->
							<?php previous_image_link( false, wp_kses(__( '<i class="fa fa-angle-left"></i>&nbsp;&nbsp;&nbsp;Previous Image', 'storex' ), $allowed_html=array('i' => array('class'=>array())) )); ?>
							<?php next_image_link( false, wp_kses(__( 'Next Image&nbsp;&nbsp;&nbsp;<i class="fa fa-angle-right"></i>', 'storex' ), $allowed_html=array('i' => array('class'=>array())) )); ?>
							
							</div><!-- .nav-links (end)-->
						</nav><!-- #image-navigation (end)-->
					<?php } ?>
					
					<div class="entry-meta-bottom"><!-- .entry-meta-bottom (start)-->
						<?php if ( function_exists( 'storex_entry_post_views' ) ) { storex_entry_post_views(); } ?>
						<?php if ( get_option('blog_share_buttons')=='on'){storex_share_buttons_output();} ?>
					</div><!-- .entry-meta-bottom (end)-->
					
				</article><!-- #post-## -->

				<?php comments_template(); ?>

				<?php endwhile; // end of the loop. ?>

			</div><!-- #content -->
			<?php get_sidebar(); ?>
		</div>
	
		<?php if (!$boxed || $boxed=='') : ?></div><?php endif; ?>
</div><!--.main -->
<?php get_footer(); ?>
