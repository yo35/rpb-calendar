<?php
/******************************************************************************
 *                                                                            *
 *    This file is part of RPB Calendar, a Wordpress plugin.                  *
 *    Copyright (C) 2014  Yoann Le Montagner <yo35 -at- melix.net>            *
 *                                                                            *
 *    This program is free software: you can redistribute it and/or modify    *
 *    it under the terms of the GNU General Public License as published by    *
 *    the Free Software Foundation, either version 3 of the License, or       *
 *    (at your option) any later version.                                     *
 *                                                                            *
 *    This program is distributed in the hope that it will be useful,         *
 *    but WITHOUT ANY WARRANTY; without even the implied warranty of          *
 *    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the           *
 *    GNU General Public License for more details.                            *
 *                                                                            *
 *    You should have received a copy of the GNU General Public License       *
 *    along with this program.  If not, see <http://www.gnu.org/licenses/>.   *
 *                                                                            *
 ******************************************************************************/


/**
 * Color-related functions.
 */
abstract class RPBCalendarHelperColor
{
	/**
	 * Compute the lightness of the given color.
	 *
	 * @param string $color Accepted format: `#rrggbb` (hexa-decimal digits).
	 * @return float Value in the range 0 (black) - 1 (white).
	 */
	public static function lightness($color)
	{
		// Parse the input.
		if(!preg_match('/^#([0-9a-f]{2})([0-9a-f]{2})([0-9a-f]{2})$/i', $color, $matches)) {
			return null;
		}
		$r = intval($matches[1], 16) / 255;
		$g = intval($matches[2], 16) / 255;
		$b = intval($matches[3], 16) / 255;

		// The lightness is obtained as a convex combination of the RGB compounds.
		return 0.30*$r + 0.59*$g + 0.11*$b;
	}
}
