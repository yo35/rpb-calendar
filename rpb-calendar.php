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
Plugin URI: http://wordpress.org/plugins/rpb-calendar/
Description: Create and organize events, and display them in a calendar in post/page on your website.
Text Domain: rpbcalendar
Author: Yoann Le Montagner
License: GPLv3
Version: 1.99
*/


// Directories
define('RPBCALENDAR_PLUGIN_DIR', basename(dirname(__FILE__)));
define('RPBCALENDAR_ABSPATH'   , ABSPATH.'wp-content/plugins/'.RPBCALENDAR_PLUGIN_DIR.'/');
define('RPBCALENDAR_URL'       , site_url().'/wp-content/plugins/'.RPBCALENDAR_PLUGIN_DIR);


// Enable internationalization
load_plugin_textdomain('rpbcalendar', false, RPBCALENDAR_PLUGIN_DIR.'/languages/');


// Initialize the database objects
add_action('init', 'rpbcalendar_init');
function rpbcalendar_init()
{
	require_once(RPBCALENDAR_ABSPATH . 'wp/eventclass.php');
	RPBCalendarEventClass::register();
}


// Enqueue scripts
add_action(is_admin() ? 'admin_enqueue_scripts' : 'wp_enqueue_scripts', 'rpbcalendar_enqueue_scripts');
function rpbcalendar_enqueue_scripts()
{
	// Loading indicator
	$dependencies = array('jquery-ui-widget');
	wp_register_script('rpbcalendar-spinamin', RPBCALENDAR_URL.'/js/spinanim.js', $dependencies);
	wp_enqueue_script('rpbcalendar-spinamin');

	// qTip2
	$dependencies = array('jquery');
	wp_register_script('rpbcalendar-qtip2', RPBCALENDAR_URL.'/third-party-libs/qtip2/jquery.qtip.min.js', $dependencies);
	wp_enqueue_script('rpbcalendar-qtip2');

	// FullCalendar
	$dependencies = array('jquery-ui-widget');
	wp_register_script('rpbcalendar-fullcalendar', RPBCALENDAR_URL.'/fullcalendar/fullcalendar.min.js', $dependencies);
	wp_enqueue_script('rpbcalendar-fullcalendar');

	// Additional scripts for the backend.
	if(is_admin()) {
		wp_enqueue_script('jquery-ui-datepicker');

		// Color-picker
		$dependencies = array('jquery-ui-widget', 'iris');
		wp_register_script('rpbcalendar-iris2', RPBCALENDAR_URL.'/js/iris2.js', $dependencies);
		wp_enqueue_script('rpbcalendar-iris2');
	}
}


// Enqueue CSS
add_action(is_admin() ? 'admin_print_styles' : 'wp_print_styles', 'rpbcalendar_enqueue_css');
function rpbcalendar_enqueue_css()
{
	// qTip2
	wp_register_style('rpbcalendar-qtip2', RPBCALENDAR_URL.'/third-party-libs/qtip2/jquery.qtip.min.css');
	wp_enqueue_style('rpbcalendar-qtip2');

	// FullCalendar
	wp_register_style('rpbcalendar-fullcalendar', RPBCALENDAR_URL.'/fullcalendar/fullcalendar.css');
	wp_enqueue_style('rpbcalendar-fullcalendar');

	// Main CSS.
	wp_register_style('rpbcalendar-main', RPBCALENDAR_URL.'/css/main.css');
	wp_enqueue_style('rpbcalendar-main');

	// Additional CSS for the backend.
	if(is_admin()) {

		// Color-picker
		wp_register_style('rpbcalendar-iris2', RPBCALENDAR_URL.'/css/iris2.css');
		wp_enqueue_style('rpbcalendar-iris2');

		wp_register_style('rpbcalendar-jquery-ui', RPBCALENDAR_URL.'/css/jquery-ui-1.10.4.custom.min.css');
		wp_register_style('rpbcalendar-backend'  , RPBCALENDAR_URL.'/css/backend.css');
		wp_enqueue_style('rpbcalendar-jquery-ui');
		wp_enqueue_style('rpbcalendar-backend'  );
	}

	// Additional CSS for the frontend.
	else {
		wp_register_style('rpbcalendar-frontend', RPBCALENDAR_URL.'/css/frontend.css');
		wp_enqueue_style('rpbcalendar-frontend');
	}
}


// Short-code registration
if(!is_admin()) {
	add_action('init', 'rpbcalendar_init_shortcodes');
	function rpbcalendar_init_shortcodes()
	{
		require_once(RPBCALENDAR_ABSPATH . 'wp/shortcodes.php');
		RPBCalendarShortcodes::register();
	}
}


// Plugin administration pages
if(is_admin()) {
	add_action('admin_menu', 'rpbcalendar_init_admin_pages');
	function rpbcalendar_init_admin_pages()
	{
		require_once(RPBCALENDAR_ABSPATH . 'wp/adminpages.php');
		RPBCalendarAdminPages::register();
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
