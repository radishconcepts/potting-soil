<?php

namespace RadishConcepts\PottingSoil\Helpers;

class ArrayHelpers {
	/**
	 * Check if needle(s) exists in haystack.
	 *
	 * @note This helper only works for one dimensional arrays.
	 *
	 * @param array|string $needles The needle(s) to search for.
	 * @param array $haystack The array to search in.
	 * @param bool $strict Whether to use strict comparison.
	 *
	 * @return bool
	 */
	public static function exists( array|string $needles, array $haystack, bool $strict = false ): bool {
		// If the needles are not an array, convert them to an array by splitting on comma.
		if ( ! is_array( $needles ) ) {
			$needles = explode( ',', $needles );
		}

		// Default there is nothing found.
		$needles_found = false;

		// Loop through the needles and check if they exist in the array.
		foreach ( $needles as $needle ) {
			if ( in_array( $needle, $haystack, $strict ) ) {
				$needles_found = true;
			}
		}

		// Return the result.
		return $needles_found;
	}

	/**
	 * Convert an array to a key value pair array.
	 *
	 * This helper only works for one dimensional arrays. When the array is
	 * multidimensional, the array will be returned as is.
	 *
	 * The returning array will be sorted by key. To disable this, set the
	 * $sort parameter to false.
	 *
	 * @param array $array
	 * @param bool $sort
	 *
	 * @return array
	 */
	public static function toValueKeyPairs( array $array, bool $sort = true ): array {
		// When the array is multidimensional, return the array as is.
		if ( self::isMultiDimensional( $array ) ) {
			return $array;
		}

		// Create a new array.
		$new_array = [];

		// Loop through the array and create a new array with the key and value swapped.
		foreach ( $array as $key => $value ) {
			// When the value is empty, skip it.
			if ( empty( $value ) ) {
				continue;
			}

			// Add the value and key to the new array.
			$new_array[ $value ] = $key;
		}

		// Sort the array.
		if ( $sort === true ) {
			ksort( $new_array );
		}

		// Return the new array.
		return $new_array;
	}

	/**
	 * Check if an array is multidimensional.
	 *
	 * @param array $array
	 *
	 * @return bool
	 */
	public static function isMultiDimensional( array $array ): bool {
		return count( $array ) !== count( $array, COUNT_RECURSIVE );
	}

	/**
	 * Fill the keys with your (multidimensional) array with the provided keys.
	 *
	 * @param array $array (Multidimensional) array.
	 * @param array $keys Array with values that must be used as keys in $array.
	 *
	 * @return array
	 */
	public static function fillKeys( array $array, array $keys ): array {

		return array_map( static function ( $row ) use ( $keys ) {

			$columns = [];

			foreach ( $row as $column => $value ) {

				if ( isset( $keys[ $column ] ) ) {

					$columns[ $keys[ $column ] ] = $value;
				}
			}

			return $columns;
		}, $array );
	}
}