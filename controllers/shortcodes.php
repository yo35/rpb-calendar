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


require_once(RPBCALENDAR_ABSPATH . 'controllers/abstractcontroller.php');
require_once(RPBCALENDAR_ABSPATH . 'helpers/loader.php');


/**
 * Set-up the frontend shortcodes.
 */
class RPBCalendarControllerShortcodes extends RPBCalendarAbstractController
{
	public function __construct()
	{
		parent::__construct(null);
	}


	public function run()
	{
		// Register the shortcodes
		add_shortcode('rpbcalendar', array(__CLASS__, 'runShortcodeCalendar'));
	}


	/**
	 * Callback for the [rpbcalendar] shortcode.
	 */
	public static function runShortcodeCalendar($atts)
	{
		return self::runShortcode('ShortcodeCalendar', $atts, '');
	}


	/**
	 * Generic callback for the shortcodes.
	 */
	private static function runShortcode($modelName, $atts, $content)
	{
		// Load the model and the view
		$model = RPBCalendarHelperLoader::loadModel($modelName, $atts, $content);
		$view  = RPBCalendarHelperLoader::loadView($model);

		// Display the view
		ob_start();
		$view->display();
		return ob_get_clean();
	}
}
