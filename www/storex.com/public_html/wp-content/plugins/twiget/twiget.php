<?php
/*
Plugin Name: Twiget Twitter Widget CUST
Plugin URI: http://link.com
Description: A widget to display the latest Twitter status updates.
Author: Prasanna SP -C
Version: 1.1.4
Author URI: http://www.prasannasp.net/
*/

/*  This file is part of TwiGet Twitter Widget plugin, developed by Syahir Hakim (email : syahir at khairul dash syahir dot com) and Prasanna SP (email: prasanna[AT]prasannasp.net)

    TwiGet Twitter Widget is free software: you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation, either version 3 of the License, or
    (at your option) any later version.

    TwiGet Twitter Widget is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with TwiGet Twitter Widget plugin. If not, see <http://www.gnu.org/licenses/>.
*/

define( 'TWIGET_PLUGIN_PATH', untrailingslashit( plugin_dir_path( __FILE__ ) ) );
define( 'TWIGET_PLUGIN_URL', untrailingslashit( plugin_dir_url( __FILE__ ) ) );

/**
 * Load plugin textdomain
 *
 * @package Twiget Twitter Widget
 * @since 1.0
 */
function load_twiget_plugin_textdomain() {
  load_plugin_textdomain( 'twiget', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' ); 
}
add_action( 'plugins_loaded', 'load_twiget_plugin_textdomain' );


require_once TWIGET_PLUGIN_PATH . '/options.php';
require_once TWIGET_PLUGIN_PATH . '/widgets.php';
require_once TWIGET_PLUGIN_PATH . '/get-tweets.php';


/**
 * Delete options table entries ONLY when plugin deactivated AND deleted (options.php)
 */
function twiget_delete_plugin_options(){
	delete_option( 'twiget_options' );
}
register_uninstall_hook(__FILE__, 'twiget_delete_plugin_options' );


/* 
 ** Define default option settings
 */ 
function twiget_add_defaults(){
	$tmp = get_option( 'twiget_options' );
    if ( ( $tmp['twiget_default_options_db'] == '1' ) || ( ! is_array( $tmp ) ) ) {
		delete_option( 'twiget_options' );
		$arr = array( 'twiget_default_options_db' => '' );
		update_option( 'twiget_options', $arr );
	}
}
register_activation_hook(__FILE__, 'twiget_add_defaults' );


/* 
 ** More plugins link on manage plugin page
 */
function twiget_pluginspage_links( $links, $file ){
$plugin = plugin_basename (__FILE__);

// create links
if ( $file == $plugin ) {
return array_merge(
$links,
array( '<a href="http://www.prasannasp.net/donate/" target="_blank" title="'.esc_attr__( 'Donate for this plugin via PayPal', 'twiget' ).'">'.__( 'Donate', 'twiget' ).'</a>',
'<a href="http://www.prasannasp.net/wordpress-plugins/" target="_blank" title="'.esc_attr__( 'View more plugins from the developer', 'twiget' ).'">'.__( 'More Plugins', 'twiget' ).'</a>',
'<a href="http://twitter.com/prasannasp" target="_blank" title="'.esc_attr__( 'Follow me on twitter!', 'twiget' ).'">'.__( 'twitter!', 'twiget' ).'</a>'
 )
);
			}
return $links;

	}
add_filter( 'plugin_row_meta', 'twiget_pluginspage_links', 10, 2 );

/*
 ** Display a Support forum link on the main Plugins page
 */
function twiget_plugin_action_links( $links, $file ){

	if ( $file == plugin_basename( __FILE__ ) ) {
		$twiget_link1 = '<a href="http://forum.prasannasp.net/forum/plugin-support/twiget/" title="'.esc_attr__( 'TwiGet Twitter Widget support', 'twiget' ).'" target="_blank">'.__( 'Support', 'twiget' ).'</a>';
		$twiget_link2 = '<a href="'.get_admin_url().'options-general.php?page=twiget/options.php">'.__( 'Settings', 'twiget' ).'</a>';

		array_unshift( $links, $twiget_link1, $twiget_link2 );
	}

	return $links;
}
add_filter( 'plugin_action_links', 'twiget_plugin_action_links', 10, 2 );

/**
 * Display admin notice if Twitter oAuth info is missing.
 *
 * @package Twiget Twitter Widget
 * @since 1.1
 */
function twiget_admin_notice_missing_api(){
	$opts = get_option( 'twiget_options' );
	$req_opts = array( 'consumer_key', 'consumer_secret', 'access_token', 'access_token_secret' );
	$api_exists = true;
	foreach ( $req_opts as $req_opt ) {
		if ( ! array_key_exists( $req_opt, $opts ) ) {
			$api_exists = false;
			break;
		} elseif ( ! $opts[$req_opt] ) {
			$api_exists = false;
			break;
		}
	}
	if ( $api_exists ) return;
	?>
    <div class="error">
       <p><?php printf( __( 'Twiget Twitter Widget plugin requires your Twitter API credentials to work. See <a href="%s">Twiget\'s Options Page</a> for instructions on how to set this up.', 'twiget' ), admin_url( 'options-general.php?page=twiget/options.php' ) ); ?></p>
    </div>
    <?php
}
add_action( 'admin_notices', 'twiget_admin_notice_missing_api' );


/*
** Localize timestamp strings
 *
 * @package Twiget Twitter Widget
 * @since 1.1
*/

function twiget_localize_scripts(){
	$twiget_args = array(
		'via'			=> sprintf( __( 'via %s', 'twiget' ), 'twigetTweetClient' ),
		'LessThanMin'  	=> __( 'less than a minute ago', 'twiget' ),
		'AboutAMin'  	=> __( 'about a minute ago', 'twiget' ),
		'MinutesAgo'  	=> sprintf( __( '%s minutes ago', 'twiget' ), 'twigetRelTime' ),
		'AnHourAgo'  	=> __( 'about an hour ago', 'twiget' ),
		'HoursAgo'  	=> sprintf( __( 'about %s hours ago', 'twiget' ), 'twigetRelTime' ),
		'OneDayAgo'  	=> __( '1 day ago', 'twiget' ),
		'DaysAgo'  		=> sprintf( __( '%s days ago', 'twiget' ), 'twigetRelTime' ),
		'isSSL'			=> is_ssl(),
	);
   wp_localize_script( 'twiget-widget-js', 'TwigetArgs', $twiget_args );
}
add_action( 'wp_enqueue_scripts', 'twiget_localize_scripts' );
