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


require_once(RPBCALENDAR_ABSPATH . 'helpers/loader.php');


/**
 * Widget presenting the upcoming events.
 */
class RPBCalendarWidgetUpcoming extends WP_Widget
{
	/**
	 * Register the widget class (should be called only once).
	 */
	public static function register()
	{
		register_widget(__CLASS__);
	}


	/**
	 * Constructor.
	 */
	public function __construct()
	{
		parent::__construct(
			'rpbcalendar-upcoming-events',
			__('Upcoming events', 'rpbcalendar'),
			array(
				'description' => __('A list of the upcoming events within a certain date range.', 'rpbcalendar')
			)
		);
	}


	/**
	 * Render the widget in the frontend.
	 */
	public function widget($theme, $instance)
	{
		$model = RPBCalendarHelperLoader::loadModel('WidgetPrintUpcoming', $instance, $theme);
		$view = RPBCalendarHelperLoader::loadView($model);
		$view->display();
	}


	/**
	 * Update the parameters of a widget instance.
	 */
	public function update($newInstance, $oldInstance)
	{
		$model = RPBCalendarHelperLoader::loadModel('WidgetUpdateUpcoming', $newInstance, $oldInstance);
		return $model->getValidatedInstance();
	}


	/**
	 * Generate the configuration form in the backend interface.
	 */
	public function form($instance)
	{
		$model = RPBCalendarHelperLoader::loadModel('WidgetEditUpcoming', $instance, $this);
		$view = RPBCalendarHelperLoader::loadView($model);
		$view->display();
	}
}
