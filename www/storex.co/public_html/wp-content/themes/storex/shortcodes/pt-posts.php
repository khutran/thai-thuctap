<?php // Recent Posts shortcode

function pt_recent_posts_shortcode($atts, $content = null){

	extract(shortcode_atts(array(
		'per_row' => 3,
		'posts_qty' => 5,
		'order' => 'DESC',
		'orderby' => 'date',
		'category_name' => '',
		'show_thumb' => true,
		'show_title' => true,
		'show_excerpt' => true,
		'show_buttons' => true,
	), $atts));
			
	$html = '';

	// Excerpt filters
	$new_excerpt_more = create_function('$more', 'return " ";');	
	add_filter('excerpt_more', $new_excerpt_more);

	$new_excerpt_length = create_function('$length', 'return "40";');
	add_filter('excerpt_length', $new_excerpt_length);

	// The Query
	$the_query = new WP_Query(
		array( 
			'orderby' => $orderby,
			'order' => $order,
			'category_name' => $category_name,
			'post_type' => 'post',
			'post_status' => 'publish',
			'ignore_sticky_posts' => 1,
			'posts_per_page' => $posts_qty,
		)
	);

	$html = "<ul class='post-list columns-{$per_row}'>";
	while( $the_query->have_posts() ) : $the_query->the_post();
		$html .= "<li class='post'>";

			if ( $show_thumb && has_post_thumbnail() ) {
				$html .= "<div class='thumb-wrapper'>";
				$html .= '<a class="posts-img-link" rel="bookmark" href="'.esc_url(get_permalink(get_the_ID())).'" title="'.esc_html__( 'Click to learn more', 'storex').'">';
				$html .= get_the_post_thumbnail(get_the_ID(), 'storex-pt-recent-post');
				$html .= '</a></div>';
			}

			$html .= '<div class="item-content">';

				if ( $show_title ) {
					$html .= '<h3>'. esc_html(get_the_title(get_the_ID())) .'</h3>';
					$html .= '<div class="meta-data"><span class="author">'.esc_html__('By ', 'storex').'<a href="'.esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ).'" rel="author">'.get_the_author().'</a></span>';
					$html .= '<span class="date">'.get_the_date().'</span></div>';
				}

				if ( $show_excerpt ) {
					$html .= '<div class="entry-excerpt">'. esc_html(get_the_excerpt()) .'</div>';
				}

				if ( $show_buttons ) {
					$html .= '<div class="buttons-wrapper">';
					$html .= '<div class="comments-qty"><i class="fa fa-comments"></i>('.get_comments_number(get_the_ID()).')</div>';
					if (function_exists('pt_output_likes_counter')) {
						$html .= pt_output_likes_counter(get_the_ID());
					}
					$html .= '<div class="link-to-post"><a rel="bookmark" href="'.esc_url(get_permalink(get_the_ID())).'" title="'.esc_html__( 'Click to learn more', 'storex').'"><i class="fa fa-chevron-right"></i></a></div>';
					$html .= '</div>';
				}

			$html .= '</div>';

		$html .= "</li>";
	endwhile;
	wp_reset_postdata();
	$html .= '</ul>';

	return $html;

}

add_shortcode('pt-posts', 'pt_recent_posts_shortcode');

