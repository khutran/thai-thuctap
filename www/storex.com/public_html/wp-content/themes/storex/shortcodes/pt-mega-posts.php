<?php // Mega Posts shortcode

function pt_mega_posts_shortcode($atts, $content = null){

		extract(shortcode_atts(array(
			'recent_type'=>$recent_type,
			'recent_posts_title' =>$recent_posts_title,
			'recent_posts_qty' => $recent_posts_qty,
			'recent_posts_cat' => $recent_posts_cat,
			'editors_title'=> $editors_title,
			'editors_img'=> $editors_img,
			'editors_qty'=> $editors_qty,
			//'editors_button'=> $editors_button,
		), $atts));

		$html_output = '';
	
			// Queries
		// Recent Post
		$recent_posts_args = array( 
			'orderby' => 'date',
			'order' => 'DESC',
			'category_name' => $recent_posts_cat,
			'post_type' => 'post',
			'post_status' => 'publish',
			'ignore_sticky_posts' => 1,
			'posts_per_page' => $recent_posts_qty,
		);
		
		// Editor Choice
		$editor_choice_args = array( 
		'order' => 'DESC',
		'post_status' => 'publish',
		'post_type' => 'post',
		'ignore_sticky_posts' => 1,
		'posts_per_page' => $editors_qty,
		'meta_query' => array(
			array(
				'key'     => 'storex_mega_post_meta',
				'value'   => 'on',
			)
		),
	);
	
	if( $recent_type=='recent_post' || $recent_type=='recent_post_editor' ) {
		$recent_post_output = '';
		$query = new WP_Query ($recent_posts_args);
			if ( $query->have_posts() ) {

			$count_post=0; 
			if($recent_posts_title!==''){$recent_post_output .='<h3 class="title">'. esc_attr($recent_posts_title) .'</h3>';}
			$recent_post_output .= '<ul class="post-list" data-isotope-layout="fitrows" data-isotope="container">';
					
				while( $query->have_posts() ) : $query->the_post();
					$count_post++;

					if($count_post==1){
						$recent_post_output .= "<li class='post isotope-item first'>";
						
						$recent_post_output .= '<div class="wrapper-item">';
						$recent_post_output .='<span class="new">'.esc_html__('Top New', 'storex').'</span>';
						
						if ( has_post_thumbnail() ) {
							$recent_post_output .= "<div class='thumb-wrapper'>";
							$recent_post_output .= '<a class="posts-img-link" rel="bookmark" href="'.get_permalink(get_the_ID()).'" title="'.esc_html__( 'Click to learn more', 'storex').'">';
							$recent_post_output .= get_the_post_thumbnail(get_the_ID(), 'pt-recent-post');
							$recent_post_output .= '</a></div>';
						}
							
						$recent_post_output .= '<div class="content-item">';
						$recent_post_output .= '<div class="meta-data"><span class="date">'.get_the_date('F j, Y').'</span>';
						$recent_post_output .= '<span class="author"><a href="'. esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ) .'" rel="author">'.esc_html__('By ', 'storex').get_the_author().'</a></span></div>';
						$recent_post_output .= '<a href="'. esc_url(get_permalink(get_the_ID())).'" title="'.esc_html__( 'Click to learn more', 'storex').'"><h3>'. esc_html(get_the_title(get_the_ID())) .'</h3></a>';
						$recent_post_output .= '<div class="entry-excerpt">'.esc_html(storex_the_excerpt_max_charlength(160)).'</div>';
						$recent_post_output .= '<div class="buttons-wrapper">';
						$recent_post_output .='<div class="link-to-post"><a class="button" rel="bookmark" href="'.esc_url(get_permalink(get_the_ID())).'" title="'.esc_html__( 'Click to learn more', 'storex').'">'.esc_html__( 'Read More', 'storex').'</a></div>';
						$recent_post_output .= '</div>';
						$recent_post_output .= '</div>';
						$recent_post_output .= '</div>';
						$recent_post_output .= "</li>";
					}
		
					elseif( $count_post!==1 && get_post_format( $post_id )=='aside' ){
						$recent_post_output .= "<li class='post aside isotope-item post-{$count_post}'>";
						$recent_post_output .= '<div class="content-item">';
						$recent_post_output .= '<div class="entry-excerpt"><h3>'. esc_html(storex_the_excerpt_max_charlength(65)) .'</h3></div>' ;
						$recent_post_output .= '<span class="date"><i class="fa fa-clock-o"></i>'.get_the_date('F j, Y g:i a').'</span>';
						$recent_post_output .= '</div>';
						$recent_post_output .= '<div class="author-meta"><div class="avatar">'.get_avatar(get_the_author_meta(ID)).'</div><div class="user-name">'.get_the_author().'</div>';
						$recent_post_output .= '<div class="user-info">'.get_the_author_meta('description', get_the_author_meta(ID) ).'</div>';
						$recent_post_output .= '<a rel="bookmark" class="read-more" href="'. esc_url(get_permalink(get_the_ID())) .'" title="'.esc_html__( 'Click to learn more', 'storex').'"><i class="fa fa-long-arrow-right"></i></a>';
						$recent_post_output .= '</div></li>';
					}
		
					else {
						$recent_post_output .= "<li class='post isotope-item post-{$count_post}'>";
						$recent_post_output .= '<div class="content-item">';

						if ( has_post_thumbnail() ) {
							$recent_post_output .= "<div class='thumb-wrapper'>";
							$recent_post_output .= '<a class="posts-img-link" rel="bookmark" href="'. esc_url(get_permalink(get_the_ID())) .'" title="'.esc_html__( 'Click to learn more', 'storex').'">';
							$recent_post_output .= get_the_post_thumbnail(get_the_ID(), 'pt-recent-post');
							$recent_post_output .= '</a></div>';
						}
						$recent_post_output .= '<a href="'. esc_url(get_permalink(get_the_ID())).'" title="'.esc_html__( 'Click to learn more', 'storex').'"><h3>'.get_the_title(get_the_ID()).'</h3></a>';
						$recent_post_output .= '<div class="entry-excerpt">'.esc_html(storex_the_excerpt_max_charlength(70)).'</div>';
						$recent_post_output .= '<div class="post-like-views">'.storex_entry_post_views(get_the_ID());
						$recent_post_output .= storex_output_likes_counter(get_the_ID());
						$recent_post_output .= '<a rel="bookmark" class="read-more" href="'. esc_url(get_permalink(get_the_ID())).'" title="'.esc_html__( 'Click to learn more', 'storex').'"><i class="fa fa-long-arrow-right"></i></a></div>';
						$recent_post_output .= '</div>';
						$recent_post_output .= "</li>";
					}
		
				endwhile;
				unset($query);
				wp_reset_postdata();

				$recent_post_output .= '</ul>';
			}

			$html_output .= $recent_post_output;
		}

		if( $recent_type=='editors_choice' || $recent_type=='recent_post_editor' ) {

			$editor_choice_output = '';
			$query = new WP_Query ($editor_choice_args);
			if ( $query->have_posts() ) {
				$editor_choice_output.='<div class="editor-choice-wrapper">';
				if($editors_title!=''){
					$editor_choice_output.= '<h3 class="title">'. esc_attr($editors_title). '</h3>';
				}
				if($editors_img!=''){
					$editor_choice_output.='<div class="editors-choice-img"><img src="'. esc_url($editors_img) .'" alt="editors-choice"></div>';
				}
				$editor_choice_output .= '<ul class="post-list-editor-choice">';
				while( $query->have_posts() ) : $query->the_post();
					$editor_choice_output .= '<li>';
					$editor_choice_output .= '<a href="'.esc_url(get_permalink(get_the_ID())).'" title="'.esc_html__( 'Click to learn more', 'storex').'"><h3>'.get_the_title(get_the_ID()).'</h3></a>';
					$editor_choice_output .= '<div class="meta-data"><span class="date"><i class="fa fa-calendar"></i>'.get_the_date('F j, Y').'</span></div>';
					$editor_choice_output .= '</li>';
				endwhile;
				unset($query);
				wp_reset_postdata();
				//if($editors_button=='yes'){
					//$editor_choice_output .='<div class="read-more-button"><a>'.esc_html__('Read More', 'storex').'</a></div>';
				//}
				$editor_choice_output .= '</ul>';
				$editor_choice_output .= '</div>';
			}

			$html_output .= $editor_choice_output;
		}
	
	return $html_output;
}

add_shortcode('pt-mega-posts', 'pt_mega_posts_shortcode');
