<?php
/**
 * Authenticate to Twitter using oAuth, and retrieve tweets
 */
function twiget_get_tweets(){
	require_once( TWIGET_PLUGIN_PATH . '/lib/twitteroauth.php' );
	$twiget_options = get_option( 'twiget_options' );
	
	$defaults = array(
				'user_id'				=> NULL,
				'screen_name'			=> NULL,
				'since_id'				=> NULL,
				'count'					=> 5,
				'max_id'				=> NULL,
				'trim_user'				=> NULL,
				'exclude_replies'		=> NULL,
				'include_rts'			=> NULL,
				'contributor_details'	=> NULL,
				'widget_id'				=> NULL
			);
	$options = array_intersect_key( array_merge( $defaults, $_POST ), $defaults );
	
	$cache_period = ( $twiget_options['cache_period'] ) ? $twiget_options['cache_period'] : ceil( ( ( 15 * 60 ) / 180 ) * twiget_count_instances() );
	$cache_disabled = ( $cache_period == 0 ) ? true : false;
	
	if ( ! $cache_disabled ) $tweets = get_transient( 'tweets-' . $options['widget_id'] ); else $tweets = false;
	
	if ( $tweets === false ) {
		$consumerkey = $twiget_options['consumer_key'];
		$consumersecret = $twiget_options['consumer_secret']; 
		$accesstoken = $twiget_options['access_token']; 
		$accesstokensecret = $twiget_options['access_token_secret'];
		
		session_start();
		$connection = new TwitterOAuth( $consumerkey, $consumersecret, $accesstoken, $accesstokensecret ); 
		$tweets = $connection->get( 'https://api.twitter.com/1.1/statuses/user_timeline.json?' . http_build_query( $options ) );
		
		$tweets = json_encode( $tweets );
		if ( ! $cache_disabled ) set_transient( 'tweets-' . $options['widget_id'], $tweets, $cache_period );
	}
	echo $tweets;
	exit();
}
if ( isset( $_GET['twiget-get-tweets'] ) && $_GET['twiget-get-tweets'] == 1 ) twiget_get_tweets();