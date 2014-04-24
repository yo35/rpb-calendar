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
require_once(RPBCALENDAR_ABSPATH . 'helpers/today.php');


/**
 * Meta information associated to an event.
 */
class RPBCalendarTraitEvent extends RPBCalendarAbstractTrait
{
	private static $data = array();
	private $eventID = -1;
	private $event;


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
		$this->event = null;
	}


	/**
	 * Ensure that the object `$this->event` is equal to `self::$data[$this->eventID]`.
	 */
	private function ensureEventLoaded()
	{
		if(isset($this->event)) {
			return;
		}
		if(!isset(self::$data[$this->eventID])) {
			self::$data[$this->eventID] = new stdClass;
		}
		$this->event = self::$data[$this->eventID];
	}


	/**
	 * Title of the currently selected event.
	 *
	 * @return string
	 */
	public function getEventTitle()
	{
		$this->ensureEventLoaded();
		if(!isset($this->event->title)) {
			$this->event->title = get_the_title($this->eventID);
		}
		return $this->event->title;
	}


	/**
	 * Return the categories associated to the currently selected event.
	 *
	 * @return array Array of objects, as returned by the WP function `get_the_terms()`,
	 *         or an empty array if no category is associated to the currently selected event.
	 */
	public function getEventCategories()
	{
		$this->ensureEventLoaded();
		if(!isset($this->event->categories)) {
			$this->event->categories = array();
			$categories = get_the_terms($this->eventID, 'rpbevent_category');
			if(is_array($categories)) {
				foreach($categories as $category) {
					$this->event->categories[] = (object) array(
						'ID'   => $category->term_id,
						'name' => $category->name
					);
				}
			}
		}
		return $this->event->categories;
	}


	/**
	 * Return the web link associated to the currently selected event.
	 *
	 * @return string Either a valid URL or an empty string.
	 */
	public function getEventLink()
	{
		$this->ensureEventLoaded();
		if(!isset($this->event->link)) {
			$value = RPBCalendarHelperValidation::validateURL(get_post_meta($this->eventID, 'rpbevent_link', true), true);
			$this->event->link = isset($value) ? $value : '';
		}
		return $this->event->link;
	}


	/**
	 * Return the begin date of the currently selected event.
	 *
	 * @return int Timestamp
	 */
	public function getEventDateBegin()
	{
		$this->ensureEventLoaded();
		if(!isset($this->event->dateBegin)) {
			$value = RPBCalendarHelperValidation::validateDate(get_post_meta($this->eventID, 'rpbevent_date_begin', true));
			$this->event->dateBegin = isset($value) ? $value : RPBCalendarHelperToday::timestamp();
		}
		return $this->event->dateBegin;
	}


	/**
	 * Return the end date of the currently selected event.
	 *
	 * @return int Timestamp
	 */
	public function getEventDateEnd()
	{
		$this->ensureEventLoaded();
		if(!isset($this->event->dateEnd)) {
			$value = RPBCalendarHelperValidation::validateDate(get_post_meta($this->eventID, 'rpbevent_date_end', true));
			$dateBegin = $this->getEventDateBegin();
			$this->event->dateEnd = (isset($value) && $value>=$dateBegin) ? $value : $dateBegin;
		}
		return $this->event->dateEnd;
	}


	/**
	 * Return the begin date of the currently selected event formatted as a string.
	 *
	 * @param string $format Date format pattern, as specified by the WP `date_i18n()` function.
	 * @return string
	 */
	public function getEventDateBeginAsString($format = 'Y-m-d')
	{
		return date_i18n($format, $this->getEventDateBegin());
	}


	/**
	 * Return the end date of the currently selected event formatted as a string.
	 *
	 * @param string $format Date format pattern, as specified by the WP `date_i18n()` function.
	 * @return string
	 */
	public function getEventDateEndAsString($format = 'Y-m-d')
	{
		return date_i18n($format, $this->getEventDateEnd());
	}
}
