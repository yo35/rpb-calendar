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


require_once(RPBCALENDAR_ABSPATH.'models/traits/abstracttrait.php');
require_once(RPBCALENDAR_ABSPATH.'helpers/validation.php');


/**
 * Update the link meta-information associated to an event based on an HTTP POST request.
 */
class RPBCalendarTraitUpdateEventLink extends RPBCalendarAbstractTrait
{
	/**
	 * Update the link meta-information of the post identified by the given ID.
	 */
	public function updateEventLink($eventID)
	{
		$eventLink = $this->getPostEventLink();
		if(!is_null($eventLink)) {
			update_post_meta($eventID, 'event_link', $eventLink);
		}
	}


	/**
	 * New event link value.
	 */
	public function getPostEventLink()
	{
		if(array_key_exists('event_link', $_POST)) {
			return RPBCalendarHelperValidation::validateURL($_POST['event_link'], true);
		}
		else {
			return null;
		}
	}
}