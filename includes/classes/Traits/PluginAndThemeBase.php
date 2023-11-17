<?php

namespace RadishConcepts\PottingSoil\Traits;

use RadishConcepts\PottingSoil\Plugin;
use RadishConcepts\PottingSoil\PottingSoil;
use RadishConcepts\PottingSoil\Theme;

trait PluginAndThemeBase {
	private static self $instance;


	private string $path;
	private string $url;
	private string $name;
	private string $basename;
	private string $textdomain;

	/**
	 * Initialize the plugin or theme.
	 *
	 * @param ...$args
	 */
	private function __construct( ...$args ) {
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

		//PottingSoil::get_instance();

		if ( str_ends_with( static::class, 'Plugin' ) ) {

			$args[ 'path' ]     = plugin_dir_path( $file );
			$args[ 'url' ]      = plugin_dir_url( $file );
		} elseif ( str_ends_with( static::class, 'Theme' ) ) {

			$args[ 'path' ]     = get_template_directory();
			$args[ 'url' ]      = get_template_directory_uri();
		} else {

			throw new \RuntimeException( 'The class name must end with "Plugin" or "Theme".' );
		}

		$args[ 'basename' ]     = $args[ 'basename' ] ?? wp_basename( $args[ 'path' ] );
		$args[ 'textdomain' ]   = $args[ 'textdomain' ] ?? wp_basename( $args[ 'path' ] );
		$args[ 'name' ]         = ucfirst( $args[ 'name' ] ?? $args[ 'basename' ] );

		self::$instance = new self( ...$args );
	}

	/**
	 * Return the instance of the plugin or theme.
	 *
	 * @return Plugin|Theme
	 */
	public static function get_instance(): Plugin|Theme {
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
	 * @param string|null $append The string to append to the path.
	 *
	 * @return string
	 */
	public static function path( string $append = null ): string {
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