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


// Load the WP engine.
require_once(dirname(__FILE__) . '/include/bootstrap.php');


// Load the model.
$model = RPBCalendarHelperLoader::loadModel('FetchEventData');


// Check the input parameter.
if(!$model->isEventIDValid()) {
	returnJSON(array(
		'error'   => true,
		'message' => 'Missing or invalid event ID parameter.'
	));
}


// List of categories that contain the currently selected event.
$categories = array();
foreach($model->getEventCategories() as $category) {
	$categories[] = array(
		'ID'    => htmlspecialchars($category->ID),
		'name'  => htmlspecialchars($category->name),
		'color' => htmlspecialchars($category->color)
	);
}

// Data associated to the currently selected event.
$eventData = array(
	'ID'          => htmlspecialchars($model->getEventID()),
	'title'       => htmlspecialchars($model->getEventTitle()),
	'author'      => htmlspecialchars($model->getEventAuthor()),
	'releaseDate' => htmlspecialchars($model->getEventReleaseDate()),
	'beginDate'   => htmlspecialchars($model->getEventDateBeginAsString('text')),
	'endDate'     => $model->getEventDateBegin()===$model->getEventDateEnd() ? '' : htmlspecialchars($model->getEventDateEndAsString('text')),
	'categories'  => $categories,
	'link'        => htmlspecialchars($model->getEventLink()),
	'content'     => $model->getEventContent()
);

// Return the answer.
returnJSON($eventData);
