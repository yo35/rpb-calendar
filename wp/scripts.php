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

		// Moment.js (http://momentjs.com/)
		wp_register_script('rpbcalendar-momentjs', RPBCALENDAR_URL . 'third-party-libs/moment-js/moment' . $ext);
		$momentjs = self::localizeJavaScriptLib('rpbcalendar-momentjs', 'third-party-libs/moment-js/locales/%1$s.js');

		// qTip2 (http://qtip2.com/)
		wp_register_script('rpbcalendar-qtip2', RPBCALENDAR_URL . 'third-party-libs/qtip2/jquery.qtip' . $ext, array(
			'jquery'
		));

		// FullCalendar (http://arshaw.com/fullcalendar/)
		wp_register_script('rpbcalendar-fullcalendar', RPBCALENDAR_URL . 'third-party-libs/fullcalendar/fullcalendar' . $ext, array(
			'jquery-ui-widget'
		));

		// Loading indicator
		wp_register_script('rpbcalendar-spinamin', RPBCALENDAR_URL . 'js/spinanim' . $ext, array(
			'jquery-ui-widget'
		));

		// Color-picker
		wp_register_script('rpbcalendar-iris2', RPBCALENDAR_URL . 'js/iris2' . $ext, array(
			'jquery-ui-widget',
			'iris'
		));

		// jQuery date picker
		$jQueryDatePicker = self::localizeJavaScriptLib('jquery-ui-datepicker', 'third-party-libs/jquery/locales/jquery.ui.datepicker-%1$s.js');

		// Plugin functions
		wp_register_script('rpbcalendar-main', RPBCALENDAR_URL . 'js/main' . $ext, array(
			'jquery',
			'rpbcalendar-spinamin',
			'rpbcalendar-qtip2',
			$momentjs,
			'rpbcalendar-fullcalendar'
		));
		wp_register_script('rpbcalendar-backend', RPBCALENDAR_URL . 'js/backend' . $ext, array(
			'rpbcalendar-main',
			'jquery',
			$jQueryDatePicker,
			$momentjs
		));

		// Enqueue the scripts.
		wp_enqueue_script('rpbcalendar-main');

		// Additional scripts for the backend.
		if(is_admin()) {
			wp_enqueue_script('rpbcalendar-iris2');
			wp_enqueue_script('rpbcalendar-backend');
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
	 *
	 * @param string $handle Handle of the file to localize.
	 * @param string $relativeFilePathTemplate Where the localized files should be searched.
	 * @return string Handle of the localized file a suitable translation has been found, original handle otherwise.
	 */
	private static function localizeJavaScriptLib($handle, $relativeFilePathTemplate)
	{
		foreach(self::getBlogLangCodes() as $langCode)
		{
			// Does the translation script file exist for the current language code?
			$relativeFilePath = sprintf($relativeFilePathTemplate, $langCode);
			if(!file_exists(RPBCALENDAR_ABSPATH . $relativeFilePath)) {
				continue;
			}

			// If it exists, register it, and return a handle pointing to the localization file.
			$localizedHandle = $handle . '-localized';
			wp_register_script($localizedHandle, RPBCALENDAR_URL . $relativeFilePath, array($handle));
			return $localizedHandle;
		}

		// Otherwise, if no translation file exists, return the handle of the original library.
		return $handle;
	}


	/**
	 * Return an array of language codes that may be relevant for the blog.
	 *
	 * @return array
	 */
	private static function getBlogLangCodes()
	{
		if(!isset(self::$blogLangCodes)) {
			$mainLanguage = str_replace('_', '-', strtolower(get_locale()));
			self::$blogLangCodes = array($mainLanguage);

			if(preg_match('/([a-z]+)\\-([a-z]+)/', $mainLanguage, $m)) {
				self::$blogLangCodes[] = $m[1];
			}
		}
		return self::$blogLangCodes;
	}


	/**
	 * Blog language codes.
	 */
	private static $blogLangCodes;
}
