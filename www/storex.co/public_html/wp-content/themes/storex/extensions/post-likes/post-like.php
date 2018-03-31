<?php
/*
 * Plumtree Post Like System
 */
 
/**
 *  Enqueue scripts for like system
 */
function storex_like_scripts() {
	wp_enqueue_script( 'storex_like_post', get_template_directory_uri().'/extensions/post-likes/js/post-like.js', array('jquery'), '1.0', true );
	wp_localize_script( 'storex_like_post', 'ajax_var', array(
		'url' => admin_url( 'admin-ajax.php' ),
		'nonce' => wp_create_nonce( 'ajax-nonce' )
		)
	);
}
add_action( 'init', 'storex_like_scripts' );

/**
 *  Save like data
 */
add_action( 'wp_ajax_nopriv_storex-post-like', 'storex_post_like' );
add_action( 'wp_ajax_storex-post-like', 'storex_post_like' );
function storex_post_like() {
	$nonce = $_POST['nonce'];
    if ( ! wp_verify_nonce( $nonce, 'ajax-nonce' ) )
        die ( 'Nope!' );
	
	if ( isset( $_POST['storex_post_like'] ) ) {
	
		$post_id = $_POST['post_id']; // post id
		$post_like_count = get_post_meta( $post_id, "_post_like_count", true ); // post like count
		
		if ( function_exists ( 'wp_cache_post_change' ) ) { // invalidate WP Super Cache if exists
			$GLOBALS["super_cache_enabled"]=1;
			wp_cache_post_change( $post_id );
		}
		
		if ( is_user_logged_in() ) { // user is logged in
			$user_id = get_current_user_id(); // current user
			$meta_POSTS = get_user_option( "_liked_posts", $user_id  ); // post ids from user meta
			$meta_USERS = get_post_meta( $post_id, "_user_liked" ); // user ids from post meta
			$liked_POSTS = NULL; // setup array variable
			$liked_USERS = NULL; // setup array variable
			
			if ( count( $meta_POSTS ) != 0 ) { // meta exists, set up values
				$liked_POSTS = $meta_POSTS;
			}
			
			if ( !is_array( $liked_POSTS ) ) // make array just in case
				$liked_POSTS = array();
				
			if ( count( $meta_USERS ) != 0 ) { // meta exists, set up values
				$liked_USERS = $meta_USERS[0];
			}		

			if ( !is_array( $liked_USERS ) ) // make array just in case
				$liked_USERS = array();
				
			$liked_POSTS['post-'.$post_id] = $post_id; // Add post id to user meta array
			$liked_USERS['user-'.$user_id] = $user_id; // add user id to post meta array
			$user_likes = count( $liked_POSTS ); // count user likes
	
			if ( !AlreadyLiked( $post_id ) ) { // like the post
				update_post_meta( $post_id, "_user_liked", $liked_USERS ); // Add user ID to post meta
				update_post_meta( $post_id, "_post_like_count", ++$post_like_count ); // +1 count post meta
				update_user_option( $user_id, "_liked_posts", $liked_POSTS ); // Add post ID to user meta
				update_user_option( $user_id, "_user_like_count", $user_likes ); // +1 count user meta
				echo $post_like_count; // update count on front end

			} else { // unlike the post
				$pid_key = array_search( $post_id, $liked_POSTS ); // find the key
				$uid_key = array_search( $user_id, $liked_USERS ); // find the key
				unset( $liked_POSTS[$pid_key] ); // remove from array
				unset( $liked_USERS[$uid_key] ); // remove from array
				$user_likes = count( $liked_POSTS ); // recount user likes
				update_post_meta( $post_id, "_user_liked", $liked_USERS ); // Remove user ID from post meta
				update_post_meta($post_id, "_post_like_count", --$post_like_count ); // -1 count post meta
				update_user_option( $user_id, "_liked_posts", $liked_POSTS ); // Remove post ID from user meta			
				update_user_option( $user_id, "_user_like_count", $user_likes ); // -1 count user meta
				echo "already".$post_like_count; // update count on front end
				
			}
			
		} else { // user is not logged in (anonymous)
			$ip = $_SERVER['REMOTE_ADDR']; // user IP address
			$meta_IPS = get_post_meta( $post_id, "_user_IP" ); // stored IP addresses
			$liked_IPS = NULL; // set up array variable
			
			if ( count( $meta_IPS ) != 0 ) { // meta exists, set up values
				$liked_IPS = $meta_IPS[0];
			}
	
			if ( !is_array( $liked_IPS ) ) // make array just in case
				$liked_IPS = array();
				
			if ( !in_array( $ip, $liked_IPS ) ) // if IP not in array
				$liked_IPS['ip-'.$ip] = $ip; // add IP to array
			
			if ( !AlreadyLiked( $post_id ) ) { // like the post
				update_post_meta( $post_id, "_user_IP", $liked_IPS ); // Add user IP to post meta
				update_post_meta( $post_id, "_post_like_count", ++$post_like_count ); // +1 count post meta
				echo $post_like_count; // update count on front end
				
			} else { // unlike the post
				$ip_key = array_search( $ip, $liked_IPS ); // find the key
				unset( $liked_IPS[$ip_key] ); // remove from array
				update_post_meta( $post_id, "_user_IP", $liked_IPS ); // Remove user IP from post meta
				update_post_meta( $post_id, "_post_like_count", --$post_like_count ); // -1 count post meta
				echo "already".$post_like_count; // update count on front end
				
			}
		}
	}
	
	exit;
}

