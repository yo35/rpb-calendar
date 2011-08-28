<?php

	// Current day
	$current_time    = rpbcalendar_time();
	$current_day     = date('Y-m-d', $current_time);
	$current_day_sql = "'".mysql_escape_string($current_day)."'";

	// Retrieve events
	global $wpdb;
	$select_part = 'SELECT event_title, event_desc, event_time, event_link ';
	$from_part   = 'FROM '.RPBCALENDAR_EVENT_TABLE.' ';
	if(rpbcalendar_display_author()) {
		$select_part .= ', wpu.display_name AS author_name ';
		$from_part   .= 'LEFT OUTER JOIN '.$wpdb->users.' wpu ON event_author=wpu.ID ';
	}
	if(rpbcalendar_display_category()) {
		$select_part .= ', rpbc.category_id AS category_id ';
		$from_part   .= 'LEFT OUTER JOIN '.RPBCALENDAR_CATEGORY_TABLE.' rpbc ON event_category=rpbc.category_id ';
	}
	$events = $wpdb->get_results($select_part.$from_part.
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
