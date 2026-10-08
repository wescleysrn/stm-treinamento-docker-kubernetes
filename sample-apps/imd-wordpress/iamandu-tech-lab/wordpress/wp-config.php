<?php
/**
 * The base configuration for WordPress
 *
 * The wp-config.php creation script uses this file during the installation.
 * You don't have to use the website, you can copy this file to "wp-config.php"
 * and fill in the values.
 *
 * This file contains the following configurations:
 *
 * * Database settings
 * * Secret keys
 * * Database table prefix
 * * ABSPATH
 *
 * @link https://developer.wordpress.org/advanced-administration/wordpress/wp-config/
 *
 * @package WordPress
 */

// ** Database settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define( 'DB_NAME', 'db_iamandu_tech_store' );

/** Database username */
define( 'DB_USER', 'user_iamandu_tech_store' );

/** Database password */
define( 'DB_PASSWORD', 'user_iamandu_tech_store' );

/** Database hostname */
define( 'DB_HOST', 'host.docker.internal' );

/** Database charset to use in creating database tables. */
define( 'DB_CHARSET', 'utf8mb4' );

/** The database collate type. Don't change this if in doubt. */
define( 'DB_COLLATE', '' );

/**#@+
 * Authentication unique keys and salts.
 *
 * Change these to different unique phrases! You can generate these using
 * the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}.
 *
 * You can change these at any point in time to invalidate all existing cookies.
 * This will force all users to have to log in again.
 *
 * @since 2.6.0
 */
define( 'AUTH_KEY',         '9H,tJY8<qDf_J!:>bn0Y%J^Bn ;}y0oNmC1XRb8zAR^]tX)>aK+j>.V )>$`xvQI' );
define( 'SECURE_AUTH_KEY',  ',ZN63oqF2W-FU[jbW^W$U_;#X~4SgiIG[|Q:?Gjt} ]T;`&UFCcYY]TZ+L;[upkM' );
define( 'LOGGED_IN_KEY',    'l*/uU!|6dXcKdTxdxk0BEsFZv&eMr^gUi0A;BT<A.p: :39Yu=E}|tw^PC;@c0UW' );
define( 'NONCE_KEY',        'HFAhH!%F}R~%@1&pCwd-[#Gs`U)Bg#G0O;5Sgb3Snz72_ZZJtFSp1iD-@[`KYko]' );
define( 'AUTH_SALT',        '#A1ZLW61[CpurUWeM%W~$9;ttZMa48AoevM%xa5AH<O>scO-(=%#|gi8K[Rttj(_' );
define( 'SECURE_AUTH_SALT', '+6q?wk-|IH5fWP|XCE^4A1ax(I`jM;TO3%rna S[E)e4-fGTXftTpA!:ieeU*Iq9' );
define( 'LOGGED_IN_SALT',   '.kB2L}kqUoE?cD`IboGd1qC6=UcCDDEJ5@4DrwCwqqhf:ny(f/woJ,g87gda%Lx$' );
define( 'NONCE_SALT',       '8A$dxd$+%;(2vau:^>zct|uJqeK~Vy6.;yO>zD<yBRQ_q#k)`7A<s&L9u@rHw;3 ' );

/**#@-*/

/**
 * WordPress database table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 *
 * At the installation time, database tables are created with the specified prefix.
 * Changing this value after WordPress is installed will make your site think
 * it has not been installed.
 *
 * @link https://developer.wordpress.org/advanced-administration/wordpress/wp-config/#table-prefix
 */
$table_prefix = 'tb_';

/**
 * For developers: WordPress debugging mode.
 *
 * Change this to true to enable the display of notices during development.
 * It is strongly recommended that plugin and theme developers use WP_DEBUG
 * in their development environments.
 *
 * For information on other constants that can be used for debugging,
 * visit the documentation.
 *
 * @link https://developer.wordpress.org/advanced-administration/debug/debug-wordpress/
 */
define( 'WP_DEBUG', false );

/* Add any custom values between this line and the "stop editing" line. */



/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', __DIR__ . '/' );
}

/** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-settings.php';
