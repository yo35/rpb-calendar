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
 * Register the plugin JavaScript scripts.
 *
 * This class is not constructible. Call the static method `register()`
 * to trigger the registration operations (must be called only once).
 */
abstract class RPBCalendarScripts
{
	public static function register()
	{
		$ext = WP_DEBUG ? '.js' : '.min.js';

		// qTip2
		wp_register_script('rpbcalendar-qtip2', RPBCALENDAR_URL . '/third-party-libs/qtip2/jquery.qtip' . $ext, array(
			'jquery'
		));

		// FullCalendar
		wp_register_script('rpbcalendar-fullcalendar', RPBCALENDAR_URL . '/third-party-libs/fullcalendar/fullcalendar' . $ext, array(
			'jquery-ui-widget'
		));

		// Loading indicator
		wp_register_script('rpbcalendar-spinamin', RPBCALENDAR_URL . '/js/spinanim' . $ext, array(
			'jquery-ui-widget'
		));

		// Color-picker
		wp_register_script('rpbcalendar-iris2', RPBCALENDAR_URL . '/js/iris2' . $ext, array(
			'jquery-ui-widget',
			'iris'
		));

		// Plugin functions
		wp_register_script('rpbcalendar-main', RPBCALENDAR_URL . '/js/main' . $ext, array(
			'jquery',
			'rpbcalendar-qtip2',
			'rpbcalendar-spinamin'
		));

		// Enqueue the scripts.
		wp_enqueue_script('rpbcalendar-fullcalendar');
		wp_enqueue_script('rpbcalendar-main'        );

		// Additional scripts for the backend.
		if(is_admin()) {
			wp_enqueue_script('jquery-ui-datepicker');
			wp_enqueue_script('rpbcalendar-iris2'   );
			self::localizeDatePicker();
		}

		// Inlined scripts
		add_action(is_admin() ? 'admin_print_footer_scripts' : 'wp_print_footer_scripts', array(__CLASS__, 'callbackInlinedScripts'));
	}


	public static function callbackInlinedScripts()
	{
		include(RPBCALENDAR_ABSPATH . 'templates/initialization.php');
	}


	/**
	 * Determine the language code to use to configure the jQuery date picker widget, and enqueue the required file.
	 */
	private static function localizeDatePicker()
	{
		foreach(self::getBlogLangCodes() as $langCode)
		{
			// Does the translation script file exist for the current language code?
			$relativeFilePath = 'third-party-libs/jquery/locales/jquery.ui.datepicker-' . $langCode . '.js';
			if(!file_exists(RPBCALENDAR_ABSPATH . $relativeFilePath)) {
				continue;
			}

			// If it exists, enqueue it, set language code, and return.
			wp_enqueue_script('rpbcalendar-datePicker-localization', RPBCALENDAR_URL . '/' . $relativeFilePath, array(
				'jquery-ui-datepicker'
			));
			return;
		}
	}


	/**
	 * Return an array of language codes that may be relevant for the blog.
	 *
	 * @return array
	 */
	private static function getBlogLangCodes()
	{
		$mainLanguage = str_replace('_', '-', strtolower(get_locale()));
		$retVal = array($mainLanguage);

		if(preg_match('/([a-z]+)\\-([a-z]+)/', $mainLanguage, $m)) {
			$retVal[] = $m[1];
		}

		return $retVal;
	}
}
