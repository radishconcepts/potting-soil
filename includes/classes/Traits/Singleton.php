<?php

namespace RadishConcepts\PottingSoil\Traits;

trait Singleton {
	protected static self $instance;

	final public static function get_instance(): self {
		if ( static::$instance === null ) {
			static::$instance = new static();
		}

		return static::$instance;
	}

	final public function __construct() {
		$this->init();
	}

	protected function init(): void {}
}