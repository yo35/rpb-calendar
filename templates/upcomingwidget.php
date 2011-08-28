<?php

	// First and last days
	$current_time   = rpbcalendar_time();
	$upcoming_range = max((int)get_option('rbpcalendar_upcoming_range', 7), 1);
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
	$widget_title = get_option('rpbcalendar_upcoming_widget_title', __('Upcoming events', 'rpbcalendar'));
	echo $args['before_widget'];
	echo $args['before_title'].htmlspecialchars($widget_title).$args['after_title'];
	echo '<ul>';

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
		echo '<li>'.$date_range_label;
		include(RPBCALENDAR_ABSPATH.'templates/events.php');
		echo '</li>';
	}

	// End of the widget
	echo '</ul>';
	echo $args['after_widget'];

?>
