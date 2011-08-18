<?php

	/*
	 * Variables used by the template
	 *  - $current_year
	 *  - $current_month
	 *  - $opts
	 *  - $current_highdays
	 *  - $current_holidays
	 *  - $event_map (to be changed)
	 */

	// Utilities
	require_once(RPBCALENDAR_ABSPATH.'tools.php');

	// Validate the year
	if(!(isset($current_year) && $current_year>=1000 && $current_year<=3000)) {
		if(isset($current_year))
			rpbcalendar_error_message(sprintf(
				__('The year must be valued between 1000 and 3000 (current value: %s)', 'calendar'),
				(string)$current_year
			));
		else
			rpbcalendar_error_message(__('No year defined', 'calendar'));
		return;
	}
	$current_year = (int)$current_year;

	// Validate the month
	if(!(isset($current_month) && $current_month>=1 && $current_month<=12)) {
		if(isset($current_month))
			rpbcalendar_error_message(sprintf(
				__('The month must be valued between 1 and 12 (current value: %s)', 'calendar'),
				(string)$current_month
			));
		else
			rpbcalendar_error_message(__('No month defined', 'calendar'));
		return;
	}
	$current_month = (int)$current_month;

	// Basic information
	$days_in_month = (int)(date("t", mktime(0, 0, 0, $current_month, 1, $current_year)));
	$first_weekday = (int)(date("w", mktime(0, 0, 0, $current_month, 1, $current_year)));

	// First day of a week
	$start_of_week = 0;
	if(isset($opts['start_of_week']) && $opts['start_of_week']>=0 && $opts['start_of_week']<7) {
		$start_of_week = (int)$opts['start_of_week'];
	}

	// Highdays
	$highday_map = array_fill(1, $days_in_month, false);
	if(isset($current_highdays) && is_array($current_highdays)) {
		foreach($current_highdays as $highday) {
			$highday_map[$highday] = true;
		}
	}

	// Highdays
	$holiday_map = array_fill(1, $days_in_month, false);
	if(isset($current_holidays) && is_array($current_holidays)) {
		foreach($current_holidays as $holyday) {
			$holiday_map[$holyday] = true;
		}
	}



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

	// Append an empty cell
	function push_pĥantom_cell($column)
	{
		// Begin of line
		if($column==0) {
			?><tr><?php
		}

		// Actual cell
		?><td class="rpbcalendar-phantom-cell">&nbsp;</td><?php

		// End of line
		if($column==6) {
			?></tr><?php
		}
	}

	// Append a regular cell
	function push_regular_cell($column, $day, $is_weekend, $is_highday, $is_holiday, $events, $opts)
	{
		// Begin of line
		if($column==0) {
			?><tr><?php
		}

		// Actual cell
		?><td class="<?php
			echo ($is_weekend ? 'rpbcalendar-weekend-day-cell' : 'rpbcalendar-normal-day-cell');
			if($is_highday) {
				echo ' rpbcalendar-highday';
			}
		?>">
			<div class="rpbcalendar-holiday-bar<?php
				if($is_holiday) {
					echo ' rpbcalendar-holiday';
				}
			?>">
				<div class="rpbcalendar-day-label"><?php echo $day; ?></div>
			</div>
			<div class="rpbcalendar-cell-content"><?php
				 include('theevents.php');
			?></div>
		</td>
		<?php

		// End of line
		if($column==6) {
			?></tr><?php
		}
	}

?>

<div class="very-large-content">
	<table class="rpbcalendar-table">
		<tbody>

			<!-- Month name -->
			<tr>
				<th colspan="7" class="rpbcalendar-month-header">
					<?php
						previous_month_link($switch_date_link, $current_year, $current_month);
						echo htmlspecialchars(rpbcalendar_month_info($current_month, 'name').' '.$current_year);
						next_month_link($switch_date_link, $current_year, $current_month);
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
				$current_column  = $first_weekday - $start_of_week;
				$current_weekday = $first_weekday;
				if($current_column < 0) {
					$current_column += 7;
				}
			?>
			<?php
				for($k=0; $k<$current_column; $k++) {
					push_pĥantom_cell($k);
				}
				for($k=1; $k<=$days_in_month; $k++) {
					if($current_column==7) {
						$current_column = 0;
					}
					$is_weekend = rpbcalendar_weekday_info($current_weekday, 'weekend');
					$is_highday = $highday_map[$k];
					$is_holiday = $holiday_map[$k];
					$events     = $event_map  [$k];
					push_regular_cell($current_column, $k, $is_weekend, $is_highday, $is_holiday, $events, $opts);
					$current_column++;
					$current_weekday++;
					if($current_weekday==7) {
						$current_weekday = 0;
					}
				}
				for($k=$current_column; $k<7; $k++) {
					push_pĥantom_cell($k);
				}
			?>

		</tbody>
	</table>
</div>
