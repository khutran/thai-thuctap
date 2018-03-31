<?php
/*
 * The Header for our theme
 */
?>
<!DOCTYPE html>

<html <?php language_attributes(); ?>>

<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="profile" href="http://gmpg.org/xfn/11">
	<link rel="pingback" href="<?php esc_url( bloginfo( 'pingback_url' ) ); ?>">
	<?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>

<?php // Check if site turned to boxed version
	  $boxed = ''; $boxed_element = ''; $row_class = '';
	  if (get_option('site_layout')=='boxed') {$boxed = 'container'; $boxed_element = 'col-md-12 col-sm-12'; $row_class = 'row';}
?>


<div id="page" class="hfeed site <?php echo esc_attr($boxed);?>">

	<?php if ($boxed && $boxed!='') { ?>
		<div class='row'>
	<?php }
		if ( get_option( 'header_bg_color' ) != '' ) {
			$header_bg = ' style="background: '. esc_attr(get_option( 'header_bg_color' )).';"';
		}
		else{$header_bg='';}
	?>

	<header id="masthead" class="site-header <?php echo esc_attr($boxed);?>"  <?php echo $header_bg; ?>>
		<?php if (get_option( 'header_top_panel' ) == 'on' && ( has_nav_menu( 'header-top-nav' ) || get_option('top_panel_info') || is_active_sidebar('top-sidebar') ) ) : ?>
		<?php  
		/* Top panel bg options */
		if ( get_option( 'top_panel_bg' ) && get_option( 'top_panel_bg' ) != '' ) {
			$top_panel_bg = ' style="background: '. esc_attr(get_option( 'top_panel_bg' )) .';"';
		} else {
			$top_panel_bg = '';
		}
		?>
		
		<div class="header-top <?php echo esc_attr($row_class);?>" <?php echo $top_panel_bg; ?>><!-- Header top section -->
			
			<?php if (!$boxed || $boxed=='') : ?><div class="container">
				<div class="row"><?php endif; ?>
					<div class="top-widgets col-xs-12  col-md-4 col-sm-4">
						<?php if ( is_active_sidebar('top-sidebar') ) dynamic_sidebar( 'top-sidebar' ); ?>
					</div>
					<div class="info-container col-xs-12 col-md-4 col-sm-4">
						<?php if ( get_option('top_panel_info') !='' ) echo ( get_option('top_panel_info') ); ?>
					</div>

					<div class="top-nav-container col-xs-12 col-md-4 col-sm-4">
						<?php if (has_nav_menu( 'header-top-nav' )) : ?>
							<nav class="header-top-nav">
								<a class="screen-reader-text skip-link" href="#content"><?php esc_html_e( 'Skip to content', 'storex' ); ?></a>
								<?php wp_nav_menu( array('theme_location'  => 'header-top-nav') ); ?>
							</nav>
						<?php endif;?>
					</div>
				<?php if (!$boxed || $boxed=='') : ?></div>
				</div><?php endif; ?>
			</div><!-- end of Header top section -->
		<?php endif; ?>


		<div class="logo-wrapper <?php echo esc_attr($row_class);?>"><!-- Logo & hgroup -->
				<?php if (!$boxed || $boxed=='') : ?><div class="container">
					<div class="row"><?php endif; ?>

				<?php 
					$logo_position = esc_attr(get_option('site_logo_position'));
					switch ($logo_position) {
					case 'left':
						$logo_class = 'col-md-3 col-sm-3 col-xs-4';
						$sidebar_class = 'col-md-9 col-sm-9 col-xs-8';
					    break;
					case 'center':
						$logo_class = 'col-md-4 col-sm-12 col-md-offset-4 center-pos';
						$sidebar_class = 'col-md-12 col-sm-12 center-pos';
						break;
					case 'right':
						$logo_class = 'col-md-3 col-sm-6 col-md-push-9 right-pos';
						$sidebar_class = 'col-md-9 col-sm-12 col-md-pull-3 right-pos';
						break;
					default:
						$logo_class = 'col-md-3 col-sm-3 col-xs-4';
						$sidebar_class = 'col-md-9 col-sm-9 col-xs-8';					
					}
				?>

				<?php if (get_option('site_logo')): ?>
					<div class="site-logo <?php echo esc_attr($logo_class);?>">
							<a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home" title="<?php echo esc_attr( bloginfo( 'name' ) ); ?>">
								<img src="<?php echo esc_url(get_option('site_logo')) ?>" alt="<?php esc_html(bloginfo( 'description' )); ?>" />
							</a>
					</div>
				<?php else: ?>
					<div class="header-group <?php echo esc_attr($logo_class); ?>">
						<h1 id="the-title" class="site-title">

								<a href="<?php echo esc_url( home_url( '/' ) ); ?>" title="<?php echo esc_attr( get_bloginfo( 'name' ) ); ?>" rel="home">
									<?php esc_attr( bloginfo( 'name')); ?>
								</a>
						</h1>
						<h2 class="site-description"><?php esc_html(bloginfo( 'description' )); ?></h2>
					</div>
				<?php endif; ?>

				<div  class="aside-logo-container <?php echo esc_attr($sidebar_class);?>">

				<div class="row">
					<div class="col-md-8 col-xs-10">
					<?php if (has_nav_menu( 'primary-nav' )) : ?>
						<div class="header-primary-nav"><!-- Primary nav -->
							<nav class="primary-nav">
								<a class="screen-reader-text skip-link" href="#content"><?php esc_html_e( 'Skip to content', 'storex' ); ?></a>
									<?php  wp_nav_menu( array('theme_location'  => 'primary-nav', 'container' => false) ); ?>
							</nav>
						</div>
					<?php endif; ?><!-- end of Primary nav -->
					</div>

					<div class="col-md-4 col-xs-2">
                	<?php if ( is_active_sidebar( 'hgroup-sidebar' ) ) : ?>
                    	<div class="hgroup-sidebar">
                       		 <?php dynamic_sidebar( 'hgroup-sidebar' ); ?>
                    	</div>
                	<?php endif; ?>
					</div>
				</div>

				</div>
			<?php if (!$boxed || $boxed=='') : ?></div>
			</div>
		</div><?php endif; ?><!-- end of Logo & hgroup -->
				
	</header><!-- #masthead -->

	<?php if ($boxed && $boxed!='') { ?>
		</div>
		<div class='row'>
	<?php } ?>

	<div id="main" class="site-main <?php echo esc_attr($boxed_element); ?>">

