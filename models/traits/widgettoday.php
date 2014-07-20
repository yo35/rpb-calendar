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
 * Global parameters relative to an instance of a "today-events" widget.
 */
class RPBCalendarTraitWidgetToday extends RPBCalendarAbstractTrait
{
	private $instance;
	private $title;


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
	public function getTodayWidgetFields()
	{
		return array('Title');
	}


	/**
	 * Title of the widget.
	 *
	 * @return string
	 */
	public function getTitle()
	{
		if(!isset($this->title)) {
			$value = isset($this->instance['title']) ? RPBCalendarHelperValidation::validateString($this->instance['title']) : null;
			$this->title = isset($value) ? $value : __('Today\'s events', 'rpbcalendar');
		}
		return $this->title;
	}
}
