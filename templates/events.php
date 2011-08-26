<?php

	// Checks
	if(!isset($event)) {
		rpbcalendar_error_message(
			__('Unable to print the requested event', 'rpbcalendar');
		);
		return;
	}

	// Options
	if(!isset($opts['time_format'])) {
		$opts['time_format'] = get_option('time_format');
	}



	// Data
	//if($opts['show_category']) {
	//
	//	// TODO: change that, use JOIN
	//	global $wpdb;
	//	$sql = "SELECT * FROM " . WP_CALENDAR_CATEGORIES_TABLE .
	//		" WHERE category_id=".mysql_escape_string($event->event_category);
	//	$cat_details = $wpdb->get_row($sql);
	//	$style = ' style="background-color: '.stripslashes($cat_details->category_colour).';"';
	//	echo $style;
	//	// END TODO
	//}

	// TODO: change that, use lookup or JOIN
	$e      = get_userdata(stripslashes($event->event_author));
	$author = $e->display_name;
	// END TODO

	// Title (always defined)
	$title = htmlspecialchars($event->event_title);

	// Description (may be empty)
	$description = rpbcalendar_format_event_desc($event->event_desc);

	// Event link (may be empty)
	$link =
	$href = '';
	if(!empty($event->event_link)) {
		$href = 'href="'.htmlspecialchars($event->event_link).'"';
		if(true) {
			$href .= '" target="_blank"';
		}
	}

	// Author (may be empty)
	$author_string = $opts['show_author'] ?
		htmlspecialchars(__('Posted by', 'calendar').' '.$author) :
		'';

	// Time (may be empty)
	$time_string = $event->event_time=="00:00:00" ?
		'' :
		htmlspecialchars(' '.__('at', 'calendar').' '.date($opts['time_format'], strtotime($event->event_time)));

	// Category (always defined)
	$category_class = $opts['show_category'] ?
		'rpbcalendar-category-'.htmlspecialchars($event->event_category) :
		'rpbcalendar-default-category';
?>

<a <?php echo $href; ?> class="rpbcalendar-event">

	<!-- Tooltip -->
	<div class="rpbcalendar-event-tooltip <? echo $category_class; ?>">
		<?php
			echo '<div class="rpbcalendar-event-title">'.$title;
			if(!empty($time_string)) {
				echo '<span class="rpbcalendar-event-time">'.$time_string.'</span>';
			}
			echo '</div>';
			$rule_printed = false;
			if($opts['show_author']) {
				if(!$rule_printed) { echo '<hr/>'; $rule_printed=true; }
				echo '<div class="rpbcalendar-event-author">'.$author_string.'</div>';
			}
			if(!empty($description)) {
				if(!$rule_printed) { echo '<hr/>'; $rule_printed=true; }
				echo '<div class="rpbcalendar-event-desc">'.$description.'</div>';
			}
		?>
	</div>

	<!-- Event summary -->
	<div class="rpbcalendar-event-summary <? echo $category_class; ?>">
		<?php
			echo '<div class="rpbcalendar-event-title">'.$title;
			if(!empty($time_string)) {
				echo '<span class="rpbcalendar-event-time">'.$time_string.'</span>';
			}
			echo '</div>';
			if(!empty($description)) {
				echo '<div class="rpbcalendar-event-desc">'.$description.'</div>';
			}
		?>
	</div>

</a>
