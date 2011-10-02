<?php

require(RPBCALENDAR_ABSPATH.'tcpdf/tcpdf.php');

class RpbCalendarPDF extends TCPDF
{
	// Header and footer parameters
	private $title_margin_top     = 3;
	private $title_margin_bottom  = 1;
	public  $footer_margin_bottom = 6;
	private $title_font_size    = 14;
	private $subtitle_font_size = 10;
	private $footer_font_size   = 8 ;
	public  $title    = null;
	public  $subtitle = null;

	// Constants
	private $text_width;
	private $margin_left   =  5;
	private $margin_right  =  5;
	private $margin_top    =  4;
	private $margin_bottom = 10;
	private $normal_font_size = 8;
	private $small_font_size  = 6;
	private $minimum_cell_height = 18  ;
	private $holiday_bar_height  =  1.7;
	private $day_label_width     =  5  ;
	private $separator_height    =  7  ;
	private $event_margin_lr = 0.6;
	private $event_margin_tb = 0.5;

	// Misc
	private $table_on_page_count;
	private $creation_date_string;
	public  $total_number_of_tables = 0;

	// Current table parameters
	private $year         ;
	private $month        ;
	private $days_in_month;
	private $first_weekday;
	private $start_of_week;
	private $first_day    ;
	private $last_day     ;
	private $easter_day   ;
	private $rel_first_day;
	private $rel_last_day ;
	private $sql_first_day;
	private $sql_last_day ;

	// Data retrieved from the database
	private $highday_map;
	private $holiday_map;
	private $event_map  ;

	// Temporary flags
	private $cell_width        ;
	private $weekend_cell      ;
	private $first_pass_row    ;
	private $current_row_height;
	private $lookup_fill_color ;
	private $lookup_text_color ;

	// Constructor
	function __construct()
	{
		parent::__construct();

		// Margins
		$this->SetMargins($this->margin_left, $this->margin_top, $this->margin_right);
		$this->SetAutoPageBreak(false, $this->margin_bottom);
		$this->text_width = $this->getPageWidth() - $this->margin_left - $this->margin_right;

		// Initialization
		$this->table_on_page_count  = 2;
		$this->creation_date_string = date_i18n(get_option('date_format'), rpbcalendar_time());
		$this->SetFont('helvetica', '', $this->normal_font_size);
		$this->SetLineWidth(0.1);
		$this->lookup_fill_color = array();
		$this->lookup_text_color = array();
	}

	// Header
	function Header()
	{
		if(isset($this->title)) {
			$this->SetFont('helvetica', 'B', $this->title_font_size);
			$this->Ln($this->title_margin_top);
			$this->Cell(0, 0, $this->title, 0, 1, 'C');
			if(isset($this->subtitle)) {
				$this->Ln($this->title_margin_bottom);
				$this->SetFont('', 'I', $this->subtitle_font_size);
				$this->Cell(0, 0, $this->subtitle, 0, 1, 'C');
			}
		}
		$this->SetTopMargin($this->GetY() + $this->margin_top);
	}

	// Footer
	function Footer()
	{
		$this->setXY($this->margin_left, 0);
		$this->SetFont('helvetica', 'I', $this->footer_font_size);
		$this->startTransaction();
		$this->Cell(0, 0, 'Test cell', 0, 1);
		$footer_height = $this->GetY();
		$this->rollbackTransaction(true);
		$this->SetY($this->getPageHeight() - $this->footer_margin_bottom - $footer_height);
		$text_left  = sprintf(__('Generated from %s on %s', 'rpbcalendar'), site_url(), $this->creation_date_string);
		$text_right = sprintf(__('Page %d on %d', 'rpbcalendar'), $this->PageNo(), ceil($this->total_number_of_tables / 2));
		$this->Cell($this->text_width/2, 0, $text_left , 0, 0, 'L');
		$this->Cell($this->text_width/2, 0, $text_right, 0, 0, 'R');
	}

