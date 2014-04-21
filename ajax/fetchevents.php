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


// Find the root directory of the WP engine.
define('RPBCALENDAR_AJAX_DIRECTORY', dirname(__FILE__));
if(file_exists(RPBCALENDAR_AJAX_DIRECTORY . '/config.php'))
{
	// The file config.php may provide an alternative definition of the constant RPBCALENDAR_WP_DIRECTORY.
	require_once(RPBCALENDAR_AJAX_DIRECTORY . '/config.php');
}
if(!defined('RPBCALENDAR_WP_DIRECTORY')) {
	define('RPBCALENDAR_WP_DIRECTORY', dirname(dirname(dirname(dirname(RPBCALENDAR_AJAX_DIRECTORY)))));
}


// Load the WP engine.
define('WP_USE_THEMES', false);
require_once(RPBCALENDAR_WP_DIRECTORY . '/wp-load.php');


// Function to call to print the answer
function printJSON($value)
{
	header('Content-Type: application/json');
	echo json_encode($value);
	die;
}


// Load the model.
require_once(RPBCALENDAR_ABSPATH . 'helpers/loader.php');
$model = RPBCalendarHelperLoader::loadModel('FetchEvents');


// Begin/end dates.
if($model->getFetchIntervalBegin()===null || $model->getFetchIntervalEnd()===null) {
	printJSON(array(
		'error'   => true,
		'message' => 'Missing or invalid start date and/or end date.'
	));
}


// Retrieve the events.
$events = array();
while($model->fetchNextEvent())
{
	$events[] = array(
		'title' => $model->getEventTitle(),
		'start' => $model->getEventDateBegin(),
		'end'   => $model->getEventDateEnd()
	);
}


// Return the fetched events.
printJSON($events);
