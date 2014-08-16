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


require_once(RPBCALENDAR_ABSPATH . 'models/traits/abstracttrait.php');
require_once(RPBCALENDAR_ABSPATH . 'helpers/validation.php');


/**
 * Update the meta information associated to an event.
 */
class RPBCalendarTraitPostEvent extends RPBCalendarAbstractTrait
{
	private $dateBegin;
	private $dateEnd;
	private $link;


	/**
	 * Constructor.
	 */
	public function __construct()
	{
		// Load the begin/end dates.
		if(isset($_POST['rpbevent_date_begin']) && isset($_POST['rpbevent_date_end'])) {
			$dateBegin = RPBCalendarHelperValidation::validateDate($_POST['rpbevent_date_begin']);
			$dateEnd   = RPBCalendarHelperValidation::validateDate($_POST['rpbevent_date_end'  ]);
			if(isset($dateBegin) && isset($dateEnd)) {
				$this->dateBegin = min($dateBegin, $dateEnd);
				$this->dateEnd   = max($dateBegin, $dateEnd);
			}
		}

		// Load the link.
		if(isset($_POST['rpbevent_link'])) {
			$this->link = RPBCalendarHelperValidation::validateString($_POST['rpbevent_link']);
		}
	}


	/**
	 * Update the date/time meta-information of the post identified by the given ID.
	 */
	public function updateEvent($eventID)
	{
		// Update the dates.
		$dateBegin = $this->getPostEventDateBeginAsString();
		$dateEnd   = $this->getPostEventDateEndAsString  ();
		if(isset($dateBegin) && isset($dateEnd)) {
			update_post_meta($eventID, 'rpbevent_date_begin', $dateBegin);
			update_post_meta($eventID, 'rpbevent_date_end'  , $dateEnd  );
		}

		// Update the link.
		if(isset($this->link)) {
			update_post_meta($eventID, 'rpbevent_link', $this->link);
		}
	}


	/**
	 * New begin date for the event, formatted as string.
	 *
	 * @return int Timestamp, or null if no new begin date is defined.
	 */
	private function getPostEventDateBeginAsString()
	{
		return isset($this->dateBegin) ? date('Y-m-d', $this->dateBegin) : null;
	}


	/**
	 * New end date for the event, formatted as string.
	 *
	 * @return int Timestamp, or null if no new end date is defined.
	 */
	private function getPostEventDateEndAsString()
	{
		return isset($this->dateEnd) ? date('Y-m-d', $this->dateEnd) : null;
	}
}