	// Function used to print a month table
	function PrintMonthTable($current_month, $current_year)
	{
		// Deal with multiple tables in the same document
		if($this->table_on_page_count>=2) {
			$this->AddPage();
			$this->table_on_page_count = 0;
		} else {
			$this->Ln($this->separator_height);
		}
		$this->table_on_page_count++;

		// Initializations
		$this->InitMonthValues($current_month, $current_year);
		$this->RetrieveHighdays();
		$this->RetrieveHolidays();
		$this->RetrieveEvents  ();

		// Actual printing
		$this->PrintTableHeaders();
		if($this->first_weekday>=$this->start_of_week) {
			$first_day_in_row = 1 - ($this->first_weekday - $this->start_of_week);
		} else {
			$first_day_in_row = 6 + ($this->first_weekday - $this->start_of_week);
		}
		while($first_day_in_row <= $this->days_in_month) {
			$this->PushTableRow($first_day_in_row);
			$first_day_in_row += 7;
		}
	}

	// Setup the variables relative to the current month and year
	private function InitMonthValues($month, $year)
	{
		// Validate the year
		if(!is_numeric($year)) {
			$this->Error(__('The year must be a numeric value', 'rpbcalendar'));
			return;
		}
		$this->year = (int)$year;

		// Validate the month
		if(!is_numeric($month)) {
			$this->Error(__('The month must be a numeric value', 'rpbcalendar'));
			return;
		}
		$this->month = (int)$month;
		if(!($this->month>=1 && $this->month<=12)) {
			$this->Error(__('The month must be valued between 1 and 12', 'rpbcalendar'));
			return;
		}

		// Set up values
		$this->days_in_month = (int)(date("t", mktime(0, 0, 0, $this->month, 1, $this->year)));
		$this->first_weekday = (int)(date("w", mktime(0, 0, 0, $this->month, 1, $this->year)));
		$this->start_of_week = (int)get_option('start_of_week');
		$this->first_day     = mktime(0, 0, 0, $this->month, 1                   , $this->year);
		$this->last_day      = mktime(0, 0, 0, $this->month, $this->days_in_month, $this->year);
		$this->easter_day    = rpbcalendar_easter_date($this->year);
		$this->rel_first_day = ($this->first_day-$this->easter_day) / 86400;
		$this->rel_last_day  = ($this->last_day -$this->easter_day) / 86400;
		$this->sql_first_day = "'".mysql_escape_string(date('Y-m-d', $this->first_day))."'";
		$this->sql_last_day  = "'".mysql_escape_string(date('Y-m-d', $this->last_day ))."'";
	}

	// Retrieve the highdays for the current month
	private function RetrieveHighdays()
	{
		global $wpdb;
		$highdays = $wpdb->get_col('SELECT '.
			'CASE highday_month '.
				'WHEN 13 THEN highday_day'.($this->rel_first_day>=0 ? '-' : '+').abs($this->rel_first_day).'+1 '.
				'ELSE highday_day '.
			'END '.
			'FROM '.RPBCALENDAR_HIGHDAY_TABLE.' '.
			'WHERE highday_month='.$this->month.' '.
			'OR (highday_month=13 AND highday_day>='.$this->rel_first_day.' AND highday_day<='.$this->rel_last_day.');'
		);
		$this->highday_map = array_fill(1, $this->days_in_month, false);
		foreach($highdays as $highday) {
			$this->highday_map[$highday] = true;
		}
	}

	// Retrieve the holidays for the current month
	private function RetrieveHolidays()
	{
		global $wpdb;
		$holidays = $wpdb->get_results('SELECT '.
			'DAY(CASE holiday_begin<'.$this->sql_first_day.' WHEN TRUE THEN '.$this->sql_first_day.' ELSE holiday_begin END) AS actual_begin, '.
			'DAY(CASE holiday_end  >'.$this->sql_last_day .' WHEN TRUE THEN '.$this->sql_last_day .' ELSE holiday_end   END) AS actual_end '.
			'FROM '.RPBCALENDAR_HOLIDAY_TABLE.' '.
			'WHERE holiday_end>='.$this->sql_first_day.' '.
			'AND holiday_begin<='.$this->sql_last_day.';'
		);
		$this->holiday_map = array_fill(1, $this->days_in_month, false);
		foreach($holidays as $holiday) {
			$actual_begin = (int)$holiday->actual_begin;
			$actual_end   = (int)$holiday->actual_end  ;
			foreach(range($actual_begin, $actual_end) as $k) {
				$this->holiday_map[$k] = true;
			}
		}
	}

