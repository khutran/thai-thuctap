<?php
/*
 * The template for displaying posts in the Gallery post format
 */
 
  /* Add responsive bootstrap classes */
$classes = array();
if (get_option('blog_frontend_layout')=='grid' && !is_single() && !is_search()&& !is_archive()) {

	$blog_cols = get_option('blog_grid_columns');

	switch ($blog_cols) {
		case 'cols-2':
			$classes[] = 'col-md-6 col-sm-12 col-xs-12';
		break;
		case 'cols-3':
			$classes[] = 'col-md-4 col-sm-6 col-xs-12';
		break;
	}
}
/* Live preview */
if( isset( $_GET['b_type']) ){
	$blog_type = esc_attr($_GET['b_type']);
	switch ($blog_type) {
		case '2cols':
			$classes[] = 'col-md-6 col-sm-12 col-xs-12';
		break;
		case '3cols':
			$classes[] = 'col-md-4 col-sm-6 col-xs-12';
		break;
	}
} else { $blog_type = ''; }
?>

<article id="post-<?php the_ID(); ?>" <?php post_class($classes); ?>>
	<div class="entry-content">
	<?php edit_post_link( esc_html__('Edit', 'storex' ), '<div class="edit-link">', '</div>' ); ?>
	
	<?php if ( has_post_thumbnail() && ! post_password_required() ) : ?>

		<div class="thumbnail-wrapper">
			<div class="post_background"></div>
			<div class="publication-time">
				<?php storex_entry_publication_time()?>
			</div>
				<?php the_post_thumbnail(); ?>
			<div class="icon"><a href="<?php esc_url(get_permalink()); ?>" title="<?php echo esc_attr( sprintf( esc_html__( 'Click to read more', 'storex' ), the_title_attribute( 'echo=0' ) ) ); ?>" rel="bookmark"><i class="fa fa-search"></i></a></div>
		</div>

	<?php endif; ?>
		
		<div class="wrapper-content-post" role="main">
		<header class="entry-header">
		
		<div class="entry-meta-post-format">
			<div class="entry-meta-top">
			<?php
				if ( is_single() ) :
					the_title( '<h1 class="entry-title">', '</h1>' );
				else :
					the_title( '<h1 class="entry-title"><a href="' . esc_url( get_permalink() ) . '" rel="bookmark">', '</a></h1>' );
				endif;
			?>
			<div class="header-entry-meta">
				<i class="fa fa-calendar"></i><?php esc_html_e('Posted on', 'storex');?>
				<span class="entry-date"><?php echo get_the_date('j F Y'); ?></span><span class="separator">|</span><?php storex_entry_author(); ?>
			</div>
			</div>
		</div>
		
	<?php // Carousel for Blog Page
	if ( get_option('show_gallery_carousel')=='on' && !is_single() && get_post_gallery()) : 

		//$images = get_post_gallery( get_the_ID(), false );
		$count = 0;
		$transition_type = esc_attr((get_option('gallery_carousel_effect') != '') ? get_option('gallery_carousel_effect') : 'fade');

		if (get_option('show_gallery_carousel')=='on') {
				$extra_class = 'paginated';
				$extra_count = 5;
		}

		if ( get_post_gallery() ) {

			$gallery = get_post_gallery( get_the_ID(), false );
			$img_ids = isset($gallery['ids'])? $gallery['ids'] : 0;
			$img_ids_array = explode(",", $img_ids);?>

			<div class="entry-carousel">

			<div class="blog-gallery <?php echo esc_attr($extra_class); ?>"
						data-owl="container" 
						data-owl-slides="1" 
						data-owl-type="simple"
						data-owl-navi="false" 
						data-owl-pagi="true" 
						data-owl-transition="<?php echo esc_attr($transition_type); ?>">

			<?php foreach( $img_ids_array as $img_id ) :
				if ($count > $extra_count) {
					continue;
				}?>
				<div class="slide">
					<?php echo wp_get_attachment_image( $img_id, 'blog-featured-image-thumb' ); ?>
				</div>
				<?php $count++;
			endforeach;?>

			</div></div>
		<?php }

	endif;
?>	
		
		</header><!-- .entry-header -->

	<div class="content-post">
		<?php 
			if ( get_option('show_gallery_carousel')=='on' && !is_single() && get_post_gallery()) { 
				global $post;
				echo strip_shortcodes($post->post_content); 
			} else {
				the_content( apply_filters( 'storex_more', esc_html__('Continue Reading...', 'storex')) );
			} 
		?>
		<?php wp_link_pages( array(
				'before'      => '<div class="page-links"><span class="page-links-title">' . esc_html__( 'Pages:', 'storex' ) . '</span>',
				'after'       => '</div>',
				'link_before' => '<span>',
				'link_after'  => '</span>',
		) ); ?>
	</div><!-- .entry-content -->

		<div class="entry-meta">
			<div class="entry-meta-bottom">
				<?php storex_entry_post_cats(); ?>
				<?php if ( ! post_password_required() ) { storex_entry_comments_counter(); } ?>
				<?php storex_entry_post_views(); ?>
			</div>
			
				<?php
					if ( is_singular()){
						if ( get_option('blog_share_buttons')=='on'){
							storex_share_buttons_output();
						} 
					} 
				?>
		</div><!-- .entry-meta -->

	<?php if ( is_singular() ) : ?>

		<?php if ( get_the_author_meta( 'description' ) && is_multi_author() ) : ?>
			<?php get_template_part( 'author-bio' ); ?>
		<?php endif; ?>
		
	<?php endif; ?>
		</div>
</div>
</article><!-- #post-## -->
