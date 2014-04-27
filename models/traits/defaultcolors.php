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
 * Global settings: default colors for the events and the event categories.
 */
class RPBCalendarTraitDefaultColors extends RPBCalendarAbstractTrait
{
	private static $defaultCategoryColor;
	private static $defaultEventColor;


	/**
	 * Default color for the event categories.
	 *
	 * @return string
	 */
	public function getDefaultCategoryColor()
	{
		if(!isset(self::$defaultCategoryColor)) {
			$value = RPBCalendarHelperValidation::validateColor(get_option('rpbcalendar_defaultCategoryColor'));
			self::$defaultCategoryColor = isset($value) ? $value : '#cc33aa';
		}
		return self::$defaultCategoryColor;
	}


	/**
	 * Default color for the events that do not belong to any category.
	 *
	 * @return string
	 */
	public function getDefaultEventColor()
	{
		if(!isset(self::$defaultEventColor)) {
			$value = RPBCalendarHelperValidation::validateColor(get_option('rpbcalendar_defaultEventColor'));
			self::$defaultEventColor = isset($value) ? $value : '#3344ff';
		}
		return self::$defaultEventColor;
	}
}