	// Retrieve the events for the current month
	private function RetrieveEvents()
	{
		global $wpdb;
		$this->event_map = array_fill(1, $days_in_month, NULL);
		for($k=1; $k<=$this->days_in_month; $k++) {
			$current_day         = date('Y-m-d', mktime(0, 0, 0, $this->month, $k, $this->year));
			$sql_current_day     = "'".mysql_escape_string($current_day)."'";
			$this->event_map[$k] = $wpdb->get_results('SELECT '.
				'event_title, event_desc, event_time, '.
				'c.category_id AS category_id, '.
				'c.category_text_color AS category_text_color, '.
				'c.category_background_color AS category_background_color '.
				'FROM '.RPBCALENDAR_EVENT_TABLE.' '.
				'LEFT OUTER JOIN '.RPBCALENDAR_CATEGORY_TABLE.' c ON event_category=c.category_id '.
				'WHERE event_begin<='.$sql_current_day.' AND event_end>='.$sql_current_day.' '.
				'ORDER BY event_time;'
			);
		}
	}

	// Print table headers
	private function PrintTableHeaders()
	{
		// Formating commands
		$this->SetFont('', 'B', $this->normal_font_size);
		$this->SetDrawColor(0);
		$this->SetFillColor(0);
		$this->SetTextColor(255);

		// Month header
		$month_header_text = rpbcalendar_month_info('name', $this->month).' '.$this->year;
		$this->Cell($this->text_width, 0, ucwords($month_header_text), 1, 1, 'C', true);

		// Formating commands and width computations
		$this->SetFont('', '');
		$this->cell_width   = array_fill(0, 7, 0    );
		$this->weekend_cell = array_fill(0, 7, false);
		$normal_day_width  = $this->text_width * 3 / 25;
		$weekend_day_width = $this->text_width * 5 / 25;

		// Weekday headers
		for($k=0; $k<7; $k++) {
			$weekday       = ($k + $this->start_of_week) % 7;
			$weekday_name  = rpbcalendar_weekday_info('name'   , $weekday);
			$is_weekend    = rpbcalendar_weekday_info('weekend', $weekday);
			$current_width = $is_weekend ? $weekend_day_width : $normal_day_width;
			$this->Cell($current_width, 0, ucwords($weekday_name), 1, 0, 'C', true);
			$this->cell_width  [$k] = $current_width;
			$this->weekend_cell[$k] = $is_weekend   ;
		}
		$this->Ln();
	}

	// Push one row in the table
	private function PushTableRow($first_day_in_row)
	{
		$this->current_row_height = $this->minimum_cell_height;
		$this->first_pass_row     = true;
		$this->startTransaction();
		$this->PrintTableRow($first_day_in_row);
		$this->rollbackTransaction(true);
		$this->first_pass_row = false;
		$this->PrintTableRow($first_day_in_row);
		$this->Ln();
	}

	// Print one row in the table
	private function PrintTableRow($first_day_in_row)
	{
		for($k=0; $k<7; $k++) {
			$current_day = $first_day_in_row + $k;
			if($current_day<=0 || $current_day>$this->days_in_month) {
				$current_height = $this->PushPhantomCell($k);
			} else {
				$current_height = $this->PushRegularCell($k, $current_day);
			}
			if($this->first_pass_row) {
				$this->current_row_height = max($this->current_row_height, $current_height);
			}
		}
	}

	// Append a phantom cell to the current table
	private function PushPhantomCell($current_column)
	{
		$current_height = $this->first_pass_row ? 0 : $this->current_row_height;
		$this->SetDrawColor(0);
		$this->SetFillColor(128);
		$this->Cell($this->cell_width[$current_column], $current_height, '', 1, 0, 'L', true);
		$x = $this->GetX();
		$y = $this->GetY();
		$this->Ln();
		$retval = $this->GetY() - $y;
		$this->SetXY($x, $y);
		return $retval;
	}

