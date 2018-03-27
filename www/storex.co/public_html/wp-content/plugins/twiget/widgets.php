<?php
/**
 * Register custom Twitter widgets.
 *
 * @package Twiget Twitter Widget
 * @since 1.0
 */
class Twiget_Twitter_Widget extends WP_Widget{
	
	public function __construct() {
		parent::__construct(
	 		'twiget-widget', // Base ID
			__('TwiGet Twitter Widget', 'twiget'), // Name
			array('description' => __( 'Display the latest Twitter status updates.', 'twiget' ),
				  'classname' => 'twiget-widget',
			)
		);
		/* Enqueue the twitter script and css if widget is active */
		if ( is_active_widget( false, false, 'twiget-widget', true ) && ! is_admin() ) {
			wp_enqueue_script( 'jquery' );
			wp_enqueue_script( 'twiget-widget-js', TWIGET_PLUGIN_URL . '/js/twiget.js', array(), '', false );
			wp_enqueue_style( 'twiget-widget-css', TWIGET_PLUGIN_URL . '/css/twiget.css' );
		}
	}
	
	function widget( $args, $instance ){
		extract( $args );
		
		$title = apply_filters( 'twiget_widget_title', empty( $instance['title'] ) ? __( 'Latest tweets', 'twiget' ) : $instance['title'], $instance, $this->id_base);	
		$username = $instance['username'];
		$count = $instance['count'];
		$followercount = $instance['followercount'];
		$hide_replies = ( array_key_exists( 'hide_replies', $instance ) ) ? $instance['hide_replies'] : false ;
		$new_window = $instance['new_window'];
		$profile_pic = ( array_key_exists( 'profile_pic', $instance ) ) ? $instance['profile_pic'] : false ;
		$show_bio = ( array_key_exists( 'show_bio', $instance ) ) ? $instance['show_bio'] : false ;
		$twitter_client = ( array_key_exists( 'twitter_client', $instance ) ) ? $instance['twitter_client'] : false ;
		$wrapper_id = 'tweet-wrap-' . $widget_id;
		$bio_id = 'tweet-bio-' . $widget_id;
		
		$follower_count_attr = ( $followercount ) ? 'data-show-count="true"' : 'data-show-count="false"';
		$hide_replies_attr = ( $hide_replies ) ? 'true' : 'false';
		
		echo $before_widget . $before_title . $title . $after_title;
		?>
		<div class="twiget-feed">
			
            <ul id="<?php echo $wrapper_id; ?>" class="tweet-wrap">
            	<li><?php _e( 'Loading tweets...', 'twiget' ); ?></li>
            </ul>
            
            <p id="<?php echo $bio_id; ?>" class="tweet-bio"></p>

            
			<script type="text/javascript">
                jQuery(document).ready(function($) {
                    var tweetOptions = {
                            screen_name: 		'<?php echo $username; ?>',
                            count: 				<?php echo $count; ?>,
                            include_rts: 		true,
							exclude_replies: 	<?php echo $hide_replies_attr; ?>,
							widget_id:			'<?php echo $widget_id; ?>'
                    };
                    $.post( '<?php echo home_url( '?twiget-get-tweets=1' ); ?>', tweetOptions, function(data){
                        TwigetTwitter( data, '<?php echo $wrapper_id; ?>', '<?php echo $bio_id; ?>', {
						   <?php if ( $new_window ) 	echo 'newwindow:true,'; 
								 if ( $profile_pic ) 	echo 'profilepic:true,'; 
								 if ( $twitter_client ) echo 'twitterclient:true,';
								 if ( $show_bio ) echo 'showbio:true,'; ?>
						});
                    }, 'json');
                });
            </script>
            <?php do_action( 'twiget_twitter_widget' ); ?>
		</div><!-- .twiget-feed -->
        <?php echo $after_widget; ?>
        <?php
	}
	
	function update( $new_instance, $old_instance ){	// This function processes and updates the settings
		$instance = $old_instance;
		
		// Strip tags (if needed) and update the widget settings
		$instance['username'] = strip_tags( $new_instance['username']);
		$instance['count'] = strip_tags( $new_instance['count']);
		$instance['title'] = strip_tags( $new_instance['title'] );
		$instance['followercount'] = ( isset( $new_instance['followercount'] ) ) ? true : false ;
		$instance['hide_replies'] = ( isset( $new_instance['hide_replies'] ) ) ? true : false ;
		$instance['new_window'] = ( isset( $new_instance['new_window'] ) ) ? true : false ;
		$instance['profile_pic'] = ( isset( $new_instance['profile_pic'] ) ) ? true : false ;
		$instance['show_bio'] = ( isset( $new_instance['show_bio'] ) ) ? true : false ;
		$instance['twitter_client'] = ( isset( $new_instance['twitter_client'] ) ) ? true : false ;
	
		return $instance;
	}
	
