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

	// Configuration
	function form($instance)
	{
		/*$title             = __('Upcoming events', 'rpbcalendar');
		$upcoming_range    = 7;
		$show_today_events = false;
		if(isset($instance['title'])) {
			$title = htmlspecialchars($instance['title']);
		}
		if(isset($instance['upcoming_range']) && is_numeric($instance['upcoming_range'])) {
			$upcoming_range = $instance['upcoming_range'];
		}
		if(isset($instance['show_today_events']) && is_numeric($instance['show_today_events'])) {
			$show_today_events = ($instance['show_today_events']!=0);
		}
		echo '<p>';
		echo '<label for="'.$this->get_field_id('title').'">'.__('Title:', 'rpbcalendar').'</label>';
		echo '<input type="text" class="widefat" id="'.$this->get_field_id('title').'" name="'.
			$this->get_field_name('title').'" value="'.$title.'" />';
		echo '</p><p>';
		echo '<label for="'.$this->get_field_id('upcoming_range').'">'.
			__('Length of the upcoming period (in days):', 'rpbcalendar').'</label>';
		echo '<input type="text" class="widefat" id="'.$this->get_field_id('upcoming_range').'" name="'.
			$this->get_field_name('upcoming_range').'" value="'.$upcoming_range.'" />';
		echo '</p><p>';
		echo '<label for="'.$this->get_field_id('show_today_events').'">'.
			__('Show today events:', 'rpbcalendar').'</label>';
		echo '<input type="hidden" name="'.$this->get_field_name('show_today_events').'" value="0" /> ';
		echo '<input type="checkbox" class="widefat" id="'.$this->get_field_id('show_today_events').'" name="'.
			$this->get_field_name('show_today_events').'" value="1"'.($show_today_events ? ' checked="1"' : '').' />';
		echo '</p>';*/
		echo 'TODO: RPBCalendarWidgetUpcomingEvents::form()';
	}
}
