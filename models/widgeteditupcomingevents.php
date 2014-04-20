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


require_once(RPBCALENDAR_ABSPATH . 'models/abstract/abstractwidgeteditmodel.php');


/**
 * Model for the setting form of the upcoming events widget.
 */
class RPBCalendarModelWidgetEditUpcomingEvents extends RPBCalendarAbstractWidgetEditModel
{
	private $timeFrameFieldID  ;
	private $timeFrameFieldName;
	private $withTodayFieldID  ;
	private $withTodayFieldName;


	public function __construct($instance, $wpWidget)
	{
		parent::__construct($instance, $wpWidget);
		$this->loadTrait('WidgetUpcomingEvents', $instance);
	}


	/**
	 * ID for the "time frame" field.
	 *
	 * @return string
	 */
	public function getTimeFrameFieldID()
	{
		if(!isset($this->timeFrameFieldID)) {
			$this->timeFrameFieldID = $this->getFieldID('time-frame');
		}
		return $this->timeFrameFieldID;
	}


	/**
	 * Name for the "time frame" field.
	 *
	 * @return string
	 */
	public function getTimeFrameFieldName()
	{
		if(!isset($this->timeFrameFieldName)) {
			$this->timeFrameFieldName = $this->getFieldName('time-frame');
		}
		return $this->timeFrameFieldName;
	}


	/**
	 * ID for the "with today" field.
	 *
	 * @return string
	 */
	public function getWithTodayFieldID()
	{
		if(!isset($this->withTodayFieldID)) {
			$this->withTodayFieldID = $this->getFieldID('with-today');
		}
		return $this->withTodayFieldID;
	}


	/**
	 * Name for the "with today" field.
	 *
	 * @return string
	 */
	public function getWithTodayFieldName()
	{
		if(!isset($this->withTodayFieldName)) {
			$this->withTodayFieldName = $this->getFieldName('with-today');
		}
		return $this->withTodayFieldName;
	}
}
