<?php
/**
 * The base configuration for WordPress
 *
 * The wp-config.php creation script uses this file during the
 * installation. You don't have to use the web site, you can
 * copy this file to "wp-config.php" and fill in the values.
 *
 * This file contains the following configurations:
 *
 * * MySQL settings
 * * Secret keys
 * * Database table prefix
 * * ABSPATH
 *
 * @link https://codex.wordpress.org/Editing_wp-config.php
 *
 * @package WordPress
 */
define('WP_HOME','http://storex.co');
define('WP_SITEURL','http://storex.co');


// ** MySQL settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define('DB_NAME', '104_forest_storex_db');

/** MySQL database username */
define('DB_USER', 'thaild');

/** MySQL database password */
define('DB_PASSWORD', 'thaild');

/** MySQL hostname */
define('DB_HOST', '172.22.0.3');

/** Database Charset to use in creating database tables. */
define('DB_CHARSET', 'utf8mb4');

/** The Database Collate type. Don't change this if in doubt. */
define('DB_COLLATE', '');

/**#@+
 * Authentication Unique Keys and Salts.
 *
 * Change these to different unique phrases!
 * You can generate these using the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}
 * You can change these at any point in time to invalidate all existing cookies. This will force all users to have to log in again.
 *
 * @since 2.6.0
 */
define('AUTH_KEY',         '{(rj<#`iBng=:l$XO_%LWu#c1I>7;g:Rau1=jV#v`E>ESfzNQKU13ak>{fbm;Rhe');
define('SECURE_AUTH_KEY',  'ov)HQ-DRnKy>m~Yu;v$-&l#vhIXo#S>T;))yTcqS?Zgy5tDDdHW1Vr@?^!uE)8n4');
define('LOGGED_IN_KEY',    'IZ,3{~y0R6:P]!u@5h-zs12^,BBKTVDbSu+V{boArSy_xgjJ:,t=$vk1I>-QZ#1Q');
define('NONCE_KEY',        '6lFTdu>cZfsf-=]q`}]TK3VeFFAm;+IBR;?Z?%x%rOh!FJ^+i&VLeS`Cax^U7Jjo');
define('AUTH_SALT',        'a5{>r/0n;f8wzNZDUc544pi>35`lW?He/Og?wz%N_}8:e9.gA`Ue*.={]^8WO^Q]');
define('SECURE_AUTH_SALT', 'TWPNNEoKW7Y&?GMn;U%I7P>V6u;)J.~vjs}9%^,]2w7%qw/{8MM&2|9+zTaPJ/a ');
define('LOGGED_IN_SALT',   'vV^w)4c9=,l~2b6RFrntcnUK{EMoVn[+-!rOchw%XmpR3PFW7c!A[Z8RC3$;M%fL');
define('NONCE_SALT',       'IsP13R|R)Ha*(}|!ssL[J<-B-5Dft%=?@6 nuINgG|vzHezx+T*uP:XW[>7#oGMj');

/**#@-*/

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix  = 'wp_';

/**
 * For developers: WordPress debugging mode.
 *
 * Change this to true to enable the display of notices during development.
 * It is strongly recommended that plugin and theme developers use WP_DEBUG
 * in their development environments.
 *
 * For information on other constants that can be used for debugging,
 * visit the Codex.
 *
 * @link https://codex.wordpress.org/Debugging_in_WordPress
 */
define('WP_DEBUG', false);

/* That's all, stop editing! Happy blogging. */

/** Absolute path to the WordPress directory. */
if ( !defined('ABSPATH') )
	define('ABSPATH', dirname(__FILE__) . '/');

/** Sets up WordPress vars and included files. */
require_once(ABSPATH . 'wp-settings.php');
