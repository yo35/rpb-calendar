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
 * Load the date/time meta information associated to an event.
 */
class RPBCalendarTraitEventDateTime extends RPBCalendarAbstractTrait
{
	private $eventID;
	private $dateBegin    ;
	private $dateBeginInfo;
	private $dateBeginStr ;
	private $dateEnd    ;
	private $dateEndInfo;
	private $dateEndStr ;


	/**
	 * Constructor.
	 *
	 * @param object $event
	 */
	public function __construct($eventID)
	{
		$this->eventID = $eventID;
	}


	/**
	 * Return the begin date of the given event.
	 *
	 * @return int Timestamp
	 */
	public function getEventDateBegin()
	{
		if(is_null($this->dateBegin)) {
			$value = RPBCalendarHelperValidation::validateDate(get_post_meta($this->eventID, 'event_date_begin', true));
			$this->dateBegin = is_null($value) ? RPBCalendarHelperValidation::validateDate(time()) : $value;
		}
		return $this->dateBegin;
	}


	/**
	 * Return the end date of the given event.
	 *
	 * @return int Timestamp
	 */
	public function getEventDateEnd()
	{
		if(is_null($this->dateEnd)) {
			$value     = RPBCalendarHelperValidation::validateDate(get_post_meta($this->eventID, 'event_date_end', true));
			$dateBegin = $this->getEventDateBegin();
			$this->dateEnd = (is_null($value) || $value < $dateBegin) ? $dateBegin : $value;
		}
		return $this->dateEnd;
	}


	/**
	 * Return the detailed information about the begin date of the given event.
	 *
	 * @return array See the PHP function `getdate`.
	 */
	public function getEventDateBeginInfo()
	{
		if(is_null($this->dateBeginInfo)) {
			$this->dateBeginInfo = getdate($this->getEventDateBegin());
		}
		return $this->dateBeginInfo;
	}


	/**
	 * Return the detailed information about the end date of the given event.
	 *
	 * @return array See the PHP function `getdate`.
	 */
	public function getEventDateEndInfo()
	{
		if(is_null($this->dateEndInfo)) {
			$this->dateEndInfo = getdate($this->getEventDateEnd());
		}
		return $this->dateEndInfo;
	}


	/**
	 * Return the begin date of the given event formatted as a string.
	 */
	public function getEventDateBeginAsString()
	{
		if(is_null($this->dateBeginStr)) {
			$this->dateBeginStr = date('Y-m-d', $this->getEventDateBegin());
		}
		return $this->dateBeginStr;
	}


	/**
	 * Return the end date of the given event formatted as a string.
	 */
	public function getEventDateEndAsString()
	{
		if(is_null($this->dateEndStr)) {
			$this->dateEndStr = date('Y-m-d', $this->getEventDateEnd());
		}
		return $this->dateEndStr;
	}
}
