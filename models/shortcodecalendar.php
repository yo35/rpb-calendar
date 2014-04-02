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


require_once(RPBCALENDAR_ABSPATH . 'models/abstract/abstractshortcodemodel.php');
require_once(RPBCALENDAR_ABSPATH . 'helpers/validation.php');


/**
 * Model for the shortcode [rpbcalendar].
 */
class RPBCalendarModelShortcodeCalendar extends RPBCalendarAbstractShortcodeModel
{
	private $startOfWeek;


	/**
	 * Day on which the week is assumed to starts.
	 *
	 * @return int 0 for Sunday, 1 for Monday, ..., 6 for Saturday.
	 */
	public function getStartOfWeek()
	{
		if(!isset($this->startOfWeek)) {
			$value = RPBCalendarHelperValidation::validateInteger(get_option('start_of_week'), 0, 6);
			$this->startOfWeek = is_null($value) ? 0 : $value;
		}
		return $this->startOfWeek;
	}
}
