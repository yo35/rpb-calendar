<?php

	// Input
	$current_time  = rpbcalendar_time();
	$current_year  = isset($_GET['rpbyear' ]) ? $_GET['rpbyear' ] : date('Y', $current_time);
	$current_month = isset($_GET['rpbmonth']) ? $_GET['rpbmonth'] : date('n', $current_time);

	// Validate the year
	if(!is_numeric($current_year)) {
		rpbcalendar_error_message(__('The year must be a numeric value', 'rpbcalendar'));
		return;
	}
	$current_year = (int)$current_year;

	// Validate the month
	if(!is_numeric($current_month)) {
		rpbcalendar_error_message(__('The month must be a numeric value', 'rpbcalendar'));
		return;
	}
	$current_month = (int)$current_month;
	if(!($current_month>=1 && $current_month<=12)) {
		rpbcalendar_error_message(__('The month must be valued between 1 and 12', 'rpbcalendar'));
		return;
	}

	// Wordpress DB
	global $wpdb;

	// Basic information
	$days_in_month = (int)(date("t", mktime(0, 0, 0, $current_month, 1, $current_year)));
	$first_weekday = (int)(date("w", mktime(0, 0, 0, $current_month, 1, $current_year)));
	$start_of_week = (int)get_option('start_of_week');
	$first_day     = mktime(0, 0, 0, $current_month, 1, $current_year);
	$last_day      = mktime(0, 0, 0, $current_month, $days_in_month, $current_year);
	$easter_day    = rpbcalendar_easter_date($current_year);
	$rel_first_day = ($first_day-$easter_day) / 86400;
	$rel_last_day  = ($last_day -$easter_day) / 86400;
	$sql_first_day = "'".mysql_escape_string(date('Y-m-d', $first_day))."'";
	$sql_last_day  = "'".mysql_escape_string(date('Y-m-d', $last_day ))."'";

	// Highdays
	$highdays = $wpdb->get_col('SELECT '.
		'CASE highday_month '.
			'WHEN 13 THEN highday_day'.($rel_first_day>=0 ? '-' : '+').abs($rel_first_day).'+1 '.
			'ELSE highday_day '.
		'END '.
		'FROM '.RPBCALENDAR_HIGHDAY_TABLE.' '.
		'WHERE highday_month='.$current_month.' '.
		'OR (highday_month=13 AND highday_day>='.$rel_first_day.' AND highday_day<='.$rel_last_day.');'
	);
	$highday_map = array_fill(1, $days_in_month, false);
	foreach($highdays as $highday) {
		$highday_map[$highday] = true;
	}

	// Holidays
	$holidays = $wpdb->get_results('SELECT '.
		'DAY(CASE holiday_begin<'.$sql_first_day.' WHEN TRUE THEN '.$sql_first_day.' ELSE holiday_begin END) AS actual_begin, '.
		'DAY(CASE holiday_end  >'.$sql_last_day .' WHEN TRUE THEN '.$sql_last_day .' ELSE holiday_end   END) AS actual_end '.
		'FROM '.RPBCALENDAR_HOLIDAY_TABLE.' '.
		'WHERE holiday_end>='.$sql_first_day.' '.
		'AND holiday_begin<='.$sql_last_day.';'
	);
	$holiday_map = array_fill(1, $days_in_month, false);
	foreach($holidays as $holiday) {
		$actual_begin = (int)$holiday->actual_begin;
		$actual_end   = (int)$holiday->actual_end  ;
		foreach(range($actual_begin, $actual_end) as $k) {
			$holiday_map[$k] = true;
		}
	}

	// Events
	$event_map        = array_fill(1, $days_in_month, NULL);
	$select_from_part = rpbcalendar_select_events_base_sql();
	for($k=1; $k<=$days_in_month; $k++) {
		$current_day     = date('Y-m-d', mktime(0, 0, 0, $current_month, $k, $current_year));
		$sql_current_day = "'".mysql_escape_string($current_day)."'";
		$event_map[$k] = $wpdb->get_results($select_from_part.
			'WHERE event_begin<='.$sql_current_day.' AND event_end>='.$sql_current_day.' '.
			'ORDER BY event_title;'
		);
	}

	// Parameters for the navigation form
	$prev2_params = array('rpbmonth'=>$current_month-3, 'rpbyear'=>$current_year);
	$prev1_params = array('rpbmonth'=>$current_month-1, 'rpbyear'=>$current_year);
	$next1_params = array('rpbmonth'=>$current_month+1, 'rpbyear'=>$current_year);
	$next2_params = array('rpbmonth'=>$current_month+3, 'rpbyear'=>$current_year);
	if($prev2_params['rpbmonth']<=0) {
		$prev2_params['rpbmonth'] += 12;
		$prev2_params['rpbyear' ]--;
	}
	if($prev1_params['rpbmonth']<=0) {
		$prev1_params['rpbmonth'] += 12;
		$prev1_params['rpbyear' ]--;
	}
	if($next1_params['rpbmonth']>12) {
		$next1_params['rpbmonth'] -= 12;
		$next1_params['rpbyear' ]++;
	}
	if($next2_params['rpbmonth']>12) {
		$next2_params['rpbmonth'] -= 12;
		$next2_params['rpbyear' ]++;
	}
	$today_params = array('rpbmonth'=>date('n', $current_time), 'rpbyear'=>date('Y', $current_time));

	// Tooltip for the navigation form
	$prev2_tooltip = rpbcalendar_month_info('name', $prev2_params['rpbmonth']).' '.$prev2_params['rpbyear'];
	$prev1_tooltip = rpbcalendar_month_info('name', $prev1_params['rpbmonth']).' '.$prev1_params['rpbyear'];
	$today_tooltip = rpbcalendar_month_info('name', $today_params['rpbmonth']).' '.$today_params['rpbyear'];
	$next1_tooltip = rpbcalendar_month_info('name', $next1_params['rpbmonth']).' '.$next1_params['rpbyear'];
	$next2_tooltip = rpbcalendar_month_info('name', $next2_params['rpbmonth']).' '.$next2_params['rpbyear'];

