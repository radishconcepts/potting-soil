<?php

namespace RadishConcepts\PottingSoil;

use RadishConcepts\PottingSoil\Helpers\StringHelpers;

class PottingSoil {

	private static ?self $instance = null;

	private string $path;

	private function __construct() {
		// Set the path to the root of PottingSoil.
		$this->path = StringHelpers::trailingslash( dirname( __DIR__, 2 ) );

		// Load the textdomain.
		add_action( 'init', [ $this, 'load_textdomain' ], 1 );
	}

	public function load_textdomain(): void {
		load_textdomain( 'potting-soil', self::path( 'languages' ) . '/' . get_locale() . '.mo' );
	}

	/**
	 * Bootstrap PottingSoil. This one needs to be called in the main file of your plugin and/or theme.
	 *
	 * @return void
	 */
	public static function bootstrap(): void {
		if ( static::$instance === null ) {
			static::$instance = new self();
		}
	}

	/**
	 * Return the instance of the plugin or theme.
	 *
	 * @return PottingSoil|null
	 */
	public static function get_instance(): ?self {
		return self::$instance;
	}

	/**
	 * Return the path to the Potting Soil package.
	 *
	 * @param string $append The string to append to the path.
	 *
	 * @return string
	 */
	public static function path( string $append = '' ): string {
		return self::get_instance()->path . $append;
	}
}