/**
 *  Test if user already liked post
 */
function AlreadyLiked( $post_id ) { // test if user liked before
	if ( is_user_logged_in() ) { // user is logged in
		$user_id = get_current_user_id(); // current user
		$meta_USERS = get_post_meta( $post_id, "_user_liked" ); // user ids from post meta
		$liked_USERS = ""; // set up array variable
		
		if ( count( $meta_USERS ) != 0 ) { // meta exists, set up values
			$liked_USERS = $meta_USERS[0];
		}
		
		if( !is_array( $liked_USERS ) ) // make array just in case
			$liked_USERS = array();
			
		if ( in_array( $user_id, $liked_USERS ) ) { // True if User ID in array
			return true;
		}
		return false;
		
	} else { // user is anonymous, use IP address for voting
	
		$meta_IPS = get_post_meta( $post_id, "_user_IP" ); // get previously voted IP address
		$ip = $_SERVER["REMOTE_ADDR"]; // Retrieve current user IP
		$liked_IPS = ""; // set up array variable
		
		if ( count( $meta_IPS ) != 0 ) { // meta exists, set up values
			$liked_IPS = $meta_IPS[0];
		}
		
		if ( !is_array( $liked_IPS ) ) // make array just in case
			$liked_IPS = array();
		
		if ( in_array( $ip, $liked_IPS ) ) { // True is IP in array
			return true;
		}
		return false;
	}
	
}

/**
 *  Front end button
 */
function storex_output_like_button( $post_id ) {
	$like_count = get_post_meta( $post_id, "_post_like_count", true ); // get post likes
	$count = ( empty( $like_count ) || $like_count == "0" ) ? 'Like' : $like_count;
	if ( AlreadyLiked( $post_id ) ) {
		$class = ' liked';
		$title = 'Unlike';
		$heart = '';
	} else {
		$class = '';
		$title = 'Like';
		$heart = '';
	}?>
	<div class="like-wrapper">
		<a href="#" class="storex-post-like<?php echo esc_attr($class);?>" data-post_id="<?php echo esc_attr($post_id); ?>" title="<?php echo esc_attr($title); ?>">
			<?php if ( AlreadyLiked( $post_id ) ) { ?>
				<i id="icon-like" class="post-icon-like fa fa-heart"></i>
				<?php echo esc_attr($count);?>
			<?php } else { ?>
				<i id="icon-unlike" class="post-icon-unlike fa fa-heart-o"></i>
				<?php echo esc_attr($count);?>
			<?php } ?>
		</a>
	</div>
	<?php
}

