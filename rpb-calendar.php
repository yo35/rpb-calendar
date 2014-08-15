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


// MVC loading tools
require_once(RPBCALENDAR_ABSPATH . 'helpers/loader.php');


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
	}
}


// Localization & configuration scripts
add_action(is_admin() ? 'admin_print_footer_scripts' : 'wp_print_footer_scripts', 'rpbcalendar_inlined_scripts');
function rpbcalendar_inlined_scripts()
{
	include(RPBCALENDAR_ABSPATH . 'templates/initialization.php');
}


// CSS
add_action(is_admin() ? 'admin_print_styles' : 'wp_print_styles', 'rpbcalendar_init_style_sheets');
function rpbcalendar_init_style_sheets()
{
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
