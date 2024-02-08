<?php

namespace RadishConcepts\PottingSoil\Traits;

trait ArgsProperties {
	/**
	 * List of arguments for the post type.
	 *
	 * @var array
	 */
	protected array $args = [];

	/**
	 * Set the value of an argument.
	 *
	 * @param string $argument
	 * @param $value
	 *
	 * @return void
	 */
	public function __set( string $argument, $value ): void {
		$this->args[ $argument ] = $value;
	}

	/**
	 * Get the value of an argument.
	 *
	 * @param string $argument
	 *
	 * @return mixed|null
	 */
	public function __get( string $argument ) {
		return $this->args[ $argument ] ?? null;
	}

	/**
	 * Check if an argument is set.
	 *
	 * @param string $argument
	 *
	 * @return bool
	 */
	public function __isset( string $argument ): bool {
		return isset( $this->args[ $argument ] );
	}
}