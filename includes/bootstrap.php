<?php

namespace RadishConcepts\PottingSoil;

class Bootstrap {
	private function __construct() {
		add_action( 'init', [ $this, 'init' ] );
	}

	public function setup(): void {
		//load_textdomain( 'potting-soil', self::path() . 'languages/potting-soil-' . get_locale() . '.mo' );
	}

	public static function path(): string {
		return __DIR__;
	}
}