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
 * Register the plugin shortcodes.
 *
 * This class is not constructible. Call the static method `register()`
 * to trigger the registration operations.
 */
abstract class RPBCalendarShortcodes
{
	/**
	 * Register the plugin shortcodes. Must be called only once.
	 */
	public static function register()
	{
		add_shortcode('rpbcalendar', array(__CLASS__, 'callbackShortcodeCalendar'));
	}


	public static function callbackShortcodeCalendar($atts, $content) { return self::runShortcode('Calendar', $atts, $content); }


	/**
	 * Process a shortcode.
	 *
	 * @param string $shortcodeName
	 * @param array $atts
	 * @param string $content
	 * @return string
	 */
	private static function runShortcode($shortcodeName, $atts, $content)
	{
		require_once(RPBCALENDAR_ABSPATH . 'controllers/shortcode.php');
		$controller = new RPBCalendarControllerShortcode($shortcodeName, $atts, $content);
		return $controller->run();
	}
}
