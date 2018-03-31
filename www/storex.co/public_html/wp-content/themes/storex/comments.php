<?php
/**
 * The template for displaying Comments
 *
 * The area of the page that contains comments and the comment form.
 *
 * @package WordPress
 * @subpackage Twenty_Fourteen
 * @since Twenty Fourteen 1.0
 */

/*
 * If the current post is protected by a password and the visitor has not yet
 * entered the password we will return early without loading the comments.
 */
if ( post_password_required() ) {
	return;
}
?>

<div id="comments" class="comments-area">

	<?php if ( have_comments() ) : ?>

	<h2>
		<?php
			printf( esc_html(_n( '1 Comment', '%1$s Comments', get_comments_number(), 'storex' )),
				number_format_i18n( get_comments_number() ), get_the_title() );
		?>
	</h2>

	<ol class="comment-list">
		<?php wp_list_comments( array('callback' => 'storex_comments',
									  'style'      => 'ol',
									  'short_ping' => true,
									  'avatar_size'=> 72,
							) );
		?>
	</ol><!-- .comment-list -->
	
		<?php if ( get_comment_pages_count() > 1 && get_option( 'page_comments' ) ) : ?>
			<?php $comment_navi_type = (get_option('comments_pagination')) ? get_option('comments_pagination') : 'numeric'; 
				  storex_comments_nav( $comment_navi_type ); ?>
		<?php endif; // Check for comment navigation. ?>
		


	<?php if ( ! comments_open() ) : ?>
	<p class="no-comments"><?php esc_html_e( 'Comments are closed.', 'storex' ); ?></p>
	<?php endif; ?>

	<?php endif; ?>


<?php 
	$srgs=array(
		'comment_field' => '<p class="comment-form-comment"><label for="comment">' . esc_html__( 'Message Text ', 'storex' ) . '</label><textarea id="comment" name="comment" cols="42" rows="8" aria-required="true"></textarea></p>',
		'comment_notes_after' => '',
		'label_submit' => esc_html__( 'Send', 'storex'),
		'title_reply' => esc_html__( 'Leave a Comment', 'storex')
	);
?>

	<?php comment_form($srgs); ?>


</div><!-- #comments -->
