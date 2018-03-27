<?php
/**
 * @version    $Id$
 * @package    IG_Library
 * @author     InnoGears Team <support@innogears.com>
 * @copyright  Copyright (C) 2012 InnoGears.com. All Rights Reserved.
 * @license    GNU/GPL v2 or later http://www.gnu.org/licenses/gpl-2.0.html
 *
 * Websites: http://www.innogears.com
 * Technical Support: Feedback - http://www.innogears.com/contact-us/get-support.html
 */

/**
 * IG PageBuilder Settings
 *
 * @package  IG_Library
 * @since    1.0.0
 */
class IG_Pb_Product_Plugin {
	/**
	 * Define pages.
	 *
	 * @var  array
	 */
	public static $pages = array( 'ig-pb-settings', 'ig-pb-addons' );

	/**
	 * Current IG PageBuilder settings.
	 *
	 * @var  array
	 */
	protected static $settings;

	/**
	 * Initialize IG PageBuilder plugin.
	 *
	 * @return  void
	 */
	public static function init() {
		global $pagenow;

		// Get product information
		$plugin = IG_Product_Info::get( IG_PB_FILE );

		// Generate menu title
		$menu_title = __( 'IG PageBuilder', IGPBL );

		if ( $plugin['Available_Update'] && ( 'admin.php' != $pagenow || ! isset( $_REQUEST['page'] ) || ! in_array( $_REQUEST['page'], self::$pages ) ) ) {
			$menu_title .= " <span class='ig-available-updates update-plugins count-{$plugin['Available_Update']}'><span class='pending-count'>{$plugin['Available_Update']}</span></span>";
		}

		// Define admin menus
		$admin_menus = array(
			'page_title' => __( 'IG PageBuilder', IGPBL ),
			'menu_title' => $menu_title,
			'capability' => 'manage_options',
			'menu_slug'  => 'ig-pb-settings',
			'icon_url'   => IG_Pb_Helper_Functions::path( 'assets/innogears' ) . '/images/ig-pgbldr-icon-white.png',
			'function'   => array( __CLASS__, 'settings' ),
			'children'   => array(
				array(
					'page_title' => __( 'IG PageBuilder - Settings', IGPBL ),
					'menu_title' => __( 'Settings', IGPBL ),
					'capability' => 'manage_options',
					'menu_slug'  => 'ig-pb-settings',
					'function'   => array( __CLASS__, 'settings' ),
				),
			),
		);

		if ( $plugin['Addons'] ) {
			// Generate menu title
			$menu_title = __( 'Add-ons', IGPBL );

			if ( $plugin['Available_Update'] && ( 'admin.php' == $pagenow && isset( $_REQUEST['page'] ) && in_array( $_REQUEST['page'], self::$pages ) ) ) {
				$menu_title .= " <span class='ig-available-updates update-plugins count-{$plugin['Available_Update']}'><span class='pending-count'>{$plugin['Available_Update']}</span></span>";
			}

			// Update admin menus
			$admin_menus['children'][] = array(
				'page_title' => __( 'IG PageBuilder - Add-ons', IGPBL ),
				'menu_title' => $menu_title,
				'capability' => 'manage_options',
				'menu_slug'  => 'ig-pb-addons',
				'function'   => array( __CLASS__, 'addons' ),
			);
		}

		// Initialize necessary IG Library classes
		IG_Init_Admin_Menu::hook();
		IG_Product_Addons ::hook();

		// Register admin menus
		IG_Init_Admin_Menu::add( $admin_menus );

		// Load required assets
		if ( 'admin.php' == $pagenow && isset( $_REQUEST['page'] ) && in_array( $_REQUEST['page'], array( 'ig-pb-settings', 'ig-pb-addons' ) ) ) {
			// Load common assets
			IG_Init_Assets::load( array( 'ig-bootstrap-css', 'ig-jsn-css' ) );

			switch ( $_REQUEST['page'] ) {
				case 'ig-pb-addons':
					// Load addons style and script
					IG_Init_Assets::load( array( 'ig-addons-css', 'ig-addons-js' ) );
				break;
			}
		}

		// Register Ajax actions
		if ( 'admin-ajax.php' == $pagenow ) {
			add_action( 'wp_ajax_ig-pb-convert-data',  array( __CLASS__, 'convert_data' ) );
		}
	}

	/**
	 * Convert other page builder data to IG PageBuilder data.
	 *
	 * @return  void
	 */
	public static function convert_data() {
		// Get current post
		$post = isset( $_REQUEST['post'] ) ? get_post( $_REQUEST['post'] ) : null;

		if ( ! $post ) {
			die( json_encode( array( 'success' => false, 'message' => __( 'Missing post ID.', IGPBL ) ) ) );
		}

		// Get converter
		$converter = isset( $_REQUEST['converter'] ) ? IG_Pb_Converter::get_converter( $_REQUEST['converter'], $post ) : null;

		if ( ! $converter ) {
			die( json_encode( array( 'success' => false, 'message' => __( 'Missing data converter.', IGPBL ) ) ) );
		}

		// Handle conversion of other page builder data to IG PageBuilder
		$result   = $converter->convert();
		$response = array( 'success' => true, 'message' => $result );

		if ( ! is_integer( $result ) || ! $result ) {
			$response = array( 'success' => false, 'message' => $result );
		}

		die( json_encode( $response ) );
	}

