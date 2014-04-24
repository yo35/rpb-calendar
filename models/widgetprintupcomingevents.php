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


require_once(RPBCALENDAR_ABSPATH . 'models/abstract/widgetprint.php');
require_once(RPBCALENDAR_ABSPATH . 'helpers/validation.php');
require_once(RPBCALENDAR_ABSPATH . 'helpers/today.php');


/**
 * Model used to render the upcoming events widget in the frontend.
 */
class RPBCalendarModelWidgetPrintUpcomingEvents extends RPBCalendarAbstractWidgetPrintModel
{
	public function __construct($instance, $theme)
	{
		parent::__construct($instance, $theme);
		$this->loadTrait('WidgetUpcomingEvents', $instance);
	}


	/**
	 * Begin date of the time frame.
	 *
	 * @return string
	 */
	private function getTimeFrameBegin()
	{
		$t = RPBCalendarHelperToday::timestamp();
		if(!$this->getWithToday()) {
			$t += 86400; // 86400 = 24*60*60 = number of seconds in a day.
		}
		return date('Y-m-d', $t);
	}


	/**
	 * End date of the time frame.
	 *
	 * @return string
	 */
	private function getTimeFrameEnd()
	{
		$t = RPBCalendarHelperToday::timestamp();
		$t += $this->getTimeFrame() * 86400; // 86400 = 24*60*60 = number of seconds in a day.
		return date('Y-m-d', $t);
	}
}
