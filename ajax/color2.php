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
 * Standalone script that generates a bi-color striped pattern.
 */


// Load the helper functions.
define('RPBCALENDAR_ABSPATH', dirname(dirname(__FILE__)).'/');
require_once(RPBCALENDAR_ABSPATH . 'helpers/color.php');


// Parse the input colors.
$color1 = isset($_GET['c1']) ? RPBCalendarHelperColor::parse($_GET['c1']) : null;
$color2 = isset($_GET['c2']) ? RPBCalendarHelperColor::parse($_GET['c2']) : null;


// Ensure the both color parameters are valid.
if($color1==null || $color2==null) {
	header('Content-Type: application/json');
	echo json_encode(array(
		'error'   => true,
		'message' => 'Invalid color parameter.'
	));
	return;
}


// Size of the sprite.
define('SPRITE_WIDTH' , 48);
define('SPRITE_HEIGHT', 16);


// Create the background image (filled with the first color).
$retVal = imagecreatetruecolor(SPRITE_WIDTH, SPRITE_HEIGHT);
imagefill($retVal, 0, 0, imagecolorallocate($retVal, $color1['r'], $color1['g'], $color1['b']));


// Create the mask image (stripes filled with second color).
$imA = imagecreatefrompng(RPBCALENDAR_ABSPATH . 'images/color2a-pattern.png');
imagesavealpha($imA, true);
imagefilter($imA, IMG_FILTER_COLORIZE, $color2['r'], $color2['g'], $color2['b']);


// Merge both images.
imagecopy($retVal, $imA, 0, 0, 0, 0, SPRITE_WIDTH, SPRITE_HEIGHT);


// Return the result.
header('Content-Type: image/png');
imagepng($retVal);
