<?php

	// Input
	$current_time  = rpbcalendar_time();
	$current_year  = isset($_GET['year' ]) ? $_GET['year' ] : date('Y', $current_time);
	$current_month = isset($_GET['month']) ? $_GET['month'] : date('n', $current_time);

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
	$first_day     = date('Y-m-d', mktime(0, 0, 0, $current_month, 1, $current_year));
	$last_day      = date('Y-m-d', mktime(0, 0, 0, $current_month, $days_in_month, $current_year));
	$first_day_sql = "'".mysql_escape_string($first_day)."'";
	$last_day_sql  = "'".mysql_escape_string($last_day )."'";

	// Highdays
	$highday_map = array_fill(1, $days_in_month, false);

	// Holidays
	$holidays = $wpdb->get_results('SELECT '.
		'DAY(CASE holiday_begin<'.$first_day_sql.' WHEN TRUE THEN '.$first_day_sql.' ELSE holiday_begin END) AS actual_begin, '.
		'DAY(CASE holiday_end  >'.$last_day_sql .' WHEN TRUE THEN '.$last_day_sql .' ELSE holiday_end   END) AS actual_end '.
		'FROM '.RPBCALENDAR_HOLIDAY_TABLE.' '.
		'WHERE holiday_end>='.$first_day_sql.' '.
		'AND holiday_begin<='.$last_day_sql.';'
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
		$current_day_sql = "'".mysql_escape_string($current_day)."'";
		$event_map[$k] = $wpdb->get_results($select_from_part.
			'WHERE event_begin<='.$current_day_sql.' AND event_end>='.$current_day_sql.' '.
			'ORDER BY event_time;'
		);
	}

	// Parameters for the navigation form
	$prev_month_params = array('month'=>$current_month-1, 'year'=>$current_year);
	$next_month_params = array('month'=>$current_month+1, 'year'=>$current_year);
	if($prev_month_params['month']==0) {
		$prev_month_params['month'] = 12;
		$prev_month_params['year' ] = $current_year-1;
	}
	if($next_month_params['month']==13) {
		$next_month_params['month'] = 1;
		$next_month_params['year' ] = $current_year+1;
	}
	$prev_year_params = array('month'=>$current_month, 'year'=>$current_year-1);
	$next_year_params = array('month'=>$current_month, 'year'=>$current_year+1);

	// Tooltip for the navigation form
	$prev_month_tooltip = date_i18n('F Y', mktime(0, 0, 0, $prev_month_params['month'], 1, $prev_month_params['year']));
	$next_month_tooltip = date_i18n('F Y', mktime(0, 0, 0, $next_month_params['month'], 1, $next_month_params['year']));
	$prev_year_tooltip  = date_i18n('F Y', mktime(0, 0, 0, $prev_year_params ['month'], 1, $prev_year_params ['year']));
	$next_year_tooltip  = date_i18n('F Y', mktime(0, 0, 0, $next_year_params ['month'], 1, $next_year_params ['year']));

?>

<div class="rpbcalendar-button-bar">
	<?php

		// Change month and year buttons
		rpbcalendar_navigate_form('prevyear' , $prev_year_params , '&lt;&lt;', $prev_year_tooltip );
		rpbcalendar_navigate_form('prevmonth', $prev_month_params, '&lt;'    , $prev_month_tooltip);
		rpbcalendar_navigate_form('nextmonth', $next_month_params, '&gt;'    , $next_month_tooltip);
		rpbcalendar_navigate_form('nextyear' , $next_year_params , '&gt;&gt;', $next_year_tooltip );

		// Change date form
		rpbcalendar_begin_navigate_form('changedate', array('month', 'year'));
		echo '<select name="month">';
		for($k=1; $k<12; $k++) {
			$label    = date_i18n('F', mktime(0, 0, 0, $k, 1, 2000));
			$selected = ($k==$current_month) ? ' selected="1"' : '';
			echo '<option value="'.$k.'"'.$selected.'>'.$label.'</option>';
		}
		echo '</select>';
		echo '<input name="year" value="'.$current_year.'" maxlength="4" />';
		rpbcalendar_end_navigate_form  (__('Go', 'rpbcalendar'));
	?>
</div>

<div class="rpbcalendar-very-large-content">
	<table class="rpbcalendar-table">
		<tbody>

			<!-- Month name -->
			<tr>
				<th colspan="7" class="rpbcalendar-month-header">
					<?php
						echo date_i18n('F Y', mktime(0, 0, 0, $current_month, 1, $current_year));
					?>
				</th>
			</tr>

			<!-- Week day names -->
			<tr>
				<?php
					for($k=0; $k<7; $k++) {
						$weekday      = ($k + $start_of_week) % 7;
						$weekday_name = rpbcalendar_weekday_info($weekday, 'name'   );
						$is_weekend   = rpbcalendar_weekday_info($weekday, 'weekend');
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
					$is_weekend = rpbcalendar_weekday_info($current_weekday, 'weekend');
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
