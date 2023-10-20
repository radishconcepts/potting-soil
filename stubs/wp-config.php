<?php

/**
 * This config file is based on the original config file of WordPress itself. This one is customized to the new
 * working method of Radish Concepts.
 *
 * @link https://wordpress.org/support/article/editing-wp-config-php/
 *
 * @package WordPress
 */

// Check of the environment file exists.
if ( !file_exists( __DIR__ . '/wp-config-local.php' ) ) {
	die( 'WordPress environment file could not be loaded.' );
}

// When it exists, load it :-)
require_once __DIR__ . '/wp-config-local.php';

// Place some extra database shizzle here.
define( 'DB_CHARSET', 'utf8' );
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
define( 'AUTH_KEY',         'put your unique phrase here' );
define( 'SECURE_AUTH_KEY',  'put your unique phrase here' );
define( 'LOGGED_IN_KEY',    'put your unique phrase here' );
define( 'NONCE_KEY',        'put your unique phrase here' );
define( 'AUTH_SALT',        'put your unique phrase here' );
define( 'SECURE_AUTH_SALT', 'put your unique phrase here' );
define( 'LOGGED_IN_SALT',   'put your unique phrase here' );
define( 'NONCE_SALT',       'put your unique phrase here' );

/**#@-*/

/**
 * WordPress database table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix = 'wp_';

/**
 * Disable automatic updates.
 * @see http://codex.wordpress.org/Configuring_Automatic_Background_Updates
 */
define( 'AUTOMATIC_UPDATER_DISABLED', true );

/**
 * Some extra defines for performance and security.
 */
define( 'WP_POST_REVISIONS', 3 ); // 3 revisions should be enough, use "false" to deactivate post revisions
define( 'AUTOSAVE_INTERVAL', 300 ); // seconds, increase to something like "9999" to `disable` autosave
define( 'EMPTY_TRASH_DAYS', 365 ); // empty trash for files longer than a year in trash, use "0" to completely deactivate trash, WP standard is 30 days
define( 'CORE_UPGRADE_SKIP_NEW_BUNDLED', true ); // skip the wp-content directory while updating, because we don't use the default themes
define( 'DISALLOW_FILE_EDIT', true ); // disable file editing in the backend
define( 'CONCATENATE_SCRIPTS', false ); //prevent WP from concatenating internal scripts

/**
 * Set standard WordPress theme.
 */
define( 'WP_DEFAULT_THEME', '' ); // Set the default theme folder name here.

/* Add any custom values between this line and the "stop editing" line. */



/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', __DIR__ . '/' );
}

/** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-settings.php';