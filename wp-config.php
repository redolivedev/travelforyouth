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

/** Set the HTTP host when running things from the CLI **/
if( defined('WP_CLI') && WP_CLI ) $_SERVER['HTTP_HOST'] = 'local.travelforyouth.com'; //e.g. local.redolive.com

/** Find config file or use default DB config **/
$config_file = dirname( __FILE__ ) . '/_config/' . $_SERVER['HTTP_HOST'] . '.php';
if( file_exists( $config_file ) ) require_once $config_file;

if( ! defined( 'DB_NAME' ) ) define('DB_NAME', 'travelforyouth');
if( ! defined( 'DB_USER' ) ) 		define('DB_USER', 'ro-admin');
if( ! defined( 'DB_PASSWORD' ) )	define('DB_PASSWORD', '2 million dolla$$');
if( ! defined( 'DB_HOST' ) )		define('DB_HOST', 'localhost');
if( ! defined( 'DB_CHARSET' ) )		define('DB_CHARSET', 'utf8');
if( ! defined( 'DB_COLLATE' ) )		define('DB_COLLATE', '');

/**#@+
 * Authentication Unique Keys and Salts.
 *
 * Change these to different unique phrases!
 * You can generate these using the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}
 * You can change these at any point in time to invalidate all existing cookies. This will force all users to have to log in again.
 *
 * @since 2.6.0
 */
define('AUTH_KEY',         '@M@;-iy!{HMaR@L3W#S<{V<}ZH/(T=r&/ pqY1o=<3ms0WZs-QCYQLwRA5=pMP)9');
define('SECURE_AUTH_KEY',  'l>f^z@@{R,,#qfk7YO gG.lpbzepB|aR3}tX)MRGQVF)~6&(U&;e<:5+H$zL!0;%');
define('LOGGED_IN_KEY',    'Ol;&QveL5~umu #=&}/7v=P3?fSw3aq+!A,m2fpw?[n~B#>|W+JXx.La^U!~vH(k');
define('NONCE_KEY',        'v[9sua]8ywf)PliB&KZ7_q(]p,YC/$5DSx=Vp$)Q*|-c[GhDAPzRtrX/2MIw$k_&');
define('AUTH_SALT',        'k*5.Xu/E8<8VW!jB[$KHs5qykfQd+NgS4Jzm!j?TWvJ9~YJP&qsy3Hs3K9=r+ :5');
define('SECURE_AUTH_SALT', 'tWJW^eWe3P^|QQ!>XRoRQ;9C7RQ6+pQK38|k|N/EDxf=t=Bh7`*dwl$A~tKr|uW}');
define('LOGGED_IN_SALT',   ';D**]uvF=~-Ypqr>8UMV!;;q6^`K3C;50qy,vdJB*Pjj;>`ytA+J)LtN}Qg/?6iE');
define('NONCE_SALT',       'Os%^f_DxYK3B5>XVMR+5niWftXIU0QX%n^?,/8JfXq5[b !pAZT]K,}bv9x+^viA');

/**#@-*/

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix  = 'ro_';

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

if( ! defined( 'WP_DEBUG' ) ) define( 'WP_DEBUG', true );
if( ! defined( 'WP_DEBUG_LOG' ) ) define( 'WP_DEBUG_LOG', true );
if( ! defined( 'WP_DEBUG_DISPLAY' ) ) define( 'WP_DEBUG_DISPLAY', true );

$protocol = isset( $_SERVER['HTTPS'] ) && $_SERVER['HTTPS'] ? 'https' : 'http';
define('WP_HOME', $protocol . '://' . $_SERVER['HTTP_HOST'] . '/' );
define('WP_SITEURL', $protocol . '://' . $_SERVER['HTTP_HOST'] . '/' );

/* That's all, stop editing! Happy blogging. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) )
	define( 'ABSPATH', dirname( __FILE__ ) . '/' );

/** Sets up WordPress vars and included files. */
require_once( ABSPATH . 'wp-settings.php' );
