<?php
define('WP_CACHE', true); // Added by WP Rocket
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
define('DB_NAME', 'congnx_bihanguc');

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
define('AUTH_KEY',         ']q|;2Pq<s[YD s|oXP6W|=@QKRBMHuL}ro}N tWvat@Z[x8;6)XR%7_.j/<if*eR');
define('SECURE_AUTH_KEY',  'rv#Lyy~(a0I;:?/LwCYU5eS<KBub KEGDB|dP5=iF<0b`mU9Vb15nS8cd4!]$p2G');
define('LOGGED_IN_KEY',    'o@YW)n6Xj!)zr5TRwEaiE@dVr`1A6:<tl/lN8m(L>LXsh%C>!p/QjFbEQ tZB<uM');
define('NONCE_KEY',        'PMS9U8&!itWfnq.>zfEGnvm,>6:xBnXSEWPr]G6k^E`D@LAi/z#y#Q)M(*167:xa');
define('AUTH_SALT',        '_czqD,_NIc3sQKTV+nl85lf. Du6<rHxiTf}a#B2`C?XET/AWM*bk[Gp6C6DA7oc');
define('SECURE_AUTH_SALT', '=E/$?-yUanPT~HIrVkK[;mZChZlsX G0G;N1h2u*ne16yWL2>O`)]-TtqmU@W0ns');
define('LOGGED_IN_SALT',   '6j&{a%597<knvPTQV_sC^GhfBawO3%@kr?qm,m+d9Cg5L`Qpyhsk(8&$gY/&PE/ ');
define('NONCE_SALT',       '[2(~ayM;Xi94g9J0,#`;6>$ APsCOj)hOtvv#orkQ e}A?cZO-9$XUWGsU^k`}b(');

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
