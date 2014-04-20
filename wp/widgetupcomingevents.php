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
class RPBCalendarWidgetUpcomingEvents extends WP_Widget
{
	/**
	 * Register the widget class (should be called only once).
	 */
	public static function register()
	{
		register_widget('RPBCalendarWidgetUpcomingEvents');
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

	// Display
	function widget($args, $instance)
	{
		//include(RPBCALENDAR_ABSPATH.'templates/upcomingwidget.php');
		echo 'TODO: RPBCalendarWidgetUpcomingEvents::widget()';
	}

	// Update
	/*function update($new_instance, $old_instance)
	{
		$instance          = $old_instance;
		$instance['title'] = $new_instance['title'];
		if(is_numeric($new_instance['upcoming_range'])) {
			$instance['upcoming_range'] = $new_instance['upcoming_range'];
		}
		if(is_numeric($new_instance['show_today_events'])) {
			$instance['show_today_events'] = $new_instance['show_today_events'];
		}
		return $instance;
	}*/


	/**
	 * Generate the configuration form in the backend interface.
	 */
	function form($instance)
	{
		$model = RPBCalendarHelperLoader::loadModel('WidgetFormUpcomingEvents', $instance, $this);
		$view = RPBCalendarHelperLoader::loadView($model);
		$view->display();
	}
}