?>

<div id="rpbcalendar-navigation-bar">
	<?php

		// Change month and year buttons
		$today_label = __('Today', 'rpbcalendar');
		rpbcalendar_navigate_form('prev3month', $prev2_params, '&lt;&lt;'  , $prev2_tooltip);
		rpbcalendar_navigate_form('prevmonth' , $prev1_params, '&lt;'      , $prev1_tooltip);
		rpbcalendar_navigate_form('nextmonth' , $today_params, $today_label, $today_tooltip);
		rpbcalendar_navigate_form('nextmonth' , $next1_params, '&gt;'      , $next1_tooltip);
		rpbcalendar_navigate_form('next3month', $next2_params, '&gt;&gt;'  , $next2_tooltip);

		// Change date form
		rpbcalendar_begin_navigate_form('changedate', array('rpbmonth', 'rpbyear'));
		echo '<select name="rpbmonth">';
		for($k=1; $k<=12; $k++) {
			$label    = rpbcalendar_month_info('name', $k);
			$selected = ($k==$current_month) ? ' selected="1"' : '';
			echo '<option value="'.$k.'"'.$selected.'>'.$label.'</option>';
		}
		echo '</select>';
		echo '<input type="text" name="rpbyear" value="'.$current_year.'" maxlength="4" />';
		rpbcalendar_end_navigate_form  (__('Go', 'rpbcalendar'));
	?>
</div>

<div id="rpbcalendar-calendar">
	<table>
		<tbody>

			<!-- Month name -->
			<tr>
				<th colspan="7" class="rpbcalendar-month-header">
					<?php
						echo rpbcalendar_month_info('name', $current_month).' '.$current_year;
					?>
				</th>
			</tr>

			<!-- Week day names -->
			<tr>
				<?php
					for($k=0; $k<7; $k++) {
						$weekday      = ($k + $start_of_week) % 7;
						$weekday_name = rpbcalendar_weekday_info('name'   , $weekday);
						$is_weekend   = rpbcalendar_weekday_info('weekend', $weekday);
						echo '<th class="rpbcalendar-'.($is_weekend ? 'weekend' : 'normal').'-day-header">';
						echo htmlspecialchars($weekday_name);
						echo '</th>';
					}
				?>
			</tr>

			<!-- Table body -->
			<?php
				$first_column    = $first_weekday - $start_of_week;
				$current_weekday = $first_weekday;
				if($first_column < 0) {
					$first_column += 7;
				}
			?>
			<?php
				for($current_column=0; $current_column<$first_column; $current_column++) {
					include(RPBCALENDAR_ABSPATH.'templates/phantomcell.php');
				}
				for($current_day=1; $current_day<=$days_in_month; $current_day++) {
					if($current_column==7) {
						$current_column = 0;
					}
					$is_weekend = rpbcalendar_weekday_info('weekend', $current_weekday);
					$is_highday = $highday_map[$current_day];
					$is_holiday = $holiday_map[$current_day];
					$events     = $event_map  [$current_day];
					include(RPBCALENDAR_ABSPATH.'templates/regularcell.php');
					$current_column++;
					$current_weekday++;
					if($current_weekday==7) {
						$current_weekday = 0;
					}
				}
				for( ; $current_column<7; $current_column++) {
					include(RPBCALENDAR_ABSPATH.'templates/phantomcell.php');
				}
			?>

		</tbody>
	</table>
</div>
