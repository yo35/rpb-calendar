<?php

	global $wpdb;

	$events = $wpdb->get_results("
		SELECT *
		FROM ".WP_CALENDAR_TABLE."
		WHERE event_title='N2'
	;");

	// Options
	if(!isset($opts['time_format'])) {
		$opts['time_format'] = get_option('time_format');
	}
	if(!isset($opts['date_format'])) {
		$opts['date_format'] = get_option('date_format');
	}


?>

<table>
	<thead>
		<tr>
			<th scope="col"><?php _e('Event'      , 'rpbcalendar'); ?></th>
			<th scope="col"><?php _e('Description', 'rpbcalendar'); ?></th>
			<th scope="col"><?php _e('Date'       , 'rpbcalendar'); ?></th>
			<th scope="col"><?php _e('Time'       , 'rpbcalendar'); ?></th>
		</tr>
	</thead>
	<tbody>
		<?php
			foreach($events as $event) {
				echo '<tr>';
				echo '<td>'.htmlspecialchars($event->event_title).'</td>';
				echo '<td>'.rpbcalendar_format_event_desc($event->event_desc).'</td>';
				echo '<td>'.date($opts['date_format'], strtotime($event->event_begin)).'</td>';
				echo '<td>'.date($opts['time_format'], strtotime($event->event_time )).'</td>';
				echo '</tr>';
			}
		?>
	</tbody>
</table>
