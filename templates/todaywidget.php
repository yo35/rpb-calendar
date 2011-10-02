<?php

	// Current day
	$current_time    = rpbcalendar_time();
	$current_day     = date('Y-m-d', $current_time);
	$sql_current_day = "'".mysql_escape_string($current_day)."'";

	// Retrieve events
	global $wpdb;
	$events = $wpdb->get_results(rpbcalendar_select_events_base_sql().
		'WHERE event_begin<='.$sql_current_day.' AND event_end>='.$sql_current_day.' '.
		'ORDER BY event_title;'
	);

	// Special case if no event to display
	if(empty($events)) {
		return;
	}

	// Display
	echo $args['before_widget'];
	if(!empty($instance['title'])) {
		echo $args['before_title'].htmlspecialchars($instance['title']).$args['after_title'];
	}
	include(RPBCALENDAR_ABSPATH.'templates/events.php');
	echo $args['after_widget'];

?>
