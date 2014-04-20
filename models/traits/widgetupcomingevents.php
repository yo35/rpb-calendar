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
 * Global parameters relative to an instance of an upcoming events widget.
 */
class RPBCalendarTraitWidgetUpcomingEvents extends RPBCalendarAbstractTrait
{
	private $instance;
	private $timeFrame;
	private $withToday;


	/**
	 * Constructor.
	 *
	 * @param array $instance Array containing the information relative to the current widget instance.
	 */
	public function __construct($instance)
	{
		$this->instance = $instance;
	}


	/**
	 * Default title of the widget.
	 *
	 * @return string
	 */
	public function getDefaultTitle()
	{
		return __('Upcoming events', 'rpbcalendar');
	}


	/**
	 * Size of the time frame in which events will be displayed (in days).
	 *
	 * @return int
	 */
	public function getTimeFrame()
	{
		if(!isset($this->timeFrame)) {
			$value = isset($this->instance['time-frame']) ? RPBCalendarHelperValidation::validateInteger($this->instance['time-frame'], 1) : null;
			$this->timeFrame = isset($value) ? $value : 7;
		}
		return $this->timeFrame;
	}


	/**
	 * Whether the events of the current day should be included or not.
	 *
	 * @return boolean
	 */
	public function getWithToday()
	{
		if(!isset($this->withToday)) {
			$value = isset($this->instance['with-today']) ? RPBCalendarHelperValidation::validateBooleanFromInt($this->instance['with-today']) : null;
			$this->withToday = isset($value) ? $value : false;
		}
		return $this->withToday;
	}
}