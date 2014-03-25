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
class RPBCalendarTraitUpdateEventDateTime extends RPBCalendarAbstractTrait
{
	private $dateLoaded = false;
	private $dateBegin;
	private $dateEnd  ;


	/**
	 * Update the date/time meta-information of the post identified by the given ID.
	 */
	public function updateEventDateTime($eventID)
	{
		$dateBegin = $this->getPostEventDateBeginAsString();
		$dateEnd   = $this->getPostEventDateEndAsString  ();
		if(!is_null($dateBegin)) {
			update_post_meta($eventID, 'event_date_begin', $dateBegin);
		}
		if(!is_null($dateEnd)) {
			update_post_meta($eventID, 'event_date_end', $dateEnd);
		}
	}


	/**
	 * New begin date for the event.
	 *
	 * @return int Timestamp, or null if no new begin date is defined.
	 */
	public function getPostEventDateBegin()
	{
		$this->ensureDateLoaded();
		return $this->dateBegin;
	}


	/**
	 * New end date for the event.
	 *
	 * @return int Timestamp, or null if no new end date is defined.
	 */
	public function getPostEventDateEnd()
	{
		$this->ensureDateLoaded();
		return $this->dateEnd;
	}


	/**
	 * New begin date for the event, formatted as string.
	 *
	 * @return int Timestamp, or null if no new begin date is defined.
	 */
	public function getPostEventDateBeginAsString()
	{
		$value = $this->getPostEventDateBegin();
		return is_null($value) ? null : date('Y-m-d', $value);
	}


	/**
	 * New end date for the event, formatted as string.
	 *
	 * @return int Timestamp, or null if no new end date is defined.
	 */
	public function getPostEventDateEndAsString()
	{
		$value = $this->getPostEventDateEnd();
		return is_null($value) ? null : date('Y-m-d', $value);
	}


	/**
	 * Read the begin/end dates from the HTTP POST data.
	 */
	private function ensureDateLoaded()
	{
		if($this->dateLoaded) {
			return;
		}
		if(array_key_exists('event_date_begin', $_POST) && array_key_exists('event_date_end', $_POST)) {
			$dateBegin = RPBCalendarHelperValidation::validateDate($_POST['event_date_begin']);
			$dateEnd   = RPBCalendarHelperValidation::validateDate($_POST['event_date_end'  ]);
			if(!is_null($dateBegin) && !is_null($dateEnd)) {
				$this->dateBegin = min($dateBegin, $dateEnd);
				$this->dateEnd   = max($dateBegin, $dateEnd);
			}
		}
		$this->dateLoaded = true;
	}
}
