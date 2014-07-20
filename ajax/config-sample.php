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
 * Special settings for the AJAX API of the RPB Calendar plugin.
 *
 * The AJAX API of the RPB Calendar plugin is designed to work "out-of-the-box"
 * in standard configurations. Therefore, in most situations, the file
 * `rpb-calendar/ajax/config.php` MUST NOT exist. Do not create it unless you
 * are sure of you do.
 */


/**
 * Path to the root directory of the WordPress engine.
 *
 * The constant `RPBCALENDAR_WP_DIRECTORY` must be set such that
 * `RPBCALENDAR_WP_DIRECTORY . '/wp-load.php'` is a valid path to the PHP file
 * in charge of loading the WP engine (i.e. `wp-load.php` in the root directory
 * of your WordPress blog).
 *
 * By default, this constant is defined in the file `rpb-calendar/ajax/bootstrap.php`
 * as `dirname(dirname(dirname(dirname(dirname(__FILE__)))))`.
 */
define('RPBCALENDAR_WP_DIRECTORY', '/path/to/the/wpEngine');
