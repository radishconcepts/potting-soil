<?php

namespace RadishConcepts\PottingSoil\Traits\Features;

trait HasIconsSprite
{
	/**
	 * A Helper to return any SVG from the svg-sprite in assets/images/icons.svg.
	 *
	 * @param string $name The icon name.
	 * @param int $width The width of the icon. By default, 24 px.
	 * @param int $height The height of the icon. By default, 24 px.
	 *
	 * @return string Returns the Xlink svg icon.
	 */
	public static function icon( string $name, int $width = 24, int $height = 24 ): string
	{
		return '
			<svg class="icon icon-' . $name . '" width="' . $width . '"  height="' . $height . '">
				<use xlink:href="' . self::url() . 'assets/images/icons.svg#' . $name . '" />
			</svg>
		';
	}
}