	/**
	 * Load required assets.
	 *
	 * @return  void
	 */
	public static function load_assets() {
		IG_Pb_Helper_Functions::enqueue_styles();
		IG_Pb_Helper_Functions::enqueue_scripts_end();
	}

	/**
	 * Render addons installation and management screen.
	 *
	 * @return  void
	 */
	public static function addons() {
		// Instantiate product addons class
		IG_Product_Addons::init( IG_PB_FILE );
	}

	/**
	 * Render settings page.
	 *
	 * @return  void
	 */
	public static function settings() {
		// Load update script
		IG_Init_Assets::load( array( 'ig-pb-settings-js' ) );

		include IG_PB_TPL_PATH . '/settings.php';
	}

	/**
	 * Register settings with WordPress.
	 *
	 * @return  void
	 */
	public static function settings_form() {
		// Add the section to reading settings so we can add our fields to it
		$page    = 'ig-pb-settings';
		$section = 'ig-pb-settings-form';

		add_settings_section(
			$section,
			'',
			array( __CLASS__, 'ig_pb_section_callback' ),
			$page
		);

		// Add the field with the names and function to use for our settings, put it in our new section
		$fields = array(
			array(
				'id'    => 'enable_for',
				'title' => __( 'Enable PageBuilder for...', IGPBL ),
			),
			array(
				'id'    => 'cache',
				'title' => __( 'Enable Caching', IGPBL ),
			),
			array(
				'id'     => 'bootstrap',
				'title'  => __( 'Load Bootstrap Assets', IGPBL ),
				///// for multiple fields in a setting box
				'params' => array( 'ig_pb_settings_boostrap_js', 'ig_pb_settings_boostrap_css' ),
			),
			array(
				'id'    => 'ig_customer_account',
				'title' => 'InnoGears Customer Account',
			),
		);

		foreach ( $fields as $field ) {
			// Preset field id
			$field_id = $field['id'];

			// Do not add prefix for InnoGears Customer Account settings
			if ( 'ig_customer_account' != $field['id'] ) {
				$field_id = str_replace( '-', '_', $page ) . '_' . $field['id'];
			}

			// Register settings field
			add_settings_field(
				$field_id,
				$field['title'],
				array( __CLASS__, 'ig_pb_setting_callback_' . $field['id'] ),
				$page,
				$section,
				isset ( $field['args'] ) ? $field['args'] : array()
			);

			// Register our setting so that $_POST handling is done for us and callback function just has to echo the <input>
			register_setting( $page, $field_id );

			foreach ( (array) $field['params'] as $field_id ) {
				register_setting( $page, $field_id );
			}
		}

	}

	/**
	 * Get current settings.
	 *
	 * @return  array
	 */
	public static function ig_pb_settings_options() {
		if ( ! isset( self::$settings ) ) {
			// Define options
			$options  = array( 'ig_pb_settings_enable_for', 'ig_pb_settings_cache', 'ig_pb_settings_boostrap_js', 'ig_pb_settings_boostrap_css' );

			// Get saved options value
			self::$settings = array();

			foreach ( $options as $key ) {
				self::$settings[$key] = get_option( $key, 'enable' );
			}
		}

		return self::$settings;
	}

	/**
	 * Check/select options.
	 *
	 * @param   string  $value    Current value.
	 * @param   string  $compare  Desired value for checking/selecting option.
	 * @param   string  $check    HTML attribute of checked/selected state.
	 *
	 * @return  void
	 */
	public static function ig_pb_setting_show_check( $value, $compare, $check ) {
		echo esc_attr( ( $value == $compare ) ? "$check='$check'" : '' );
	}

	/**
	 * Setting section callback handler.
	 *
	 * @return  void
	 */
	public static function ig_pb_section_callback() {}

