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
 * Meta information associated to an event.
 */
class RPBCalendarTraitEvent extends RPBCalendarAbstractTrait
{
	private $eventID = -1;
	private $link        ;
	private $dateBegin   ;
	private $dateBeginStr;
	private $dateEnd     ;
	private $dateEndStr  ;


	/**
	 * ID of the currently selected event.
	 *
	 * @return int
	 */
	public function getEventID()
	{
		return $this->eventID;
	}


	/**
	 * Change the currently selected event.
	 *
	 * @param int $eventID ID of the newly selected event.
	 */
	public function setEventID($eventID)
	{
		if($this->eventID==$eventID) {
			return;
		}
		$this->eventID = $eventID;
		$this->link         = null;
		$this->dateBegin    = null;
		$this->dateBeginStr = null;
		$this->dateEnd      = null;
		$this->dateEndStr   = null;
	}


	/**
	 * Return the web link associated to the currently selected event.
	 *
	 * @return string Either a valid URL or an empty string.
	 */
	public function getEventLink()
	{
		if(is_null($this->link)) {
			$value = RPBCalendarHelperValidation::validateURL(get_post_meta($this->eventID, 'event_link', true), true);
			$this->link = is_null($value) ? '' : $value;
		}
		return $this->link;
	}


	/**
	 * Return the begin date of the currently selected event.
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
	 * Return the end date of the currently selected event.
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
	 * Return the begin date of the currently selected event formatted as a string.
	 *
	 * @return string
	 */
	public function getEventDateBeginAsString()
	{
		if(is_null($this->dateBeginStr)) {
			$this->dateBeginStr = date('Y-m-d', $this->getEventDateBegin());
		}
		return $this->dateBeginStr;
	}


	/**
	 * Return the end date of the currently selected event formatted as a string.
	 *
	 * @return string
	 */
	public function getEventDateEndAsString()
	{
		if(is_null($this->dateEndStr)) {
			$this->dateEndStr = date('Y-m-d', $this->getEventDateEnd());
		}
		return $this->dateEndStr;
	}
}
