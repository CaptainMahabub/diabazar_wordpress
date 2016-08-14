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
define('DB_NAME', 'diabazar_wordpress');

/** MySQL database username */
define('DB_USER', 'root');

/** MySQL database password */
define('DB_PASSWORD', '');

/** MySQL hostname */
define('DB_HOST', 'localhost');

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
define('AUTH_KEY',         'kXUJ1^Yer?]BMeK)D$?a8V9F.$K_^lP8Dh2u%V$u{l,R|K0pB`AgxF*en:! Sw_?');
define('SECURE_AUTH_KEY',  'uZJ:Z6nz|}_I~:t{THp9U++Or!/;AkmRa/%cP5`~R[HqtzDfXq&]!VGgLaGs2 ^w');
define('LOGGED_IN_KEY',    'xop+~D{{jbCP0Lyy^hom)vJ;*E`X*q!J=e?5$2]M<4I*F3?//wMA,qDf{r79,))>');
define('NONCE_KEY',        ':8Y-K#JST3F,gK#OD-oWz_xPE W3TjIuvwolWq7q8cV+t5nGB#azaIln>_L8kW>f');
define('AUTH_SALT',        'L5I^ a8Q]WN1Otc~iu<_j]GFe_]UUQz9di`V(+&7;N;e^c%$D/*@4zL_L5q]#g]g');
define('SECURE_AUTH_SALT', 'm>F2n9tR.}c6@{%._9/wq?%k~[#[;;5$6WBni$c3?!GKj}~Ov5Bh(`26$ D7@&]K');
define('LOGGED_IN_SALT',   'txE(A+]Vy26J+hA}=!N:W&:kU_<-#Z2:e1Vy/Exp>y27>A^K!:zOUNdT,m{}]lZZ');
define('NONCE_SALT',       'MQ|=mdBL@ygSDm!qm=Lmx~2d#uxu-i1RA.,WEa=|](GUoc(0kB^GF5dfkTU`nza-');

/**#@-*/

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix  = 'dia_';

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
