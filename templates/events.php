<?php

	// Checks
	if(!isset($events)) {
		rpbcalendar_error_message(
			__('Unable to print the list of events', 'rpbcalendar')
		);
		return;
	}

	// Displaying events
	foreach($events as $event) {


		////////////////////////////////////////////////////////////////////////////
		// Extrating and formatting event data

		// Title (always defined)
		$title = htmlspecialchars($event->event_title);

		// Description (may be empty)
		$description = rpbcalendar_format_event_desc($event->event_desc);

		// Event link (always defined)
		$href = '';
		if(isset($event->event_link) && !empty($event->event_link)) {
			$href = 'href="'.htmlspecialchars($event->event_link).'"';
			if(!rpbcalendar_is_internal_link($event->event_link)) {
				$href .= ' target="_blank"';
			}
		}

		// Author (always defined if the corresponding option is set to 'true')
		if(rpbcalendar_display_author()) {
			$author = isset($event->author_name) ? htmlspecialchars($event->author_name) : 'N/A';
		}

		// Time (may be empty)
		$time_string = '';
		if(isset($event->event_time) && !empty($event->event_time)) {
			$time_string = __(' at ', 'rpbcalendar') . date_i18n(get_option('time_format'),
				strtotime($event->event_time));
		}

		// Category (always defined)
		$category_class = 'rpbcalendar-default-category';
		if(rpbcalendar_display_category() && isset($event->category_id)) {
			$category_class = 'rpbcalendar-category-'.htmlspecialchars($event->category_id);
		}


		////////////////////////////////////////////////////////////////////////////
		// Actual printing

		// Begin of event
		echo '<a '.$href.' class="rpbcalendar-event">';

		// Tooltip
		echo '<div class="rpbcalendar-event-tooltip '.$category_class.'">';
		echo '<div class="rpbcalendar-event-title">'.$title;
		if(!empty($time_string)) {
			echo '<span class="rpbcalendar-event-time">'.$time_string.'</span>';
		}
		echo '</div>';
		$rule_printed = false;
		if(rpbcalendar_display_author()) {
			if(!$rule_printed) { echo '<hr/>'; $rule_printed=true; }
			echo '<div class="rpbcalendar-event-author">'.$author.'</div>';
		}
		if(!empty($description)) {
			if(!$rule_printed) { echo '<hr/>'; $rule_printed=true; }
			echo '<div class="rpbcalendar-event-desc">'.$description.'</div>';
		}
		echo '</div>';

		// Event summary
		echo '<div class="rpbcalendar-event-summary '.$category_class.'">';
		echo '<div class="rpbcalendar-event-title">'.$title;
		if(!empty($time_string)) {
			echo '<span class="rpbcalendar-event-time">'.$time_string.'</span>';
		}
		echo '</div>';
		if(!empty($description)) {
			echo '<div class="rpbcalendar-event-desc">'.$description.'</div>';
		}
		echo '</div>';

		// End of event
		echo '</a>';
	}

?>
