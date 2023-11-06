<?php

namespace RadishConcepts\PottingSoil\Helpers;

class StringHelpers {
	/**
	 * Add a trailing slash to a string.
	 *
	 * @note This is a copy of the WordPress function, but can be used outside WordPress.
	 *
	 * @param $value
	 *
	 * @return string
	 */
	public static function trailingslashit( $value ) {
		return self::untrailingslashit( $value ) . '/';
	}

	/**
	 * Remove a trailing slash from a string.
	 *
	 * @note This is a copy of the WordPress function, but can be used outside WordPress.
	 *
	 * @param $value
	 *
	 * @return string
	 */
	public static function untrailingslashit( $value ) {
		return rtrim( $value, '/\\' );
	}
}