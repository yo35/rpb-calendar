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


/**
 * URLs of the AJAX callbacks.
 */
class RPBCalendarTraitAjaxURLs extends RPBCalendarAbstractTrait
{
	private static $fetchEventsURL;
	private static $fetchEventDataURL;


	/**
	 * URL to the fetch-events page.
	 *
	 * @return string
	 */
	public function getFetchEventsURL()
	{
		if(!isset(self::$fetchEventsURL)) {
			return self::$fetchEventsURL = RPBCALENDAR_URL . '/ajax/fetchevents.php';
		}
		return self::$fetchEventsURL;
	}


	/**
	 * URL to the fetch-event-data page.
	 *
	 * @return string
	 */
	public function getFetchEventDataURL()
	{
		if(!isset(self::$fetchEventDataURL)) {
			self::$fetchEventDataURL = RPBCALENDAR_URL . '/ajax/fetcheventdata.php';
		}
		return self::$fetchEventDataURL;
	}
}