/**
 *  Add a shortcode to your posts instead
 *  type [pt-like-button] in your post to output the button
 */
function storex_like_shortcode() {
	return storex_get_like_button( get_the_ID() );
}
add_shortcode('storex-like-button', 'storex_like_shortcode');

/**
 *  If the user is logged in, output a list of posts that the user likes
 *  Markup assumes sidebar/widget usage
 */
function frontEndUserLikes() {
	if ( is_user_logged_in() ) { // user is logged in
		$like_list = '';
		$user_id = get_current_user_id(); // current user
		$user_likes = get_user_option( "_liked_posts", $user_id );
		if ( !empty( $user_likes ) && count( $user_likes ) > 0 ) {
			$the_likes = $user_likes;
		} else {
			$the_likes = '';
		}
		if ( !is_array( $the_likes ) )
			$the_likes = array();
		$count = count( $the_likes );
		if ( $count > 0 ) {
			$limited_likes = array_slice( $the_likes, 0, 5 ); // this will limit the number of posts returned to 5
			?>
			<div class="favourite-posts">
				<h3><?php esc_html_e( 'You Like:', 'plumtree' ); ?></h3>
				<ul>
				<?php foreach ( $limited_likes as $the_like ) { ?>
					<li>
						<a href="<?php echo esc_url( get_permalink( $the_like ) ); ?>" title="<?php echo esc_attr( get_the_title( $the_like ) ); ?>">
							<?php echo get_the_title( $the_like ); ?>
						</a>
					</li>
				<?php } ?>
				</ul>
			</div>
		<?php }
	} else { ?>
		<div class="favourite-posts">
			<h3><?php esc_html_e( 'Nothing yet', 'plumtree' ); ?></h3>
		</div>
	<?php }
}

/* User Likes Widget */
add_action( 'widgets_init', create_function( '', 'register_widget( "storex_user_likes_widget" );' ) );

class storex_user_likes_widget extends WP_Widget {
	
	public function __construct() {
		parent::__construct(
	 		'storex_user_likes_widget', // Base ID
			esc_html__('PT User Likes Widget', 'plumtree'), // Name
			array( 'description' => esc_html__( 'Plum Tree special widget. If the user is logged in, output a list of posts that the user likes', 'plumtree' ), ) 
		);
	}

	public function form( $instance ) {

		$defaults = array( 
			'title' 		=> 'Favourite Posts',
			'precontent'    => '',
			'postcontent'   => '',
		);

		$instance = wp_parse_args( (array) $instance, $defaults ); 
	?>
		<p>
			<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php esc_html_e( 'Title:', 'plumtree' ); ?></label> 
			<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo $instance['title']; ?>" />
		</p>
		<p>
			<label for="<?php echo $this->get_field_id ('precontent'); ?>"><?php esc_html_e('Pre-Content', 'plumtree'); ?></label>
			<textarea class="widefat" id="<?php echo $this->get_field_id('precontent'); ?>" name="<?php echo $this->get_field_name('precontent'); ?>" rows="2" cols="25"><?php echo $instance['precontent']; ?></textarea>
		</p>
		<p>
			<label for="<?php echo $this->get_field_id ('postcontent'); ?>"><?php esc_html_e('Post-Content', 'plumtree'); ?></label>
			<textarea class="widefat" id="<?php echo $this->get_field_id('postcontent'); ?>" name="<?php echo $this->get_field_name('postcontent'); ?>" rows="2" cols="25"><?php echo $instance['postcontent']; ?></textarea>
		</p>
		<?php 
	}

	public function update( $new_instance, $old_instance ) {
		
		$instance = $old_instance;

		$instance['title'] = ( $new_instance['title'] );
		$instance['precontent'] = stripslashes( $new_instance['precontent'] );
		$instance['postcontent'] = stripslashes( $new_instance['postcontent'] );

		return $instance;
	}

