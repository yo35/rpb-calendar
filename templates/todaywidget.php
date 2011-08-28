<?php

	// Current day
	$current_time    = rpbcalendar_time();
	$current_day     = date('Y-m-d', $current_time);
	$current_day_sql = "'".mysql_escape_string($current_day)."'";

	// Retrieve events
	global $wpdb;
	$events = $wpdb->get_results(rpbcalendar_select_events_base_sql().
		'WHERE event_begin<='.$current_day_sql.' AND event_end>='.$current_day_sql.' '.
		'ORDER BY event_time;'
	);

	// Special case if no event to display
	if(empty($events)) {
		return;
	}

	// Widget title
	$widget_title = get_option('rpbcalendar_today_widget_title', __('Today\'s events', 'rpbcalendar'));

	// Display
	echo $args['before_widget'];
	echo $args['before_title'].htmlspecialchars($widget_title).$args['after_title'];
	include(RPBCALENDAR_ABSPATH.'templates/events.php');
	echo $args['after_widget'];

?>
