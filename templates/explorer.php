<?php

	// Do not print the table if no search was emitted
	if(isset($_GET['rpbfilter']) && strlen($_GET['rpbfilter'])!=0) {

		// Retrieve events
		global $wpdb;
		$event_filter = "'".mysql_escape_string($_GET['rpbfilter'])."'";
		$events       = $wpdb->get_results(
			'SELECT event_title, event_desc, event_begin, event_end, event_time, event_link '.
			'FROM '.RPBCALENDAR_EVENT_TABLE.' '.
			'WHERE event_title='.$event_filter.' '.
			'ORDER BY event_begin ASC, event_end ASC, event_time ASC;'
		);
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
			if(empty($events)) {
				echo '<tr><td colspan="4">'.__('No event found', 'rpbcalendar').'</td></tr>';
			} else {
				foreach($events as $event) {
					$link_begin = '';
					if($event->event_link) {
						$link_begin = '<a href="'.htmlspecialchars($event->event_link).'"';
						if(!rpbcalendar_is_internal_link($event->event_link)) {
							$link_begin .= ' target="_blank"';
						}
						$link_begin .= '>';
					}
					$link_end   = isset($event->event_link) ? '</a>' : '';
					$title      = htmlspecialchars($event->event_title);
					$desc       = rpbcalendar_format_event_desc($event->event_desc);
					$date_range = rpbcalendar_format_date_range($event->event_begin, $event->event_end);
					$time       = isset($event->event_time) ? date(get_option('time_format'), strtotime($event->event_time)) : '';
					echo '<tr>';
					echo '<td>'.$link_begin.htmlspecialchars($event->event_title).$link_end.'</td>';
					echo '<td>'.$desc.'</td>';
					echo '<td>'.$date_range.'</td>';
					echo '<td>'.$time.'</td>';
					echo '</tr>';
				}
			}
		?>
	</tbody>
</table>

<?php
	}

	// Search form
	rpbcalendar_begin_navigate_form('searchevent', array('rpbfilter'));
	$default_search_value = isset($_GET['rpbfilter']) ? htmlspecialchars($_GET['rpbfilter']) : '';
	echo __('Search for an event:', 'rpbcalendar').' ';
	echo '<input type="text" name="rpbfilter" value="'.$default_search_value.'" />';
	rpbcalendar_end_navigate_form(__('Search', 'rpbcalendar'));
	echo '<div id="rpbcalendar-after-searchevent-form"></div>';
?>
