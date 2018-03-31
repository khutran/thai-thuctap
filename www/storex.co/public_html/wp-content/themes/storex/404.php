<?php
/*
 * The template for displaying 404 pages (Not Found)
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
					<h1 class="title"><?php esc_html_e('404 Page Not Found', 'storex'); ?></h1>
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

			<div id="post-0" class="post error-404 no-results not-found">

			<div class="page-content">
				<p class="oops-404"><?php esc_html_e( 'Oops! Page Not Found', 'storex' ); ?></p>
				<p class="info-404"><?php esc_html_e( 'It looks like nothing was found at this location. Try use the search.', 'storex' ); ?></p>

				<?php get_search_form(); ?>

				<div class="sad-smail">
					<img src="<?php echo get_stylesheet_directory_uri(); ?>/img/sad-smaile.png" alt="Page Not Found" width="370" height="310">
				</div>

			</div><!-- .page-content -->
			</div><!-- #post-0 -->
		</div><!-- #content -->
	</div>
<?php if (!$boxed || $boxed=='') : ?></div><?php endif; ?>
</div><!--.main -->

<?php get_footer(); ?>
