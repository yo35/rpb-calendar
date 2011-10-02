<?php

	// Date range
	$current_time  = rpbcalendar_time();
	$current_year  = isset($_GET['rpbyear' ]) ? $_GET['rpbyear' ] : date('Y', $current_time);
	$current_month = isset($_GET['rpbmonth']) ? $_GET['rpbmonth'] : date('n', $current_time);
	$next_year     = $current_year;
	$next_month    = $current_month + 1;
	if($next_month>12) {
		$next_year++;
		$next_month = 1;
	}
?>

<div id="rpbcalendar-printer-form">
	<form name="printer" method="get" action="<?php echo RPBCALENDAR_URL.'print.php'; ?>">
		<?php
			if(isset($atts['title'])) {
				echo '<input type="hidden" name="title" value="'.htmlspecialchars($atts['title']).'" />';
			}
			echo '<span>'.__('From', 'rpbcalendar').'</span>';
			echo '<select name="firstmonth">';
			for($k=1; $k<=12; $k++) {
				$label    = rpbcalendar_month_info('name', $k);
				$selected = ($k==$current_month) ? ' selected="1"' : '';
				echo '<option value="'.$k.'"'.$selected.'>'.$label.'</option>';
			}
			echo '</select>';
			echo '<input type="text" name="firstyear" value="'.$current_year.'" maxlength="4" />';
			echo '<span>'.__('to', 'rpbcalendar').'</span>';
			echo '<select name="lastmonth">';
			for($k=1; $k<=12; $k++) {
				$label    = rpbcalendar_month_info('name', $k);
				$selected = ($k==$next_month) ? ' selected="1"' : '';
				echo '<option value="'.$k.'"'.$selected.'>'.$label.'</option>';
			}
			echo '</select>';
			echo '<input type="text" name="lastyear" value="'.$next_year.'" maxlength="4" />';
			echo '<input type="submit" value="'.__('Print', 'rpbcalendar').'" title="'.
				__('Generate the PDF for printing', 'rpbcalendar').'" />';
		?>
	</form>
</div>
<div id="rpbcalendar-after-printer-form"></div>
