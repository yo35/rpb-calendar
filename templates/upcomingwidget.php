<?php

	// First and last days
	$current_time   = rpbcalendar_time();
	$upcoming_range = max($instance['upcoming_range'], 1);
	$first_day      = date('Y-m-d', $current_time + 86400);
	$last_day       = date('Y-m-d', $current_time + 86400*$upcoming_range);
	$first_day_sql  = "'".mysql_escape_string($first_day)."'";
	$last_day_sql   = "'".mysql_escape_string($last_day )."'";

	// All type of event date range encountered within the given interval
	global $wpdb;
	$date_ranges = $wpdb->get_results(
		'SELECT DISTINCT event_begin, event_end FROM '.RPBCALENDAR_EVENT_TABLE.' '.
		'WHERE event_begin<='.$last_day_sql.' AND event_end>='.$first_day_sql.' '.
		'ORDER BY event_begin, event_end;'
	);

	// Special case if no events within the given period of time
	if(empty($date_ranges)) {
		return;
	}

	// Display the title
	echo $args['before_widget'];
	if(!empty($instance['title'])) {
		echo $args['before_title'].htmlspecialchars($instance['title']).$args['after_title'];
	}

	// Display events grouped by period ranges
	$select_from_part = rpbcalendar_select_events_base_sql();
	foreach($date_ranges as $date_range) {

		// Retrieve the corresponding events
		$current_begin_sql = "'".$date_range->event_begin."'";
		$current_end_sql   = "'".$date_range->event_end  ."'";
		$events = $wpdb->get_results($select_from_part.
			'WHERE event_begin='.$current_begin_sql.' AND event_end='.$current_end_sql.' '.
			'ORDER BY event_time;'
		);

		// Date range label
		if($date_range->event_begin==$date_range->event_end) {
			$date_range_label = date_i18n(get_option('date_format'), strtotime($date_range->event_begin));
		} else {
			$current_begin    = date_i18n(get_option('date_format'), strtotime($date_range->event_begin));
			$current_end      = date_i18n(get_option('date_format'), strtotime($date_range->event_end  ));
			$date_range_label = sprintf(__('From %1$s to %2$s', 'rpbcalendar'),
				$current_begin, $current_end);
		}

		// Display events
		echo '<div class="rpbcalendar-upcoming-period">';
		echo '<div class="rpbcalendar-upcoming-period-title">'.$date_range_label.'</div>';
		echo '<div class="rpbcalendar-upcoming-period-content">';
		include(RPBCALENDAR_ABSPATH.'templates/events.php');
		echo '</div></div>';
	}

	// End of the widget
	echo $args['after_widget'];

?>
