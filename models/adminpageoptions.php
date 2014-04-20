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


require_once(RPBCALENDAR_ABSPATH . 'models/abstract/adminpage.php');
require_once(RPBCALENDAR_ABSPATH . 'helpers/validation.php');


/**
 * Model for the plugin options page.
 */
class RPBCalendarModelAdminPageOptions extends RPBCalendarAbstractAdminPageModel
{
	private $defaultCategoryColor;


	public function getTitle()
	{
		return __('Events and calendar settings', 'rpbcalendar');
	}


	/**
	 * URL to which the the request for modifying the options of the plugin will be dispatched.
	 *
	 * @return string
	 */
	public function getFormActionURL()
	{
		return site_url() . '/wp-admin/edit.php?post_type=rpbevent&page=rpbcalendar-options';
	}


	/**
	 * Default color for the event categories.
	 *
	 * @return string
	 */
	public function getDefaultCategoryColor()
	{
		if(!isset($this->defaultCategoryColor)) {
			$value = RPBCalendarHelperValidation::validateColor(get_option('rpbcalendar_defaultColor'));
			$this->defaultCategoryColor = is_null($value) ? '#ffffcc' : $value;
		}
		return $this->defaultCategoryColor;
	}
}
