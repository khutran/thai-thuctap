<?php
/* 
 ** Init plugin options to whitelist our options
 */ 
function twiget_init(){
	register_setting( 'twiget_plugin_options', 'twiget_options', 'twiget_validate_options' );
}
add_action( 'admin_init', 'twiget_init' );


/*
 ** Add menu page (wp-admin/options-general.php?page=twiget/options.php)
 */
function twiget_add_options_page(){
	add_options_page( 'Twiget Twitter Plugin Settings', 'Twiget Settings', 'manage_options', __FILE__, 'twiget_render_form');
}
add_action( 'admin_menu', 'twiget_add_options_page');


/*
 ** Sanitize and validate input. Accepts an array, return a sanitized array.
 */
function twiget_validate_options( $input){
	 // strip html from textboxes
	$input['consumer_key'] =  wp_filter_nohtml_kses( $input['consumer_key']);
	$input['consumer_secret'] =  wp_filter_nohtml_kses( $input['consumer_secret']);
	$input['access_token'] =  wp_filter_nohtml_kses( $input['access_token']);
	$input['access_token_secret'] =  wp_filter_nohtml_kses( $input['access_token_secret']);
	$input['cache_period'] = trim( $input['cache_period'] );
	$input['cache_period'] = ( is_numeric( $input['cache_period'] ) ) ? $input['cache_period'] : '';
	return $input;
}


/**
 * Render the Plugin options form. Thanks David Gwyer for Plugin Options Starter Kit plugin!
 */
