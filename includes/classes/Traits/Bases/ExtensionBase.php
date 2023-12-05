<?php

namespace RadishConcepts\PottingSoil\Traits\Bases;

use RadishConcepts\PottingSoil\Helpers\StringHelpers;
use RadishConcepts\PottingSoil\Plugin;
use RadishConcepts\PottingSoil\PottingSoil;
use RadishConcepts\PottingSoil\Theme;

trait ExtensionBase {

	private static ?self $instance = null;

	private string $path;
	private string $url;
	private string $name;
	private string $basename;
	private string $textdomain;

	private function __construct( ...$args ) {
		// Check if PottingSoil is bootstrapped.
		if ( PottingSoil::get_instance() === null ) {
			wp_die( 'You need to add "PottingSoil::bootstrap()" in the main file of your plugin and/or theme.' );
		}

		// Set instance properties.
		$this->path         = $args[ 'path' ];
		$this->url          = $args[ 'url' ];
		$this->name         = $args[ 'name' ];
		$this->basename     = $args[ 'basename' ];
		$this->textdomain   = $args[ 'textdomain' ];
	}

	/**
	 * Set up your plugin or theme.
	 *
	 * @param string $file The file that called this method. Normally, you'll pass __FILE__.
	 * @param mixed ...$args The arguments to overwrite the default arguments.
	 *
	 * @return void
	 */
	public static function setup( string $file, ...$args ): void {

		if ( str_ends_with( static::class, 'Plugin' ) ) {

			$args[ 'path' ]     = StringHelpers::untrailingslash( plugin_dir_path( $file ) );
			$args[ 'url' ]      = StringHelpers::untrailingslash( plugin_dir_url( $file ) );
		} elseif ( str_ends_with( static::class, 'Theme' ) ) {

			$args[ 'path' ]     = StringHelpers::untrailingslash( get_template_directory() );
			$args[ 'url' ]      = StringHelpers::untrailingslash( get_template_directory_uri() );
		} else {

			wp_die( 'The class name must end with "Plugin" or "Theme".' );
		}

		$args[ 'basename' ]     = $args[ 'basename' ] ?? wp_basename( $args[ 'path' ] );
		$args[ 'textdomain' ]   = $args[ 'textdomain' ] ?? wp_basename( $args[ 'path' ] );
		$args[ 'name' ]         = ucfirst( $args[ 'name' ] ?? $args[ 'basename' ] );

		self::$instance = new self( ...$args );
	}

	/**
	 * Return the instance of the plugin or theme.
	 *
	 * @return Plugin|Theme|null
	 */
	public static function get_instance(): null|Plugin|Theme {
		return self::$instance;
	}

	/**
	 * Return the textdomain of the plugin or theme.
	 *
	 * @return string
	 */
	public static function textdomain(): string {
		return self::get_instance()->textdomain;
	}

	/**
	 * Return the path of the plugin or theme.
	 *
	 * @param string $append The string to append to the path.
	 *
	 * @return string
	 */
	public static function path( string $append = '' ): string {
		return trailingslashit( self::get_instance()->path ) . $append;
	}

	/**
	 * Return the URL of the plugin or theme.
	 *
	 * @param string|null $append The string to append to the URL.
	 *
	 * @return string
	 */
	public static function url( string $append = null ): string {
		return trailingslashit( self::get_instance()->url ) . $append;
	}
}