	public function widget( $args, $instance ) {

		global $wpdb;

		extract( $args );

		$title = apply_filters( 'widget_title', $instance['title'] );
		$precontent = (isset($instance['precontent']) ? $instance['precontent'] : '' );
		$postcontent = (isset($instance['postcontent']) ? $instance['postcontent'] : '' );

		echo $before_widget;
		if ( ! empty( $title ) )
			echo $before_title . $title . $after_title;
		if ( ! empty( $precontent ) ) {
			echo '<div class="precontent">'.$precontent.'</div>';
		} 
		frontEndUserLikes();
		if ( ! empty( $postcontent ) ) {
			echo '<div class="postcontent">'.$postcontent.'</div>';
		}

		echo $after_widget;
	}
}


/**
 * ---- Popular Posts Widget 
 * Outputs a list of the posts with the most user likes
 * Markup assumes sidebar/widget usage
 */
add_action( 'widgets_init', create_function( '', 'register_widget( "storex_popular_posts_widget" );' ) );

class storex_popular_posts_widget extends WP_Widget {
	
	public function __construct() {
		parent::__construct(
	 		'storex_popular_posts_widget', // Base ID
			esc_html__('Storex Popular Posts Widget', 'plumtree'), // Name
			array( 'description' => esc_html__( 'Plum Tree special widget. Outputs a list of the posts with the most user likes', 'plumtree' ), ) 
		);
	}

	public function form( $instance ) {

		$defaults = array( 
			'title' 		=> 'Popular Posts',
			'range'         => 'all',
			'post-quantity' => 3,
			'precontent'    => '',
			'postcontent'   => '',
		);

		$instance = wp_parse_args( (array) $instance, $defaults ); 
	?>
		<p>
			<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php esc_html_e( 'Title:', 'plumtree' ); ?></label> 
			<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo $instance['title']; ?>" />
		</p>
		<p>
            <label for="<?php echo $this->get_field_id("range"); ?>"><?php esc_html_e('Time Range:', 'plumtree'); ?></label>
        	<select class="widefat" id="<?php echo $this->get_field_id("range"); ?>" name="<?php echo $this->get_field_name("range"); ?>">
          		<option value="date" <?php selected( $instance["range"], "day" ); ?>><?php esc_html_e('Today', 'plumtree'); ?></option>
           		<option value="comment_count" <?php selected( $instance["range"], "week" ); ?>><?php esc_html_e('Week', 'plumtree'); ?></option>
         		<option value="title" <?php selected( $instance["range"], "month" ); ?>><?php esc_html_e('Month', 'plumtree'); ?></option>
				<option value="author" <?php selected( $instance["range"], "all" ); ?>><?php esc_html_e('All Time', 'plumtree'); ?></option>
        	</select>
        </p>
        <p>
			<label for="<?php echo $this->get_field_id('post-quantity'); ?>"><?php esc_html_e( 'How many posts to display: ', 'plumtree' ) ?></label>
			<input size="3" id="<?php echo esc_attr( $this->get_field_id('post-quantity') ); ?>" name="<?php echo esc_attr( $this->get_field_name('post-quantity') ); ?>" type="number" value="<?php echo esc_attr( $instance['post-quantity'] ); ?>" />
		</p>
		<p>
			<label for="<?php echo $this->get_field_id ('precontent'); ?>"><?php esc_html_e('Pre-Content', 'plumtree'); ?></label>
			<textarea class="widefat" id="<?php echo $this->get_field_id('precontent'); ?>" name="<?php echo $this->get_field_name('precontent'); ?>" rows="2" cols="25"><?php echo $instance['precontent']; ?></textarea>
		</p>
		<p>
			<label for="<?php echo $this->get_field_id ('postcontent'); ?>"><?php esc_html_e('Post-Content', 'plumtree'); ?></label>
			<textarea class="widefat" id="<?php echo $this->get_field_id('postcontent'); ?>" name="<?php echo $this->get_field_name('postcontent'); ?>" rows="2" cols="25"><?php echo $instance['postcontent']; ?></textarea>
		</p>
		<?php 
	}

