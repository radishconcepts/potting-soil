<?php

namespace RadishConcepts\PottingSoil\Helpers;

class NumberHelpers
{
	/**
	 * Convert a number to a currency value.
	 *
	 * @param $input
	 *
	 * @return string
	 */
	public static function valutify( $input ): string
	{
		return '€ ' . str_replace( ',00', ',-', number_format( $input, 2, ',', '' ) );
	}

	/**
	 * Convert a number to a float.
	 *
	 * @param $input
	 *
	 * @return string
	 */
	public static function floatify( $input ): string
	{
		return (float) str_replace( ',', '.', $input );
	}

	/**
	 * Convert a number to a string.
	 *
	 * @param $input
	 *
	 * @return string
	 */
	public static function commafy( $input ): string
	{
		return str_replace( '.', ',', (float) $input );
	}
}


