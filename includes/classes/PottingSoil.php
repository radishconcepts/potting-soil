<?php

namespace RadishConcepts\PottingSoil;

use RadishConcepts\PottingSoil\Traits\Singleton;

class PottingSoil {
	use Singleton;

	public function init(): void {
		add_action( 'init', [ $this, 'setup' ], 1 );
	}

	public function setup(): void {
		load_textdomain( 'potting-soil', self::path() . 'languages/' . get_locale() . '.mo' );
	}

	public static function path(): string {
		return __DIR__;
	}
}