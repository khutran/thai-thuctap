<?php
/**
 * Template for Related Posts by Taxonomy widget and shortcode - post thumbnail template
 * This template uses a function that comes whith the plugin. See the documentation
 * See http://codex.wordpress.org/Post_Thumbnails if you want to use your own html markup.
 * See the documentation on how you can use your own templates. 
 *
 * @package related posts by taxonomy
 *
 * The following variables are available:
 * @var array $related_posts Array with full related posts objects or empty array.
 */
?>

<?php
/**
 * Note: global a$post; is run before this template by the widget and the shortcode.
 */
?>
<?php if ( $related_posts ) : ?>

	<ul class="related">
		<?php foreach($related_posts as $post) : ?>
			<li>
				<a href="<?php echo esc_url(get_post_permalink( $post->ID )); ?>">
				<div class="wrapper-block">
				<i class="fa fa-search"></i>
					<div class="block-animate"></div>
					<?php echo get_the_post_thumbnail( $post->ID, 'storex-related-thumb', array( 'data-role'=>'meteor', 'data-title'=>$post->post_title)); ?>
				</div>
				<h6><?php echo esc_attr(get_the_title($post->ID)); ?></h6>
				</a>
			</li>
		<?php endforeach; ?>
	</ul>
<?php else : ?>

<p><?php esc_html_e( 'No related posts found', 'storex' ); ?></p>

<?php endif; ?>

<?php
/**
 * note: wp_reset_postdata(); is run after this template by the widget and the shortcode
 */
?>