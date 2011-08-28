<?php

	// Begin of line
	if($current_column==0) {
		echo '<tr>';
	}

	// Actual cell
	$td_class = $is_weekend ? 'rpbcalendar-weekend-day-cell' : 'rpbcalendar-normal-day-cell';
	if($is_highday) {
		$td_class .= ' rpbcalendar-highday';
	}
	if($is_holiday) {
		$td_class .= ' rpbcalendar-holiday';
	}
	echo '<td class="'.$td_class.'">';
	echo '<div class="rpbcalendar-cell-bar"><div class="rpbcalendar-day-label">'.$current_day.'</div></div>';
	echo '<div class="rpbcalendar-cell-content">';
	include(RPBCALENDAR_ABSPATH.'templates/events.php');
	echo '</div>';
	echo '</td>';

	// End of line
	if($current_column==6) {
		echo '</tr>';
	}

?>
