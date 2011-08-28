<?php

// Today's events
class RpbcTodaysEvents extends WP_Widget
{
	// Constructor
	function __construct()
	{
		$widget_ops = array(
			'description' => __('Display the list of today\'s events', 'rpbcalendar')
		);
		parent::__construct('rpbcalendar_todays_events', __('Today\'s events', 'rpbcalendar'), $widget_ops);
	}

	// Display
	function widget($args, $instance)
	{
		include(RPBCALENDAR_ABSPATH.'templates/todaywidget.php');
	}

	// Update
	function update($new_instance, $old_instance)
	{
		$instance          = $old_instance;
		$instance['title'] = $new_instance['title'];
		return $instance;
	}

	// Configuration
	function form($instance)
	{
		$title = __('Today\'s events', 'rpbcalendar');
		if(isset($instance['title'])) {
			$title = htmlspecialchars($instance['title']);
		}
		echo '<p>';
		echo '<label for="'.$this->get_field_id('title').'">'.__('Title:', 'rpbcalendar').'</label>';
		echo '<input type="text" class="widefat" id="'.$this->get_field_id('title').'" name="'.
			$this->get_field_name('title').'" value="'.$title.'" />';
		echo '</p>';
	}
}

?>
