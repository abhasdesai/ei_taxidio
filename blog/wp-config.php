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
define('DB_NAME', 'taxidio_blog2012');

/** MySQL database username */
define('DB_USER', 'taxidio_blog2012');

/** MySQL database password */
define('DB_PASSWORD', '$*X-m^U5T6FE');

/** MySQL hostname */
define('DB_HOST', 'taxidiodb.c4nqxbpduoqq.ap-southeast-1.rds.amazonaws.com');

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
define('AUTH_KEY',         'i}zkek|Qt,?U~o:S#ER4f2Y$*#Sym>mV!8_+vXjqfqfS)Y#U@KG_T`S:/IYH[ZPKZ4');
define('SECURE_AUTH_KEY',  '3/zk>C%R[9[zxzpRN+qQuI|.,*8KM*/sj]2PQGyr+3x5];6KpT97o.aS Sn!E*#Anh');
define('LOGGED_IN_KEY',    'mezk0?%4F2G51ryHk(q3o:]=+U@Q=9r[%[&RQ0z]>_)][wEA^[lD&84M4Nb?Gy(/OU');
define('NONCE_KEY',        'aMzkQ$<>+.<V9nC5hw.FjG:^#yo=u5E>EVt$RR^J>/4;#RJDl49x*f+a ?cn+!{*B+');
define('AUTH_SALT',        'UhzkD!b%nk!KL3)OQY@!)4dr~]~6FKLYX14+}.Shj<M=g*B?_>JS0>M j)y2&(Cs3S');
define('SECURE_AUTH_SALT', '2uzkB`AAt;$=xK!ZYm`O~}$|nPP_P;Ifg]/Y,bWA,V0r|@*D/IyoS eNb3o%(uEO)[');
define('LOGGED_IN_SALT',   'b zkS5|aVks0m>}!kIj>DrNU>zOcs(7sIoT.W|u%V<gP3o]mFW(X&+ @5J0RK{^Z 1');
define('NONCE_SALT',       'y<zk9|23Hk}9.hrE%ZaRXp8:P461me6:v*wm+&e9M(#76)S@Ec.AxRT}0iaa,5Cul]');
define('FS_METHOD','direct');
/**#@-*/

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix  = 'tx_';

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
