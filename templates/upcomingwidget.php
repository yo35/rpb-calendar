<?php

	// Widget title
	$widget_title = get_option('rpbcalendar_upcoming_widget_title', __('Upcoming events', 'rpbcalendar'));

	// Display
	echo $args['before_widget'];
	echo $args['before_title'].htmlspecialchars($widget_title).$args['after_title'];
	echo 'TODO';
	echo $args['after_widget'];

?>
