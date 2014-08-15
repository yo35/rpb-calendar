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
 * Register the plugin CSS.
 *
 * This class is not constructible. Call the static method `register()`
 * to trigger the registration operations (must be called only once).
 */
abstract class RPBCalendarStyleSheets
{
	public static function register()
	{
		// qTip2
		wp_enqueue_style('rpbcalendar-qtip2', RPBCALENDAR_URL . '/third-party-libs/qtip2/jquery.qtip.min.css');

		// FullCalendar
		wp_enqueue_style('rpbcalendar-fullcalendar', RPBCALENDAR_URL . '/third-party-libs/fullcalendar/fullcalendar.css');

		// Main CSS
		wp_enqueue_style('rpbcalendar-main', RPBCALENDAR_URL . '/css/main.css');

		// Additional CSS for the backend.
		if(is_admin()) {
			wp_enqueue_style('rpbcalendar-jquery-ui', RPBCALENDAR_URL . '/third-party-libs/jquery/jquery-ui-1.10.4.custom.min.css');
			wp_enqueue_style('rpbcalendar-iris2'    , RPBCALENDAR_URL . '/css/iris2.css');
			wp_enqueue_style('rpbcalendar-backend'  , RPBCALENDAR_URL . '/css/backend.css');
		}

		// Additional CSS for the frontend.
		else {
			wp_enqueue_style('rpbcalendar-frontend', RPBCALENDAR_URL . '/css/frontend.css');
		}
	}
}
