<?php

require(RPBCALENDAR_ABSPATH.'tcpdf/tcpdf.php');

class RpbCalendarPDF extends TCPDF
{
	// Constants
	private $text_width;
	private $cell_width;
	private $margin_left   =  5;
	private $margin_right  =  5;
	private $margin_top    =  8;
	private $margin_bottom = 10;
	private $normal_font_size = 8;
	private $small_font_size  = 6;
	private $cell_height        = 18;
	private $holiday_bar_height =  1.7;
	private $day_label_width    =  5  ;
	private $separator_height   =  7  ;

	// Control
	private $table_on_page_count;

	// Constructor
	function __construct()
	{
		parent::__construct();

		// Margins
		$this->SetMargins($this->margin_left, $this->margin_top, $this->margin_right);
		$this->SetAutoPageBreak(false, $this->margin_bottom);
		$this->text_width = $this->getPageWidth() - $this->margin_left - $this->margin_right;

		// Initialization
		$this->table_on_page_count = 2;
		$this->SetFont('helvetica', '', $this->normal_font_size);

		$this->SetLineWidth(0.1);
	}

	// Function used to print a month table
	function PrintMonthTable($current_month, $current_year)
	{
		// Validate the year
		if(!is_numeric($current_year)) {
			$this->Error(__('The year must be a numeric value', 'rpbcalendar'));
			return;
		}
		$current_year = (int)$current_year;

		// Validate the month
		if(!is_numeric($current_month)) {
			$this->Error(__('The month must be a numeric value', 'rpbcalendar'));
			return;
		}
		$current_month = (int)$current_month;
		if(!($current_month>=1 && $current_month<=12)) {
			$this->Error(__('The month must be valued between 1 and 12', 'rpbcalendar'));
			return;
		}

		// Deal with multiple tables in the same document
		if($this->table_on_page_count>=2) {
			$this->AddPage();
			$this->table_on_page_count = 0;
		} else {
			$this->Ln($this->separator_height);
		}
		$this->table_on_page_count++;

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

		// Wordpress DB
		global $wpdb;

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

		// Formating headers
		$this->SetFont('', 'B', $this->normal_font_size);
		$this->SetDrawColor(0);
		$this->SetFillColor(0);
		$this->SetTextColor(255);

		// Month header
		$month_header_text = rpbcalendar_month_info('name', $current_month).' '.$current_year;
		$this->Cell($this->text_width, 0, ucwords($month_header_text), 1, 1, 'C', true);

		// Weekday headers
		$this->cell_width  = array_fill(0, 7, 0);
		$normal_day_width  = $this->text_width * 3 / 25;
		$weekend_day_width = $this->text_width * 5 / 25;
		for($k=0; $k<7; $k++) {
			$weekday       = ($k + $start_of_week) % 7;
			$weekday_name  = rpbcalendar_weekday_info('name'   , $weekday);
			$is_weekend    = rpbcalendar_weekday_info('weekend', $weekday);
			$current_width = $is_weekend ? $weekend_day_width : $normal_day_width;
			$this->Cell($current_width, 0, ucwords($weekday_name), 1, 0, 'C', true);
			$this->cell_width[$k] = $current_width;
		}
		$this->Ln();

		// Start of table body
		$first_column    = $first_weekday - $start_of_week;
		$current_weekday = $first_weekday;
		if($first_column < 0) {
			$first_column += 7;
		}
		for($current_column=0; $current_column<$first_column; $current_column++) {
			$this->PushPhantomCell($current_column);
		}

		// Actual table body
		for($current_day=1; $current_day<=$days_in_month; $current_day++) {
			if($current_column==7) {
				$current_column = 0;
				$this->Ln();
			}
			$is_weekend = rpbcalendar_weekday_info('weekend', $current_weekday);
			$is_highday = $highday_map[$current_day];
			$is_holiday = $holiday_map[$current_day];
			$events     = array(); //$event_map  [$current_day];
			$this->PushRegularCell($current_column, $current_day, $is_highday, $is_holiday, $is_weekend, $events);
			$current_column++;
			$current_weekday++;
			if($current_weekday==7) {
				$current_weekday = 0;
			}
		}

		// End of table body
		for( ; $current_column<7; $current_column++) {
			$this->PushPhantomCell($current_column);
		}
		$this->Ln();
	}

	// Append a phantom cell to the current table
	private function PushPhantomCell($current_column)
	{
		$this->SetFillColor(128);
		$this->Cell($this->cell_width[$current_column], $this->cell_height, '', 1, 0, '', true);
	}

	// Append a regular day cell to the current table
	private function PushRegularCell($current_column, $current_day, $is_highday, $is_holiday, $is_weekend, $events)
	{
		// Origin
		$x = $this->GetX();
		$y = $this->GetY();

		// Background
		if($is_highday || $is_weekend)
			$this->SetFillColor(244, 232, 210);
		else
			$this->SetFillColor(255);
		$this->Cell($this->cell_width[$current_column], $this->cell_height, '', 0, 0, '', true);

		// Holiday bar
		if($is_holiday) {
			$this->SetFillColor(96, 208, 32);
			$this->Rect($x, $y, $this->cell_width[$current_column], $this->holiday_bar_height, 'F');
		}

		// Label
		$this->SetFont('', 'B', $this->small_font_size);
		if($is_highday || $is_weekend) {
			$this->SetFillColor(0);
			$this->SetTextColor(244, 232, 210);
		} else {
			$this->SetFillColor(255);
			$this->SetTextColor(0);
		}
		$this->AbsoluteCell($x, $y, $this->day_label_width, 0, $current_day, 'BR', 'C', true);

		// Border
		$this->SetXY($x, $y);
		$this->Cell($this->cell_width[$current_column], $this->cell_height, '', 1, 0, '', false);
	}

	// Append an event to the current cell
	private function PushEvent($event)
	{

	}

	// Append a cell with absolute positionning
	protected function AbsoluteCell($x, $y, $w, $h, $txt, $border=0, $align='', $fill=false, $link=null)
	{
		$old_x = $this->GetX();
		$old_y = $this->GetY();
		$this->SetXY($x, $y);
		$this->Cell($w, $h, $txt, $border, 0, $align, $fill, $link);
		$this->SetXY($old_x, $old_y);
	}
}

?>
