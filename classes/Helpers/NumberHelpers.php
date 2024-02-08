<?php

namespace RadishConcepts\PottingSoil\Helpers;

class NumberHelpers {

	public static function valutify( $input ): string {
		return '€ ' . str_replace( ',00', ',-', number_format( $input, 2, ',', '' ) );
	}

	public static function floatify( $input ): string {
		return (float) str_replace( ',', '.', $input );
	}

	public static function commafy( $input ): string {
		return str_replace( '.', ',', (float) $input );
	}
}