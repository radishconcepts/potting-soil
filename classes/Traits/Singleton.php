<?php

namespace RadishConcepts\PottingSoil\Traits;

trait Singleton
{
	private static array $instances = [];

	final public static function get_instance(): self
	{
		if ( !isset( static::$instances[ static::class ] ) ) {
			static::$instances[ static::class ] = new static();
		}

		return self::$instances[ static::class ];
	}

	final public function __construct()
	{
		$this->init();
	}

	protected function init(): void {}
}