	/**
	 * Render HTML code for `Enable On` field.
	 *
	 * @return  void
	 */
	public static function ig_pb_setting_callback_enable_for() {
		// Get all post types
		$post_types = get_post_types( array( 'public' => true ), 'objects' );

		// Prepare post types as field options
		$options = array();

		global $_wp_post_type_features;

		foreach ( $post_types as $slug => $defines ) {
			// Filter supported post type
			if ( 'attachment' != $slug && post_type_supports( $slug, 'editor' ) ) {
				$options[ $slug ] = $defines->labels->name;
			}
		}

		// Get current settings
		$settings = self::ig_pb_settings_options();
		extract( $settings );

		// Render field options
		$first = true;

		foreach ( $options as $slug => $label ) :

		// Prepare checking state
		$checked = '';

		if ( 'enable' == $ig_pb_settings_enable_for ) :
			$checked = 'checked="checked"';
		elseif ( is_array( $ig_pb_settings_enable_for ) && ( ! isset( $ig_pb_settings_enable_for[ $slug ] ) || 'enable' == $ig_pb_settings_enable_for[ $slug ] ) ) :
			$checked = 'checked="checked"';
		endif;

		// Set value based on checking state
		$value = empty( $checked ) ? 'disable' : 'enable';

		if ( ! $first ) :
			echo '<br />';
		endif;
		?>
		<label for="ig_pb_settings_enable_for_<?php esc_attr_e( $slug ); ?>">
			<input type="hidden" name="ig_pb_settings_enable_for[<?php esc_attr_e( $slug ); ?>]" value="<?php esc_attr_e( $value ); ?>" />
			<input id="ig_pb_settings_enable_for_<?php esc_attr_e( $slug ); ?>" <?php _e( $checked ); ?> onclick="jQuery(this).prev().val(this.checked ? 'enable' : 'disable');" type="checkbox" autocomplete="off" />
			<?php _e( $label ); ?>
		</label>
		<?php
		$first = false;

		endforeach;
		?>
		<p class="description">
			<?php _e( 'Uncheck post types where you do not want IG PageBuilder to be activated.', IGPBL ); ?>
		</p>
		<?php
	}

	/**
	 * Render HTML code for `Enable Caching` field.
	 *
	 * @return  void
	 */
	public static function ig_pb_setting_callback_cache() {
		$settings = self::ig_pb_settings_options();
		extract( $settings );
		?>
		<div>
			<select name="ig_pb_settings_cache">
				<option value="enable" <?php selected( $ig_pb_settings_cache, 'enable' ); ?>><?php _e( 'Yes', IGPBL ); ?></option>
				<option value="disable" <?php selected( $ig_pb_settings_cache, 'disable' ); ?>><?php _e( 'No', IGPBL ); ?></option>
			</select>
			<button class="button button-default" data-textchange="<?php _e( 'Done!', IGPBL ) ?>" id="ig-pb-clear-cache"><?php _e( 'Clear cache', IGPBL ); ?><i class="jsn-icon16 layout-loading jsn-icon-loading"></i></button>
			<span class="hidden layout-message alert"></span>
		</div>
		<p class="description">
			<?php _e( "Select 'Yes' if you want to cache CSS and JS files of IG PageBuilder", IGPBL ); ?>
		</p>
	<?php
	}

	/**
	 * Render HTML code for `Load Bootstrap Assets` field.
	 *
	 * @return  void
	 */
	public static function ig_pb_setting_callback_bootstrap() {
		$settings = self::ig_pb_settings_options();
		extract( $settings );
		?>
		<label>
			<input type="checkbox" name="ig_pb_settings_boostrap_js" value="enable" <?php checked( $ig_pb_settings_boostrap_js, 'enable' ); ?>> <?php _e( 'JS', IGPBL ); ?>
		</label>
		<br>
		<label>
			<input type="checkbox" name="ig_pb_settings_boostrap_css" value="enable" <?php checked( $ig_pb_settings_boostrap_css, 'enable' ); ?>> <?php _e( 'CSS', IGPBL ); ?>
		</label>
		<p class="description">
			<?php _e( 'You should choose NOT to load Bootstrap CSS / JS if your theme or some other plugin installed on your website already loaded it.', IGPBL ); ?>
		</p>
	<?php
	}

	/**
	 * Render HTML code for `InnoGears Customer Account` field.
	 *
	 * @return  void
	 */
	public static function ig_pb_setting_callback_ig_customer_account() {
		// Get saved InnoGears Customer Account
		$username         = '';
		$password         = '';
		$customer_account = get_option( 'ig_customer_account', null );

		if ( ! empty( $customer_account ) ) {
			$username = $customer_account['username'];
			$password = $customer_account['password'];
		}
		?>
		<div>
			<label for="username">
				<?php _e( 'Username', IG_LIBRARY_TEXTDOMAIN ); ?>:
				<input type="text" value="<?php esc_attr_e( $username ); ?>" class="input-xlarge" id="username" name="ig_customer_account[username]" autocomplete="off" />
			</label>
			<label for="password">
				<?php _e( 'Password', IG_LIBRARY_TEXTDOMAIN ); ?>:
				<input type="password" value="<?php esc_attr_e( $password ); ?>" class="input-xlarge" id="password" name="ig_customer_account[password]" autocomplete="off" />
			</label>
			<p class="description">
				<?php _e( "Insert the customer account you registered on <a href='http://www.innogears.com' target='_blank'>www.innogears.com</a>. This account is only required when you want to update commercial plugins purchased from innogears.com.", IGPBL ); ?>
			</p>
		</div>
		<?php
	}
}