	function form( $instance ){		// This function sets up the settings form
		
		// Set up default widget settings
		$defaults = array( 
						'username' => '',
						'count' 			=> 5,
						'title' 			=> __( 'Latest tweets', 'twiget' ),
						'followercount' 	=> false,
						'hide_replies' 		=> false,
						'new_window' 		=> false,
						'profile_pic' 		=> true,
						'show_bio'			=> false,
						'twitter_client' 	=> true
					);
		$instance = wp_parse_args( (array) $instance, $defaults );
		?>
        <p>
        	<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title:', 'twiget' ); ?></label>
			<input id="<?php echo $this->get_field_id( 'title' ); ?>" type="text" name="<?php echo $this->get_field_name( 'title' ); ?>" value="<?php echo $instance['title']; ?>" class="widefat" />
        </p>
        <p>
        	<label for="<?php echo $this->get_field_id( 'username' ); ?>"><?php _e( 'Twitter Username:', 'twiget' ); ?></label>
			<input id="<?php echo $this->get_field_id( 'username' ); ?>" type="text" name="<?php echo $this->get_field_name( 'username' ); ?>" value="<?php echo $instance['username']; ?>" class="widefat" />
        </p>
        <p>
        	<label for="<?php echo $this->get_field_id( 'count' ); ?>"><?php _e( 'Number of tweets to display:', 'twiget' ); ?></label>
			<input id="<?php echo $this->get_field_id( 'count' ); ?>" type="text" name="<?php echo $this->get_field_name( 'count' ); ?>" value="<?php echo $instance['count']; ?>" size="1" />
        </p>
        <p>
        	<input id="<?php echo $this->get_field_id( 'followercount' ); ?>" type="checkbox" name="<?php echo $this->get_field_name( 'followercount' ); ?>" value="true" <?php checked( $instance['followercount'] ); ?> />
        	<label for="<?php echo $this->get_field_id( 'followercount' ); ?>"><?php _e( 'Show followers count', 'twiget' ); ?></label>
        </p>
        <p>
        	<input id="<?php echo $this->get_field_id( 'profile_pic' ); ?>" type="checkbox" name="<?php echo $this->get_field_name( 'profile_pic' ); ?>" value="true" <?php checked( $instance['profile_pic'] ); ?> />
        	<label for="<?php echo $this->get_field_id( 'profile_pic' ); ?>"><?php _e( 'Show profile picture', 'twiget' ); ?></label>
        </p>
        <p>
        	<input id="<?php echo $this->get_field_id( 'show_bio' ); ?>" type="checkbox" name="<?php echo $this->get_field_name( 'show_bio' ); ?>" value="true" <?php checked( $instance['show_bio'] ); ?> />
        	<label for="<?php echo $this->get_field_id( 'show_bio' ); ?>"><?php _e( 'Show Twitter Bio', 'twiget' ); ?></label>
        </p>
        <p>
        	<input id="<?php echo $this->get_field_id( 'twitter_client' ); ?>" type="checkbox" name="<?php echo $this->get_field_name( 'twitter_client' ); ?>" value="true" <?php checked( $instance['twitter_client'] ); ?> />
        	<label for="<?php echo $this->get_field_id( 'twitter_client' ); ?>"><?php _e( 'Show twitter client used', 'twiget' ); ?></label><br />
			<span class="description"><?php _e( 'Eg: via Twitter for Android', 'twiget' ); ?></span>
        </p>
         <p>
         	<input id="<?php echo $this->get_field_id( 'hide_replies' ); ?>" type="checkbox" name="<?php echo $this->get_field_name( 'hide_replies' ); ?>" value="true" <?php checked( $instance['hide_replies'] ); ?> />
        	<label for="<?php echo $this->get_field_id( 'hide_replies' ); ?>"><?php _e( 'Hide @replies', 'twiget' ); ?></label><br />
			<span class="description"><?php $showtweetcount = $instance['count']; printf( __( 'Note: Selecting this sometimes result in showing less than %d tweets', 'twiget' ), $showtweetcount ); ?></span>
        </p>
        <p>
        	<input id="<?php echo $this->get_field_id( 'new_window' ); ?>" type="checkbox" name="<?php echo $this->get_field_name( 'new_window' ); ?>" value="true" <?php checked( $instance['new_window'] ); ?> />
        	<label for="<?php echo $this->get_field_id( 'new_window' ); ?>"><?php _e( 'Open links in new window', 'twiget' ); ?></label>
        </p>
        <?php
	}
}


/**
 * Register the custom widget by passing the twiget_load_widgets() function to widgets_init
 * action hook.
*/ 
function twiget_load_widgets(){
	register_widget( 'Twiget_Twitter_Widget' );
}
add_action( 'widgets_init', 'twiget_load_widgets' );


/**
 * Count the instances of Twiget widgets being used
 *
 * @package Twiget Twitter Widget
 * @since 1.1
 */
function twiget_count_instances(){
	$sidebars = wp_get_sidebars_widgets();
	if ( array_key_exists( 'wp_inactive_widgets', $sidebars ) )	unset( $sidebars['wp_inactive_widgets'] );
	
	$count = 0;
	foreach ( $sidebars as $sidebar => $widgets ) {
		if ( stristr( $sidebar, 'orphaned' ) ) continue;
		foreach ( $widgets as $widget ) {
			if ( stristr( $widget, 'twiget' ) ) $count++;
		}
	}
	return $count;
}
