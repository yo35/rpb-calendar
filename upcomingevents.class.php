<?php

// Upcoming events
class RpbcUpcomingEvents extends WP_Widget
{
	// Constructor
	function __construct()
	{
		$widget_ops = array(
			'description' => __('Display a list of upcoming events', 'rpbcalendar')
		);
		parent::__construct('rpbcalendar_upcoming_events', __('Upcoming events', 'rpbcalendar'), $widget_ops);
	}

	// Display
	function widget($args, $instance)
	{
		include(RPBCALENDAR_ABSPATH.'templates/upcomingwidget.php');
	}

	// Update
	function update($new_instance, $old_instance)
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
	}

	// Configuration
	function form($instance)
	{
		$title             = __('Upcoming events', 'rpbcalendar');
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
		echo '</p>';
	}
}

?>
