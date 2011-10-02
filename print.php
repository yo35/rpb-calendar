<?php

// Load wordpress
require_once(dirname(__FILE__).'/../../../wp-load.php');

// Load rpbcalendar
require_once(dirname(__FILE__).'/config.php');

// PDF generator class
require(RPBCALENDAR_ABSPATH.'rpbcalendarpdf.class.php');
$pdf = new RpbCalendarPDF();

// Range
$current_time = rpbcalendar_time();
$first_month  = isset($_GET['firstmonth']) ? $_GET['firstmonth'] : date('n', $current_time);
$first_year   = isset($_GET['firstyear' ]) ? $_GET['firstyear' ] : date('Y', $current_time);
$last_month   = isset($_GET['lastmonth' ]) ? $_GET['lastmonth' ] : date('n', $current_time);
$last_year    = isset($_GET['lastyear'  ]) ? $_GET['lastyear'  ] : date('Y', $current_time);
$total_months = ($last_year-$first_year)*12 + ($last_month-$first_month) + 1;
if($total_months>12) {
	$pdf->Error(__('Cannot print more than 12 months in the same PDF', 'rpbcalendar'));
} elseif($total_months<=0) {
	$pdf->Error(__('The stop date must be greater than the start date', 'rpbcalendar'));
}
$pdf->total_number_of_tables = $total_months;

// Title
if(isset($_GET['title'])) {
	$pdf->title = $_GET['title'];
} else {
	$pdf->title = __('Calendar', 'rpbcalendar');
}

// Subtitle
$start_label = rpbcalendar_month_info('name', $first_month);
if($first_year!=$last_year) {
	$start_label .= ' '.$first_year;
}
$stop_label = rpbcalendar_month_info('name', $last_month).' '.$last_year;
$pdf->subtitle = sprintf(__('From %1$s to %2$s', 'rpbcalendar'), $start_label, $stop_label);

// Generates the PDF
$current_month = $first_month;
$current_year  = $first_year ;
while($current_year<$last_year || ($current_year==$last_year && $current_month<=$last_month)) {
	$pdf->PrintMonthTable($current_month, $current_year);
	$current_month++;
	if($current_month>12) {
		$current_month = 1;
		$current_year++;
	}
}
$pdf->Output();

?>
