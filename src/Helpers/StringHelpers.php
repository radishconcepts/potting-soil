<?php

namespace RadishConcepts\PottingSoil\Helpers;

class StringHelpers {

	public static function trailingslashit( $value ) {
		return self::untrailingslashit( $value ) . '/';
	}


	public static function untrailingslashit( $value ) {
		return rtrim( $value, '/\\' );
	}
}