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
 * Global parameters relative to an instance of an upcoming widget.
 */
class RPBCalendarTraitWidgetUpcoming extends RPBCalendarAbstractTrait
{
	private $instance;
	private $title;
	private $timeFrame;
	private $withToday;


	/**
	 * Constructor.
	 *
	 * @param array $instance
	 */
	public function __construct($instance)
	{
		$this->instance = $instance;
	}


	/**
	 * Fields of the widget.
	 *
	 * @return array
	 */
	public function getUpcomingWidgetFields()
	{
		return array('Title', 'TimeFrame', 'WithToday');
	}


	/**
	 * Title of the widget.
	 *
	 * @return string
	 */
	public function getTitle()
	{
		if(!isset($this->title)) {
			$value = isset($this->instance['Title']) ? RPBCalendarHelperValidation::validateString($this->instance['Title']) : null;
			$this->timeFrame = isset($value) ? $value : __('Upcoming events', 'rpbcalendar');
		}
		return $this->title;
	}


	/**
	 * Size of the time frame in which events will be displayed (in days).
	 *
	 * @return int
	 */
	public function getTimeFrame()
	{
		if(!isset($this->timeFrame)) {
			$value = isset($this->instance['TimeFrame']) ? RPBCalendarHelperValidation::validateInteger($this->instance['TimeFrame'], 1) : null;
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
			$value = isset($this->instance['WithToday']) ? RPBCalendarHelperValidation::validateBoolean($this->instance['WithToday']) : null;
			$this->withToday = isset($value) ? $value : false;
		}
		return $this->withToday;
	}
}