	// Append a regular day cell to the current table
	private function PushRegularCell($current_column, $current_day)
	{
		// Dimensions
		$x = $this->GetX();
		$y = $this->GetY();
		$w = $this->cell_width[$current_column];

		// Background
		if($this->highday_map[$current_day] || $this->weekend_cell[$current_column]) {
			$this->SetFillColor(244, 232, 210);
		} else {
			$this->SetFillColor(255);
		}
		$this->Cell($w, $this->current_row_height, '', 0, 0, '', true);

		// Holiday bar
		if($this->holiday_map[$current_day]) {
			$this->SetFillColor(96, 208, 32);
			$this->Rect($x, $y, $w, $this->holiday_bar_height, 'F');
		}

		// Label
		$this->SetFont('', 'B', $this->small_font_size);
		$this->SetDrawColor(0);
		if($this->highday_map[$current_day] || $this->weekend_cell[$current_column]) {
			$this->SetFillColor(0);
			$this->SetTextColor(244, 232, 210);
		} else {
			$this->SetFillColor(255);
			$this->SetTextColor(0);
		}
		$this->SetXY($x, $y);
		$this->Cell($this->day_label_width, 0, $current_day, 'BR', 1, 'C', true);

		// Events
		$this->SetFont('', '', $this->small_font_size);
		foreach($this->event_map[$current_day] as $event) {
			$this->PushEvent($event, $w, $x);
		}

		// Height of the cell
		$retval = $this->GetY() + $this->event_margin_tb - $y;

		// Border
		$this->SetXY($x, $y);
		$this->SetDrawColor(0);
		$this->Cell($w, $this->current_row_height, '', 1, 0, '', false);
		return $retval;
	}

	// Append an event to the current cell
	private function PushEvent($event, $w, $x)
	{
		// Content
		$this->SetEventColors($event);
		$text = '<b>' . $event->event_title . '</b>';
		if(isset($event->event_time) && !empty($event->event_time)) {
			$text .= __(' at ', 'rpbcalendar') . date_i18n(get_option('time_format'), strtotime($event->event_time));
		}
		if(strlen($event->event_desc)!=0) {
			$text .= '<br/>' . rpbcalendar_format_event_desc($event->event_desc);
		}

		// Rendering
		$y = $this->GetY();
		$this->SetXY($x+$this->event_margin_lr, $y+$this->event_margin_tb);
		if(!$this->first_pass_row) {
			$this->startTransaction();
			$this->MultiCell($w-2*$this->event_margin_lr, 0, $text, 0, 'L', false, 1, null, null, true, 0, true);
			$event_height = $this->GetY() - ($y+$this->event_margin_tb);
			$this->rollbackTransaction(true);
			$this->Rect($this->GetX(), $this->GetY(), $w-2*$this->event_margin_lr, $event_height, 'FD');
		}
		$this->MultiCell($w-2*$this->event_margin_lr, 0, $text, 0, 'L', false, 1, null, null, true, 0, true);
		$this->SetX($x);
	}

	// Setup event colors
	private function SetEventColors($event)
	{
		if(isset($event->category_id)) {
			if(!array_key_exists($event->category_id, $this->lookup_fill_color)) {
				$this->lookup_fill_color[$event->category_id] = $this->ConvertColor($event->category_background_color);
			}
			if(!array_key_exists($event->category_id, $this->lookup_text_color)) {
				$this->lookup_text_color[$event->category_id] = $this->ConvertColor($event->category_text_color);
			}
			$fill_color = $this->lookup_fill_color[$event->category_id];
			$text_color = $this->lookup_text_color[$event->category_id];
			$this->SetDrawColor($text_color[0], $text_color[1], $text_color[2]);
			$this->SetTextColor($text_color[0], $text_color[1], $text_color[2]);
			$this->SetFillColor($fill_color[0], $fill_color[1], $fill_color[2]);
		} else {
			$this->SetDrawColor(0);
			$this->SetTextColor(0);
			$this->SetFillColor(255, 255, 208);
		}
	}

	// Convert a color given by its hex code (such as '#ff8000') to a RVB array
	// (such as 'array(255, 128, 0)')
	private function ConvertColor($hex_color)
	{
		$value = hexdec($hex_color);
		$r     = floor($value / 65536) % 256;
		$v     = floor($value / 256  ) % 256;
		$b     =       $value          % 256;
		return array($r, $v, $b);
	}
}

?>
