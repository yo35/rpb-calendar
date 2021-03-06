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
 * Update the options of the plugin.
 */
class RPBCalendarTraitPostOptions extends RPBCalendarAbstractTrait
{
	private $defaultCategoryColor;
	private $defaultEventColor;
	private $defaultShowWeekDay;
	private $defaultShowYear;


	/**
	 * Constructor.
	 */
	public function __construct()
	{
		// Load the default colors.
		if(isset($_POST['defaultCategoryColor'])) {
			$this->defaultCategoryColor = RPBCalendarHelperValidation::validateColor($_POST['defaultCategoryColor']);
		}
		if(isset($_POST['defaultEventColor'])) {
			$this->defaultEventColor = RPBCalendarHelperValidation::validateColor($_POST['defaultEventColor']);
		}

		// Load the date format options.
		if(isset($_POST['defaultShowWeekDay'])) {
			$this->defaultShowWeekDay = RPBCalendarHelperValidation::validateBoolean($_POST['defaultShowWeekDay']);
		}
		if(isset($_POST['defaultShowYear'])) {
			$this->defaultShowYear = RPBCalendarHelperValidation::validateBoolean($_POST['defaultShowYear']);
		}
	}


	/**
	 * Update the plugin options.
	 *
	 * @return string
	 */
	public function updateOptions()
	{
		// Update the default colors.
		if(isset($this->defaultCategoryColor)) {
			update_option('rpbcalendar_defaultCategoryColor', $this->defaultCategoryColor);
		}
		if(isset($this->defaultEventColor)) {
			update_option('rpbcalendar_defaultEventColor', $this->defaultEventColor);
		}

		// Update the date format options.
		if(isset($this->defaultShowWeekDay)) {
			update_option('rpbcalendar_defaultShowWeekDay', $this->defaultShowWeekDay ? 1 : 0);
		}
		if(isset($this->defaultShowYear)) {
			update_option('rpbcalendar_defaultShowYear', $this->defaultShowYear ? 1 : 0);
		}

		// Notify the user.
		return __('Settings saved.', 'rpbcalendar');
	}
}