function twiget_render_form() {
	?>
	<div class="wrap">
		<div class="icon32" id="icon-options-general"><br></div><!-- .icon32 -->
		<h2><?php _e( 'Twiget Twitter Plugin Settings', 'twiget' ); ?></h2>

		<form method="post" action="options.php">
			<?php settings_fields('twiget_plugin_options'); ?>
			<?php $options = get_option('twiget_options'); ?>
            
            <h3><?php _e( 'Twitter API credentials', 'twiget' ); ?></h3>
			<table class="form-table">
            	<tr>
					<th scope="row"><?php _e( 'Consumer Key', 'twiget' ); ?></th>
					<td>
						<input type="text" size="50" name="twiget_options[consumer_key]" class="code" value="<?php if (isset($options['consumer_key'])) { echo $options['consumer_key']; } ?>" />
					</td>
				</tr>
				<tr>
					<th scope="row"><?php _e( 'Consumer Secret', 'twiget' ); ?></th>
					<td>
						<input type="text" size="50" name="twiget_options[consumer_secret]" class="code" value="<?php if (isset($options['consumer_secret'])) { echo $options['consumer_secret']; } ?>" />
					</td>
				</tr>
				<tr>
					<th scope="row"><?php _e( 'Access Token', 'twiget' ); ?></th>
					<td>
						<input type="text" size="50" name="twiget_options[access_token]" class="code" value="<?php if (isset($options['access_token'])) { echo $options['access_token']; } ?>" />
					</td>
				</tr>
				<tr>
					<th scope="row"><?php _e( 'Access Token Secret', 'twiget' ); ?></th>
					<td>
						<input type="text" size="50" name="twiget_options[access_token_secret]" class="code" value="<?php if (isset($options['access_token_secret'])) { echo $options['access_token_secret']; } ?>" />
					</td>
				</tr>
            </table>
            <p><?php _e( '<strong>NOTE:</strong> Twiget caches requests to Twitter to make full use of but never exceeds the Twitter API\'s limit of 180 requests per 15 minutes.<br /> Therefore, it is recommended to use the API credentials you entered above for just this website, especially so if it has a high amount of traffic.', 'twiget' ); ?></p>
            <table class="form-table">
				<tr>
					<th scope="row"><label for="cache_period"><?php _e( 'Override cache period', 'twiget' ); ?></label></th>
					<td>
                        <input name="twiget_options[cache_period]" id="cache_period" type="text" value="<?php if ( isset( $options['cache_period'] ) ) echo $options['cache_period']; ?>" size="3" /> <?php _e( 'seconds', 'twiget' ); ?><br />
                        <span class="description"><?php _e( 'Enter manual cache period to override the plugin\'s automatic cache period calculation. Enter 0 to disable caching.', 'twiget' ); ?></span>
					</td>
				</tr>
			</table>
            
            
            <table style="margin-top: 10px;" class="form-table">
				<tr valign="top" style="border-top:#dddddd 1px solid;">
					<th scope="row"><?php _e( 'Database Options', 'twiget' ); ?></th>
					<td>
						<label><input name="twiget_options[twiget_default_options_db]" type="checkbox" value="1" <?php if (isset($options['twiget_default_options_db'])) { checked('1', $options['twiget_default_options_db']); } ?> /> <?php _e( 'Restore defaults upon plugin deactivation/reactivation', 'twiget' ); ?></label>
						<br /><span style="color:#666666;margin-left:2px;"><?php _e( 'Only check this if you want to reset plugin settings upon Plugin reactivation', 'twiget' ); ?></span>
					</td>
				</tr>
			</table>
			<p class="submit"><input type="submit" class="button-primary" value="<?php _e( 'Save Changes', 'twiget' ) ?>" /></p>
		</form>

		<div id="twiget-instructions" style="padding:10px;border:1px dotted #000000;"><h2><?php _e( 'How to get Twitter API credentials', 'twiget' ); ?></h2>
		<?php printf( __( '<strong>Step 1:</strong> Go to %s page on twitter and login with your twitter username and password.', 'twiget' ), '<a href="https://dev.twitter.com/apps" target="_blank">' . __( 'My applications', 'twiget' ) . '</a>' ); ?>
		<br /><br />
		<?php printf( __( '<strong>Step 2:</strong> Click %s button.', 'twiget' ), '<a href="https://dev.twitter.com/apps/new" target="_blank">' . __( 'Create a new application', 'twiget' ) . '</a>' ); ?>
		<br /><br />
		<?php _e( '<strong>Step 3:</strong> Enter your application details', 'twiget' ); ?>
		<br /><br />
			<table class="instruction-table" cellpadding="3" style="border-collapse:collapse;text-align:left;margin-left:20px;">
				<tr>
					<th><?php _e( 'Name: *', 'twiget' ); ?></th>
					<td><?php _e( 'Enter the name of your application. It may be the title of your website.', 'twiget' ); ?></td>
				</tr>
				
				<tr>
					<th><?php _e( 'Description: *', 'twiget' ); ?></th>
					<td><?php _e( 'Enter some description. Eg: Tweets in my website.', 'twiget' ); ?></td>
				</tr>
				
				<tr>
					<th><?php _e( 'Website: *', 'twiget' ); ?></th>
					<td><?php _e( 'Enter the URL of your website.', 'twiget' ); ?></td>
				</tr>
				
				<tr>
					<th><?php _e( 'Callback URL: ', 'twiget' ); ?></th>
					<td><?php _e( 'Leave this empty.', 'twiget' ); ?></td>
				</tr>
			</table>
		<br />
		<?php _e( 'Select <strong>Yes, I agree</strong> and complete the Captcha. Then click <strong>Create your Twitter application</strong> button.', 'twiget' ); ?>
		<br /><br />	
		<?php _e( '<strong>Step 4:</strong> In the next page, click <strong>Create my access token</strong> button. It may take a moment to display access token. So refresh page.', 'twiget' ); ?>
		<br /><br />
		<?php _e( '<strong>Step 5:</strong> Copy <em>Consumer key, Consumer secret, Access token, Access token secret</em> and enter them in the respective fields above.', 'twiget' ); ?>
		</div><!-- #twiget-instructions -->
		<p style="margin-top:15px;"><?php printf( __( 'Twiget Twitter Widget is brought to you by %s developers.', 'twiget' ), '<a href="http://www.graphene-theme.com/" target="_blank">' . __( 'Graphene Theme', 'twiget' ) . '</a>' ); ?></p>
	</div><!-- .wrap -->

	<?php	
}
