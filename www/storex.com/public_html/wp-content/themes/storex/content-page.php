<?php
/*
 * The template used for displaying page content
 */
?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

	<?php edit_post_link( esc_html__('Edit', 'storex' ), '<span class="edit-link">', '</span>' ); ?>
	
	<?php
		// Page thumbnail.
		if ( has_post_thumbnail() && ! post_password_required() ) : ?>
			<div class="thumbnail-wrapper">
				<?php the_post_thumbnail(); ?>
			</div>
		<?php endif;?>

	<div class="entry-content">
		<?php the_content(); ?>
		
		<?php wp_link_pages( array(
			'before'      => '<div class="page-links"><span class="page-links-title">' . esc_html__( 'Pages:', 'storex' ) . '</span>',
			'after'       => '</div>',
			'link_before' => '<span>',
			'link_after'  => '</span>',
		) );

		?>
	</div><!-- .entry-content -->
</article><!-- #post-## -->
