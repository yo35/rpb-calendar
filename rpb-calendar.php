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

/*
Plugin Name: RPB Calendar
Plugin URI: https://wordpress.org/plugins/rpb-calendar/
Description: This plugin allows you to list and schedule events, and to display them in a calendar in a post or a page.
Text Domain: rpbcalendar
Author: Yoann Le Montagner
License: GPLv3
Version: 1.99.6
*/


// Directories
define('RPBCALENDAR_ABSPATH', plugin_dir_path(__FILE__));
define('RPBCALENDAR_URL'    , plugin_dir_url (__FILE__));


// Enable internationalization
load_plugin_textdomain('rpbcalendar', false, basename(dirname(__FILE__)) . '/languages');


// MVC loading tools
require_once(RPBCALENDAR_ABSPATH . 'helpers/loader.php');


// Initialize the database objects
add_action('init', 'rpbcalendar_init');
function rpbcalendar_init()
{
	require_once(RPBCALENDAR_ABSPATH . 'wp/eventclass.php');
	RPBCalendarEventClass::register();
}


// JavaScript & CSS
add_action(is_admin() ? 'admin_enqueue_scripts' : 'wp_enqueue_scripts', 'rpbcalendar_init_js_css');
function rpbcalendar_init_js_css()
{
	require_once(RPBCALENDAR_ABSPATH . 'wp/scripts.php');
	RPBCalendarScripts::register();

	require_once(RPBCALENDAR_ABSPATH . 'wp/stylesheets.php');
	RPBCalendarStyleSheets::register();
}


// Administration pages
if(is_admin()) {
	add_action('admin_menu', 'rpbcalendar_init_admin_pages');
	function rpbcalendar_init_admin_pages()
	{
		require_once(RPBCALENDAR_ABSPATH . 'wp/adminpages.php');
		RPBCalendarAdminPages::register();
	}
}


// Shortcodes
if(!is_admin()) {
	add_action('init', 'rpbcalendar_init_shortcodes');
	function rpbcalendar_init_shortcodes()
	{
		require_once(RPBCALENDAR_ABSPATH . 'wp/shortcodes.php');
		RPBCalendarShortcodes::register();
	}
}


// Widgets
add_action('widgets_init', 'rpbcalendar_init_widgets');
function rpbcalendar_init_widgets()
{
	require_once(RPBCALENDAR_ABSPATH . 'wp/widgetupcoming.php');
	require_once(RPBCALENDAR_ABSPATH . 'wp/widgettoday.php');
	RPBCalendarWidgetUpcoming::register();
	RPBCalendarWidgetToday::register();
}
