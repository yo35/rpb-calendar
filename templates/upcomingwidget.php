<?php

	// First and last days
	$current_time      = rpbcalendar_time();
	$upcoming_range    = max($instance['upcoming_range'], 1);
	$show_today_events = ($instance['show_today_events']!=0);
	$first_day      = date('Y-m-d', $current_time + 86400);
	$last_day       = date('Y-m-d', $current_time + 86400*$upcoming_range);
	$sql_first_day  = "'".mysql_escape_string($first_day)."'";
	$sql_last_day   = "'".mysql_escape_string($last_day )."'";

	// Retrieve the events in the database
	global $wpdb;
	$select_from_part = rpbcalendar_select_events_base_sql(true);
	if($show_today_events) {
		$where_part = 'WHERE event_begin<='.$sql_last_day.' AND event_end>='.$sql_first_day.' ';
	}
	else {
		$where_part = 'WHERE event_begin<='.$sql_last_day.' AND event_begin>='.$sql_first_day.' ';
	}
	$order_part = 'ORDER BY event_begin, event_end;';
	$events = $wpdb->get_results($select_from_part . $where_part . $order_part);
	
	// Special case if no events within the given period of time
	if(empty($events)) {
		return;
	}

	// Display the title
	echo $args['before_widget'];
	if(!empty($instance['title'])) {
		echo $args['before_title'].htmlspecialchars($instance['title']).$args['after_title'];
	}

	// Display events grouped by period ranges
	$group_by_date_range = true;
	include(RPBCALENDAR_ABSPATH.'templates/events.php');

	// End of the widget
	echo $args['after_widget'];

?>
