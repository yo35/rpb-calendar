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


/**
 * Register the plugin administration pages in the Wordpress backend.
 *
 * This class is not constructible. Call the static method `register()`
 * to trigger the registration operations.
 */
abstract class RPBCalendarAdminPages
{
	/**
	 * Register the plugin administration pages. Must be called only once.
	 */
	public static function register()
	{
		// Page "options"
		add_submenu_page('edit.php?post_type=rpbevent',
			__('Events and calendar settings', 'rpbcalendar'),
			__('Settings', 'rpbcalendar'),
			'manage_options', 'rpbcalendar-options', array(__CLASS__, 'callbackPageOptions')
		);


		// Page "about"
		add_submenu_page('edit.php?post_type=rpbevent',
			sprintf(__('About %1$s', 'rpbcalendar'), 'RPB Calendar'),
			__('About', 'rpbcalendar'),
			'manage_options', 'rpbcalendar-about', array(__CLASS__, 'callbackPageAbout')
		);
	}


	public static function callbackPageOptions() { self::printAdminPage('AdminPageOptions'); }
	public static function callbackPageAbout  () { self::printAdminPage('AdminPageAbout'  ); }


	/**
	 * Load and print the plugin administration page defined by the model `$modelName`.
	 *
	 * @param string $modelName
	 */
	private static function printAdminPage($modelName)
	{
		require_once(RPBCALENDAR_ABSPATH . 'controllers/adminpage.php');
		$controller = new RPBCalendarControllerAdminPage($modelName);
		$controller->run();
	}
}
