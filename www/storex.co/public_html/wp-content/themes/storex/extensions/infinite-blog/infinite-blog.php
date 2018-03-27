<?php

if ( ! function_exists( 'pt_infinite_blog' ) ) :

/**
 * Adding infinite blog posts.
 */
add_filter('wp_footer', 'pt_infinite_blog');

function pt_infinite_blog() {
	global $wp_query;

?>

	<script type="text/javascript">
		jQuery(document).ready(function($){
			$(window).load(function(){

				var page = 2;
				var total = <?php echo esc_html($wp_query->max_num_pages); ?>;
				var loading = false;

				$('.pt-get-more-posts').on('click', function(){
					var post_type = 'post';
					if ( !loading ) {
						if (page > total){
							return false;
						} else {
							loading = true;
							loadPosts(page);
						}
						page++;
					}
				});

				// Ajax loading Function
				function loadPosts(pageNumber){
					$.ajax({
						url: "<?php echo esc_url(site_url());  ?>/wp-admin/admin-ajax.php",
						type:'POST',
						data: "action=get_more&page_no=" + pageNumber,
			            beforeSend : function(){
			            	if( total>=page ){
			                	$('.pt-get-more-posts').hide();
			                    $("#content").append(
			                    	'<div id="temp_load"><i class="fa fa-refresh fa-spin"></i>&nbsp;Loading... \
			                        </div>');
			            	};
			            },
						success: function(html){
							$("#temp_load").remove();
							$("#content").append(html);    // This will be the div where our content will be loaded*/
							if( total>page ){ $('.pt-get-more-posts').show(); }
							loading = false;
						},
					});
				};

			});
		});
	</script>

<?php 
}

endif;

/* Loop Function to dynamicaly added posts or products */

add_action('wp_ajax_get_more', 'pt_infinite_loop');           // for logged in user
add_action('wp_ajax_nopriv_get_more', 'pt_infinite_loop');    // if user not logged in

function pt_infinite_loop() {
	global $wp_query;
	$paged           = $_POST['page_no'];
	$posts_per_page  = get_option('posts_per_page');

	$the_query = new WP_Query(
		array( 
			'post_status' => 'publish',
			'ignore_sticky_posts' => 1,
			'paged' => $paged,
			'posts_per_page' => $posts_per_page,
		)
	);
?>

	<?php while( $the_query->have_posts() ) : $the_query->the_post(); ?>
		<?php get_template_part( 'content', get_post_format() ); ?>
	<?php endwhile; ?>
	<?php die(); ?>

<?php }


