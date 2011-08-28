<?php

	// Validate the year
	if(!isset($current_year)) {
		rpbcalendar_error_message(__('No year defined', 'rpbcalendar'));
		return;
	} elseif(!is_numeric($current_year)) {
		rpbcalendar_error_message(__('The year must be a numeric value', 'rpbcalendar'));
		return;
	}
	$current_year = (int)$current_year;

	// Validate the month
	if(!isset($current_month)) {
		rpbcalendar_error_message(__('No month defined', 'rpbcalendar'));
		return;
	} elseif(!is_numeric($current_month)) {
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
	$event_map   = array_fill(1, $days_in_month, NULL);
	$select_part = 'SELECT event_title, event_desc, event_time, event_link ';
	$from_part   = 'FROM '.RPBCALENDAR_EVENT_TABLE.' ';
	if(rpbcalendar_display_author()) {
		$select_part .= ', wpu.display_name AS author_name ';
		$from_part   .= 'LEFT OUTER JOIN '.$wpdb->users.' wpu ON event_author=wpu.ID ';
	}
	if(rpbcalendar_display_category()) {
		$select_part .= ', rpbc.category_id AS category_id ';
		$from_part   .= 'LEFT OUTER JOIN '.RPBCALENDAR_CATEGORY_TABLE.' rpbc ON event_category=rpbc.category_id ';
	}
	for($k=1; $k<=$days_in_month; $k++) {
		$current_day     = date('Y-m-d', mktime(0, 0, 0, $current_month, $k, $current_year));
		$current_day_sql = "'".mysql_escape_string($current_day)."'";
		$event_map[$k] = $wpdb->get_results($select_part.$from_part.
			'WHERE event_begin<='.$current_day_sql.' AND event_end>='.$current_day_sql.' '.
			'ORDER BY event_time;'
		);
	}

	/*
	// Print a link to the previous month
	function previous_month_link($switch_date_link, $current_year, $current_month)
	{
		if(!isset($switch_date_link)) {
			return;
		}
		$target_year  = $current_year;
		$target_month = $current_month-1;
		if($target_month==0) {
			$target_year  = $target_year-1;
			$target_month = 12;
		}
		$target_link = sprintf($switch_date_link, $target_year, $target_month);
		?>
		<div class="rpbcalendar-previous-month">
			<a href="<?php echo htmlspecialchars($target_link); ?>">
				<? echo htmlspecialchars('« '.__('Previous', 'calendar')); ?>
			</a>
		</div>
		<?php
	}

	// Print a link to the next month
	function next_month_link($switch_date_link, $current_year, $current_month)
	{
		if(!isset($switch_date_link)) {
			return;
		}
		$target_year  = $current_year;
		$target_month = $current_month+1;
		if($target_month==13) {
			$target_year  = $target_year+1;
			$target_month = 1;
		}
		$target_link = sprintf($switch_date_link, $target_year, $target_month);
		?>
		<div class="rpbcalendar-next-month">
			<a href="<?php echo htmlspecialchars($target_link); ?>">
				<? echo htmlspecialchars(__('Next', 'calendar').' »'); ?>
			</a>
		</div>
		<?php
	}
	*/

?>

<div class="rpbcalendar-very-large-content">
	<table class="rpbcalendar-table">
		<tbody>

			<!-- Month name -->
			<tr>
				<th colspan="7" class="rpbcalendar-month-header">
					<?php
						//previous_month_link($switch_date_link, $current_year, $current_month);
						echo date_i18n('F Y', mktime(0, 0, 0, $current_month, 1, $current_year));
						//next_month_link($switch_date_link, $current_year, $current_month);
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
