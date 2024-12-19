<?php

namespace RadishConcepts\PottingSoil\Traits\Bases;

use RadishConcepts\PottingSoil\Helpers\StringHelpers;
use RadishConcepts\PottingSoil\Plugin;
use RadishConcepts\PottingSoil\PottingSoil;
use RadishConcepts\PottingSoil\Theme;

trait ExtensionBase
{
	private static ?self $instance = null;

	private string $main_file;
	private string $path;
	private string $url;
	private string $name;
	private string $basename;
	private string $textdomain;

	private function __construct( ...$args )
	{
		// Bootstrap PottingSoil, so the WordPress functionality is also available in the package.
		PottingSoil::bootstrap();

		// Set instance properties.
		$this->main_file  = $args[ 'main_file' ];
		$this->path       = $args[ 'path' ];
		$this->url        = $args[ 'url' ];
		$this->name       = $args[ 'name' ];
		$this->basename   = $args[ 'basename' ];
		$this->textdomain = $args[ 'textdomain' ];
	}

	/**
	 * Set up your plugin or theme.
	 *
	 * @param string $file The file that called this method. Normally, you'll pass __FILE__.
	 * @param mixed ...$args The arguments to overwrite the default arguments.
	 *
	 * @return void
	 */
	public static function setup( string $file, ...$args ): void
	{
		// Set path and URL for the plugin or theme.
		if ( str_ends_with( static::class, 'Plugin' ) ) {
			$args[ 'path' ] = StringHelpers::untrailingslash( plugin_dir_path( $file ) );
			$args[ 'url' ]  = StringHelpers::untrailingslash( plugin_dir_url( $file ) );
		} elseif ( str_ends_with( static::class, 'Theme' ) ) {
			$args[ 'path' ] = StringHelpers::untrailingslash( get_template_directory() );
			$args[ 'url' ]  = StringHelpers::untrailingslash( get_template_directory_uri() );
		} else {
			wp_die( 'The class name must end with "Plugin" or "Theme".' );
		}

		// Add the other required arguments.
		$args[ 'main_file' ]  = $file;
		$args[ 'basename' ]   = $args[ 'basename' ] ?? wp_basename( $args[ 'path' ] );
		$args[ 'textdomain' ] = $args[ 'textdomain' ] ?? wp_basename( $args[ 'path' ] );
		$args[ 'name' ]       = ucfirst( $args[ 'name' ] ?? $args[ 'basename' ] );

		// Initiate the class.
		self::$instance = new self( ...$args );
	}

	/**
	 * Return the instance of the plugin or theme.
	 *
	 * @return
	 */
	public static function get_instance()
	{
		return self::$instance;
	}

	/**
	 * Return the basename of the plugin or theme.
	 *
	 * @return string
	 */
	public static function basename(): string
	{
		return self::get_instance()->basename;
	}

	/**
	 * Return the textdomain of the plugin or theme.
	 *
	 * @return string
	 */
	public static function textdomain(): string
	{
		return self::get_instance()->textdomain;
	}

	/**
	 * Return the main file of the plugin or theme. Normally, you'll pass __FILE__ in the main plugin file.
	 *
	 * @return string
	 */
	public static function main_file(): string
	{
		return self::get_instance()->main_file;
	}

	/**
	 * Return the path of the plugin or theme.
	 *
	 * @param string $append The string to append to the path.
	 *
	 * @return string
	 */
	public static function path( string $append = '' ): string
	{
		return trailingslashit( self::get_instance()->path ) . $append;
	}

	/**
	 * Return the URL of the plugin or theme.
	 *
	 * @param string|null $append The string to append to the URL.
	 *
	 * @return string
	 */
	public static function url( string $append = null ): string
	{
		return trailingslashit( self::get_instance()->url ) . $append;
	}
}