<?php

namespace RadishConcepts\PottingSoil\Helpers;

/**
 * Sanitizers classes. The most of the helpers are based on the Laravel Framework versions.
 *
 * @link https://github.com/laravel/framework/blob/8.x/src/Illuminate/Support/Str.php
 *
 * @package RadishCli\Helpers
 */
class StringHelpers {
	/**
	 * The cache of camel-cased words.
	 *
	 * @var array
	 */
	protected static array $camelCache = [];

	/**
	 * The cache of pascal-cased words.
	 *
	 * @var array
	 */
	protected static array $pascalCache = [];

	/**
	 * The cache of snake-cased words.
	 *
	 * @var array
	 */
	protected static array $snakeCache = [];

	/**
	 * The cache of kebab-cased words.
	 *
	 * @var array
	 */
	protected static array $kebabCache = [];

	/**
	 * Add a trailing slash to a string.
	 *
	 * @param string $string
	 *
	 * @return string
	 */
	public static function trailingslash( string $string ): string {
		return self::untrailingslash( $string ) . '/';
	}

	/**
	 * Remove a trailing slash from a string.
	 *
	 * @param string $string
	 *
	 * @return string
	 */
	public static function untrailingslash( string $string ): string {
		return rtrim( $string, '/' );
	}

	/**
	 * Convert the given string to lower-case.
	 *
	 * @param string $value
	 *
	 * @return string
	 */
	public static function lower( string $value ): string {
		return mb_strtolower( $value, 'UTF-8' );
	}

	/**
	 * Convert the given string to upper-case.
	 *
	 * @param string $value
	 *
	 * @return string
	 */
	public static function upper( string $value ): string {
		return mb_strtoupper( $value, 'UTF-8' );
	}

	/**
	 * Convert a value to camel case.
	 * Example: thisIsCamelCase
	 *
	 * @param string $value
	 *
	 * @return string
	 */
	public static function camel( string $value ): string {

		$cacheKey = $value;

		if (isset( static::$camelCache[ $cacheKey ] ) ) {
			return static::$camelCache[ $cacheKey ];
		}

		$value = static::lower( $value );

		return static::$camelCache[ $cacheKey ]  = lcfirst( static::pascal( $value ) );
	}

	/**
	 * Convert a value to pascal case.
	 * Example: ThisIsPascalCase
	 *
	 * @param string $value
	 *
	 * @return string
	 */
	public static function pascal( string $value ): string {

		$cacheKey = $value;

		if ( isset( static::$pascalCache[ $cacheKey ] ) ) {
			return static::$pascalCache[ $cacheKey ];
		}

		$value = ucwords( str_replace( [ '-', '_' ], ' ', $value ) );

		return static::$pascalCache[ $cacheKey ] = str_replace( ' ', '', $value );
	}

	/**
	 * Convert a value to kebab case.
	 * Example: this-is-kebab-case
	 *
	 * @param string $value
	 *
	 * @return string
	 */
	public static function kebab( string $value ): string {

		$cacheKey = $value;

		if ( isset( static::$kebabCache[ $cacheKey ] ) ) {
			return static::$kebabCache[ $cacheKey ];
		}

		return static::$kebabCache[ $cacheKey ] = str_replace( '_', '-', static::snake( $value ) );
	}

	/**
	 * Convert a value to snake case.
	 * Example: this_is_snake_case
	 *
	 * @param string $value
	 *
	 * @return string
	 */
	public static function snake( string $value ): string {

		$cachedKey = $value;

		if ( isset( static::$snakeCache[ $cachedKey ] ) ) {
			return static::$snakeCache[ $cachedKey ];
		}

		if ( ! ctype_lower( $value ) ) {
			$value = preg_replace( '/\s+/u', '', ucwords( $value ) );

			$value = static::lower( preg_replace( '/(.)(?=[A-Z])/u', '$1' . '_', $value ) );
		}

		return static::$snakeCache[ $cachedKey ] = $value;
	}

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

	/**
	 * Generate a random string. Default length is 16 characters.
	 *
	 * @param int $length
	 *
	 * @return string
	 * @throws Exception
	 */
	public static function random( int $length = 16 ): string {
		$characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
		$characters_length = strlen( $characters );
		$random_string = '';
		for ($i = 0; $i < $length; $i++) {
			$random_string .= $characters[ random_int( 0, $characters_length - 1 ) ];
		}
		return $random_string;
	}
}