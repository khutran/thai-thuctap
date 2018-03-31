<?php
/**
 * The template for displaying the footer
 *
 * Contains footer content and the closing of the #main and #page div elements.
 *
 * @package WordPress
 * @subpackage Twenty_Fourteen
 * @since Twenty Fourteen 1.0
 */
?>

<?php // Check if site turned to boxed version
	  $boxed = ''; $boxed_element = ''; $row_class = '';
	  if (get_option('site_layout')=='boxed') {$boxed = 'container'; $boxed_element = 'col-md-12 col-sm-12'; $row_class = 'row';}
?>

		<?php if ($boxed && $boxed!='') { ?>
			</div>
			<div class='row'>
		<?php } ?>


<?php if ( class_exists('Woocommerce') ): ?>
	<?php  if(is_shop() || is_product() && is_active_sidebar('footer-bottom-shop')): ?>
					
	<?php if (!$boxed || $boxed=='') : ?><div class="container">
										<div class="row"><?php endif; ?>
											<div class="shop-bottom-sidebar col-xs-12 col-sm-12 col-md-12">
												<?php dynamic_sidebar('footer-bottom-shop'); ?>
											</div>
	<?php if (!$boxed || $boxed=='') : ?></div>
									</div><?php endif; ?>
	<?php endif ?>
<?php endif ?>
		
		<footer id="colophon" class="site-footer <?php echo esc_attr($boxed_element);?>">

			<?php 
				if (get_option('site_footer_top_background_option') && get_option('site_footer_top_background_option')!=''){
					$site_footer_top_background_option = get_option('site_footer_top_background_option');
				}
				else{$site_footer_top_background_option='';}
			?>
		
		<?php if(is_active_sidebar('footer-top-sidebar-1') || is_active_sidebar('footer-top-sidebar-2')): ?>
			<div class="footer-top widget-area <?php echo esc_attr($row_class);?>" style="background:<?php echo esc_attr($site_footer_top_background_option); ?>;">	
				<?php if (!$boxed || $boxed=='') : ?><div class="container">
				<div class="row"><?php endif; ?>

				<div class="col-xs-12 col-sm-6 col-md-6">
					<?php if(is_active_sidebar('footer-top-sidebar-1')): ?>
						<?php dynamic_sidebar('footer-top-sidebar-1'); ?>
					<?php endif;?>
				</div>

				<div class="col-xs-12 col-sm-6 col-md-6">
					<?php if(is_active_sidebar('footer-top-sidebar-2')): ?>
						<?php dynamic_sidebar('footer-top-sidebar-2'); ?>
					<?php endif;?>
				</div>
				
				<?php if (!$boxed || $boxed=='') : ?></div></div><?php endif; ?>
			</div>
		<?php endif; ?>

		<?php 
				if (get_option('site_middle_background_option') && get_option('site_middle_background_option')!=''){
					$site_middle_background_option = get_option('site_middle_background_option');
				}
				else{$site_middle_background_option='';}
		?>
			<div class="footer-middle widget-area <?php echo esc_attr($row_class);?>" style="background:<?php echo esc_attr($site_middle_background_option);?>">
				<?php if (!$boxed || $boxed=='') : ?><div class="container">
					<div class="row"><?php endif; ?>
					<?php
						if (get_option('footer_bg_img') && get_option('footer_bg_img')!=''){
						$footer_bg_img_url = get_option('footer_bg_img');
					}
					else{$footer_bg_img_url='';}
					
					if (get_option('footer_bg_img_position') && get_option('footer_bg_img_position')!=''){
						$footer_bg_img_position = get_option('footer_bg_img_position');
						switch($footer_bg_img_position){
							case "right":
								$storex_footer_bg_position='96%';
								break;
							case "left":
								$storex_footer_bg_position='4%';
								break;
						}
					}
					else{$storex_footer_bg_position='';}
					
					?>
						<div class="fotter-bg-img" style="background: url(<?php  echo esc_url($footer_bg_img_url)?>)  no-repeat transparent; background-position:<?php echo esc_attr($storex_footer_bg_position)?> 90%">
					
							<div class="col-xs-12 col-sm-6 col-md-3">
								<?php if ( is_active_sidebar( 'footer-sidebar-1' ) ) : ?>
									<?php dynamic_sidebar( 'footer-sidebar-1' ); ?>
								<?php endif; ?>
							</div>
							<div class="col-xs-12 col-sm-6 col-md-3">
								<?php if ( is_active_sidebar( 'footer-sidebar-2' ) ) : ?>
								<?php dynamic_sidebar( 'footer-sidebar-2' ); ?>
								<?php endif; ?>
							</div>
							<div class="col-xs-12 col-sm-6 col-md-3">
								<?php if ( is_active_sidebar( 'footer-sidebar-3' ) ) : ?>
									<?php dynamic_sidebar( 'footer-sidebar-3' ); ?>
									<?php endif; ?>
							</div>
							<div class="col-xs-12 col-sm-6 col-md-3">
								<?php if ( is_active_sidebar( 'footer-sidebar-4' ) ) : ?>
									<?php dynamic_sidebar( 'footer-sidebar-4' ); ?>
								<?php endif; ?>
							</div>
						</div>
						
					<?php if (!$boxed || $boxed=='') : ?></div>
				</div><?php endif; ?>
			</div>

			<?php 
				if (get_option('site_footer_bottom_background_option') && get_option('site_footer_bottom_background_option')!=''){
					$site_bottom_background_option = get_option('site_footer_bottom_background_option');
				}
				else{$site_bottom_background_option='';}
				
			?>
			
			<div id="footer-bottom" style="background:<?php echo esc_attr($site_bottom_background_option); ?>;" class="<?php echo esc_attr($row_class);?> footer-bottom">
				<?php if (!$boxed || $boxed=='') : ?><div class="container">
					<div class="row"><?php endif; ?>
						<div class="col-xs-12 col-sm-6 col-md-6">
							<?php if (has_nav_menu('footer-nav')) : ?><!-- Footer navigation -->
								<nav id="site-navigation-footer" class="footer-navigation">
									<?php wp_nav_menu( array('theme_location'  => 'footer-nav') ); ?>							
								</nav>
						<?php endif; ?><!-- Footer navigation -->

						<div class="site-info">
							<?php $copyright = esc_attr(get_option('site_copyright'));
							if ($copyright != '') {
								echo esc_attr($copyright);
							} else {
								echo 'Storex &copy; 2015'.esc_html__(' Theme by Themes Zone. All rights reserved', 'storex');
							}
							?>
						</div>

						</div>

						<div class="col-xs-12 col-sm-6 col-md-6">
							<?php if ( is_active_sidebar( 'footer-bottom' ) ) : ?>
                            	<?php dynamic_sidebar( 'footer-bottom' ); ?>
                        	<?php endif; ?>
						</div>

					<?php if (!$boxed || $boxed=='') : ?></div>
				</div><?php endif; ?>
			</div>
			
		</footer><!-- #colophon -->
		<?php if ($boxed && $boxed!='') { ?>
			</div>
		<?php } ?>
</div><!-- #page -->

		<?php wp_footer(); ?>
	</body>
</html>