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
define('DB_NAME', 'peepso');

/** MySQL database username */
define('DB_USER', 'root');

/** MySQL database password */
define('DB_PASSWORD', '');

/** MySQL hostname */
define('DB_HOST', 'localhost');

/** Database Charset to use in creating database tables. */
define('DB_CHARSET', 'utf8');

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
define('AUTH_KEY',         'W`zP5p4Dz6mm9{/mL7*Kjzu+bzb7==0A,%T>VG,h@<$N}?]v9(&v~D=$iRtt3S~a');
define('SECURE_AUTH_KEY',  '@CP4xPS&B`SjIX<*fPEQ]<7kE&a0jWrmOAUc&yvH1zwL>kQ`Nl.++YDpXS+Y8^LW');
define('LOGGED_IN_KEY',    '6s?@tJ:A;v :5Lpn5x:mTa85PsrEu7#jbYgQo6dpJL{6HcK{Y*SZ@aDk>V~g%pk4');
define('NONCE_KEY',        '#H*]mP;b>&.e<7)&,*-OC>Y`r|/8d3!a+rJQ<y$GH1e0VCSmu4I!F=##5|u^>FgF');
define('AUTH_SALT',        'V.oHJsE]F7FKpqF1cRj-<j| y:0^g7#g IQyC<@ao,t~C/b`X*EAVB8n<|VWU6K%');
define('SECURE_AUTH_SALT', 'xY8FkIzDZxr+.~4/x~8+/I1m1@dKclNyYfaL|L#Hz4T0cA4`[@eoh[+NNG.=zbl@');
define('LOGGED_IN_SALT',   'Zvyp[afUWE&r&Q+5gv|T]&}l:ymesf0,/RJF{:SCUh[o9zUP<!?ijW/x*2yNe+X}');
define('NONCE_SALT',       'Q$k!T$PA?b)/pi_LWv[-bD*X7eG7-B%+xOii<GDn5]B:fESo*@(UK$hFOHHwA!U~');

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
