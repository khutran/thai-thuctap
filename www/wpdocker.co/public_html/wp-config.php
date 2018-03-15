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

// ** MySQL settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define('DB_NAME', 'wordpress');

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
define('AUTH_KEY',         '}*]U}gi#@.yt$%FTs(CYk=i vt?<p8>?r..^YEVJA#2(rWmw4b&&`1-!zGpCpE/a');
define('SECURE_AUTH_KEY',  '(SJzv/@}6|X7qC^;+N}] UN`K;Q+34dPMI9RgR,NbEe/:R|Eby3bY!530_}4/7,%');
define('LOGGED_IN_KEY',    'NNE!Ah97Dad7.u0-NU?;i/sV;6&s1v8=*Gvfb31-ksVrcu@@z~QKojV&;eclCsj+');
define('NONCE_KEY',        '$=?saB{F@ A~+8ut)(dx0_3^uOYBj@rlCp)Jc8rk~g?>+$:0[Pr+u1/o>E+{XejZ');
define('AUTH_SALT',        'JDw|$c=%34`&K&9f&Q3#+*m;60XZ%z(ObN<h(:InBi*fzKzIS>W?}o.JaXSonOwe');
define('SECURE_AUTH_SALT', 'y(&;g%gQ}}8FD/RL*))g~bF[t,9e$j`> <b|e>Iq%R&MOjr`x9tgAyH+7xCdN{r:');
define('LOGGED_IN_SALT',   'L[K:q?B{wC$^mA3LoXcy5!v{al1Sk8az;46,U:g^-,{[Gq$ c@} ^|b&Rz~XMw{c');
define('NONCE_SALT',       'i}F:WHI}m1M.@ 3?Y%JE=2%A;xb,iP[?}ePU3g80s)s~7ei^/x&_(F<bR]4(*Lr)');

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
define('WP_DEBUG', true);

/* That's all, stop editing! Happy blogging. */

/** Absolute path to the WordPress directory. */
if ( !defined('ABSPATH') )
	define('ABSPATH', dirname(__FILE__) . '/');

/** Sets up WordPress vars and included files. */
require_once(ABSPATH . 'wp-settings.php');