	public function update( $new_instance, $old_instance ) {
		
		$instance = $old_instance;

		$instance['title'] = ( $new_instance['title'] );
		$instance['range'] = strip_tags( $new_instance['range'] );
		$instance['post-quantity'] = intval( $new_instance['post-quantity'] );
		$instance['precontent'] = stripslashes( $new_instance['precontent'] );
		$instance['postcontent'] = stripslashes( $new_instance['postcontent'] );

		return $instance;
	}

	public function widget( $args, $instance ) {

		global $wpdb, $post;

		extract( $args );

		$title = apply_filters( 'widget_title', $instance['title'] );
		$precontent = (isset($instance['precontent']) ? $instance['precontent'] : '' );
		$postcontent = (isset($instance['postcontent']) ? $instance['postcontent'] : '' );
		$range = (isset($instance['range']) ? $instance['range'] : 'all' );
		$post_qty = ( isset($instance['post-quantity']) ? $instance['post-quantity'] : 3 );

		echo $before_widget;
		if ( ! empty( $title ) )
			echo $before_title . $title . $after_title;
		if ( ! empty( $precontent ) ) {
			echo '<div class="precontent">'.$precontent.'</div>';
		} 

		// Time range variables
		$year = date('Y');
		$today = false;
		$week = false;
		$month = false;
		switch ($range) {
			case 'day':
				$today = date('j');
				$inner_title = esc_html__( 'Today\'s Most Popular Posts', 'plumtree' );
			break;
			case 'week':
				$week = date('W');
				$inner_title = esc_html__( 'This Month\'s Most Popular Posts', 'plumtree' );
			break;
			case 'month':
				$month = date('m');
				$inner_title = esc_html__( 'This Month\'s Most Popular Posts', 'plumtree' );
			break;
			case 'all':
				$year = false;
				$inner_title = esc_html__( 'This Month\'s Most Popular Posts', 'plumtree' );
  			break;
			default:
				$year = false;
				$inner_title = esc_html__( 'This Month\'s Most Popular Posts', 'plumtree' );
		}

		// New Query
		$args = array(
			'year' => $year,
			'day' => $today,
			'w' => $week,
			'monthnum' => $month,
			'post_type' => 'post',
			'meta_key' => '_post_like_count',
			'orderby' => 'meta_value_num',
			'order' => 'DESC',
			'posts_per_page' => $post_qty
		);
		
		$pop_posts = new WP_Query( $args );
		if ( $pop_posts->have_posts() ) { ?>
			<div class="favourite-posts">
				<h3><?php echo ecs_attr($inner_title); ?></h3>
				<ul>
					<?php while ( $pop_posts->have_posts() ) {
						$pop_posts->the_post(); ?>
						<li><a href="<?php echo esc_url( get_permalink($post->ID) ); ?>"><?php echo get_the_title(); ?></a></li>
					<?php } ?>
				</ul>
			</div>
		<?php } else { ?>
			<div class="favourite-posts">
				<h3><?php esc_html_e( 'Nothing yet', 'plumtree' ); ?></h3>
			</div>
		<?php }
		wp_reset_postdata();

		if ( ! empty( $postcontent ) ) {
			echo '<div class="postcontent">'.$postcontent.'</div>';
		}

		echo $after_widget;
	}
}

/**
 *  Likes Counter Function
 *  Return total number of likes for post
 */
function storex_output_likes_counter($post_id) {
	$post_like_count = get_post_meta( $post_id, "_post_like_count", true );
	$html = '';
	if ( intval($post_like_count) > 0 ) { 
		$html .= '<div class="likes-counter">';
		$html .= '<i class="fa fa-heart"></i>'.esc_attr($post_like_count).'';
		$html .= '</div>';
	}
	return $html;
}

