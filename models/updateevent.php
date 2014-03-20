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
	private $actionTraitsLoaded = false;
	private $postID;


	public function __construct($postID)
	{
		parent::__construct();
		$this->postID = $postID;
	}


	/**
	 * Function to call to process the update request.
	 */
	public function processRequest()
	{
		// If the post is not an event, nothing to do.
		if($_POST['post_type']!='rpbcalendar_event') {
			return;
		}

		// Load the required traits.
		if(!$this->actionTraitsLoaded) {
			$this->loadTrait('UpdateEventLink');
			$this->actionTraitsLoaded = true;
		}

		// Call the update methods defined in the traits.
		$this->updateEventLink($this->postID);
	}
}
