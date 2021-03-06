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
require_once(RPBCALENDAR_ABSPATH . 'helpers/date.php');


/**
 * Model used to render the today events widget in the frontend.
 */
class RPBCalendarModelWidgetPrintToday extends RPBCalendarAbstractModelWidgetPrint
{
	public function __construct($instance, $theme)
	{
		parent::__construct($instance, $theme);
		$this->loadTrait('WidgetToday', $this->instance);
		$this->registerFields($this->getTodayWidgetFields());

		// Load the events.
		$today = date('Y-m-d', RPBCalendarHelperDate::today());
		$where = array(
			'time_frame_begin' => $today,
			'time_frame_end'   => $today
		);
		$where[$this->getInclusiveMode() ? 'category_in' : 'category_not_in'] = $this->getFilteredCategories();
		$this->loadTrait('EventQuery', $where);
	}


	protected function computeIsWidgetHidden()
	{
		return !$this->hasEvent();
	}
}
