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


require_once(RPBCALENDAR_ABSPATH.'models/abstract/abstractmodel.php');


/**
 * Model with the methods to use to update the meta-information associated to an
 * event based on an HTTP POST request.
 */
class RPBCalendarModelUpdateEvent extends RPBCalendarAbstractModel
{
	private $traitsLoaded = false;
	private $eventID;


	public function __construct($eventID)
	{
		parent::__construct();
		$this->eventID = $eventID;
	}


	/**
	 * Function to call to process the update request.
	 */
	public function processRequest()
	{
		// If the post is not an event, nothing to do.
		if($_POST['post_type']!='rpbevent') {
			return;
		}

		// Load the required traits.
		if(!$this->traitsLoaded) {
			$this->loadTrait('UpdateEventLink');
			$this->loadTrait('UpdateEventDateTime');
			$this->traitsLoaded = true;
		}

		// Call the update methods defined in the traits.
		$this->updateEventLink    ($this->eventID);
		$this->updateEventDateTime($this->eventID);
	}
}
