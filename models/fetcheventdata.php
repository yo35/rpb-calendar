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


require_once(RPBCALENDAR_ABSPATH . 'models/abstract/abstractmodel.php');
require_once(RPBCALENDAR_ABSPATH . 'helpers/validation.php');


/**
 * Retrieve the data (title, content, categories) associated to an event from an
 * AJAX request.
 */
class RPBCalendarModelFetchEventData extends RPBCalendarAbstractModel
{
	private $eventIDValid;


	/**
	 * Whether the event ID is valid or not.
	 *
	 * @return boolean
	 */
	public function isEventIDValid()
	{
		$this->ensureEventLoaded();
		return $this->eventIDValid;
	}


	/**
	 * Load the requested event if not done yet.
	 */
	private function ensureEventLoaded()
	{
		// Nothing to do if an attempt to load the requested event has already be made.
		if(isset($this->eventIDValid)) {
			return;
		}
		$this->eventIDValid = false;

		// Try to read the ID of the requested event from the GET parameters.
		if(!isset($_GET['id'])) {
			return;
		}
		$id = RPBCalendarHelperValidation::validateInteger($_GET['id']);
		if($id===null) {
			return;
		}

		// Fetch the requested event.
		$this->loadTrait('EventQuery', array('id' => $id));
		$this->eventIDValid = $this->fetchEvent();
	}
}
