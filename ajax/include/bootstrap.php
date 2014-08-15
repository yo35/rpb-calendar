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
 * Bootstrap the WP engine and provide the `returnJSON()` function that must be used
 * to return the answer to an AJAX request.
 */


// Find the root directory of the WP engine.
if(file_exists(dirname(dirname(dirname(__FILE__))) . '/ajax-config.php')) {
	require_once(dirname(dirname(dirname(__FILE__))) . '/ajax-config.php'); // May provide an alternative definition of RPBCALENDAR_WP_DIRECTORY.
}
if(!defined('RPBCALENDAR_WP_DIRECTORY')) {
	define('RPBCALENDAR_WP_DIRECTORY', dirname(dirname(dirname(dirname(dirname(dirname(__FILE__)))))));
}


// Load the WP engine.
define('WP_USE_THEMES', false);
require_once(RPBCALENDAR_WP_DIRECTORY . '/wp-load.php');


/**
 * Echo the answer to the AJAX request in a JSON format and terminate the PHP script.
 *
 * This function does not return.
 *
 * @param array $data Answer to the AJAX request.
 */
function returnJSON($data)
{
	header('Content-Type: application/json');
	echo json_encode($data);
	die;
}
