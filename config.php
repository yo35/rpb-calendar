<?php

// Plugin version
// Don't forget to update the version field at the top of rpbcalendar.php
define('RPBCALENDAR_VERSION', '1.0');

// Directories
define('RPBCALENDAR_PLUGIN_DIR', basename(dirname(__FILE__)));
define('RPBCALENDAR_ABSPATH'   , ABSPATH.'wp-content/plugins/'.RPBCALENDAR_PLUGIN_DIR.'/');
define('RPBCALENDAR_URL'       , site_url().'/wp-content/plugins/'.RPBCALENDAR_PLUGIN_DIR.'/');

// Enable internationalization
load_plugin_textdomain('rpbcalendar', false, RPBCALENDAR_PLUGIN_DIR.'/languages/');

// WP database
global $wpdb;

// Define the tables used by the plugin
define('RPBCALENDAR_EVENT_TABLE'   , $wpdb->prefix . 'rpbcalendar_events'    );
define('RPBCALENDAR_CATEGORY_TABLE', $wpdb->prefix . 'rpbcalendar_categories');
define('RPBCALENDAR_HIGHDAY_TABLE' , $wpdb->prefix . 'rpbcalendar_highdays'  );
define('RPBCALENDAR_HOLIDAY_TABLE' , $wpdb->prefix . 'rpbcalendar_holidays'  );

// Return the local time
function rpbcalendar_time()
{
  return time() + 3600*get_option('gmt_offset');
}

// Easter date for the given year
function rpbcalendar_easter_date($year)
{
	return mktime(0, 0, 0, 3, 21, $year) + 86400*easter_days($year);
}

// Fomat event description strings
function rpbcalendar_format_event_desc($raw_desc)
{
	$lines  = explode("\n", trim($raw_desc));
	$retval = '';
	foreach($lines as $line) {
		if($retval!='') {
			$retval .= '<br/>';
		}
		$retval .= htmlspecialchars(trim($line));
	}
	return $retval;
}

// Format a date range
function rpbcalendar_format_date_range($date_begin, $date_end, $display_year=false)
{
	// One day event
	if($date_begin==$date_end) {
		$str_date = strtotime($date_begin);
		$retval   = date('j', $str_date).' '.rpbcalendar_month_info('name', date('n', $str_date));
		if($display_year) {
			$retval .= ' '.$year_begin  = date('Y', $str_date);
		}
		return $retval;

	// Long event
	} else {
		$str_begin   = strtotime($date_begin);
		$str_end     = strtotime($date_end  );
		$day_begin   = date('j', $str_begin);
		$day_end     = date('j', $str_end  );
		$month_begin = date('n', $str_begin);
		$month_end   = date('n', $str_end  );
		$year_begin  = date('Y', $str_begin);
		$year_end    = date('Y', $str_end  );
		$range_begin = $day_begin;
		if($month_begin!=$month_end || $year_begin!=$year_end) {
			$range_begin .= ' '.rpbcalendar_month_info('name', $month_begin);
			if($display_year && $year_begin!=$year_end) {
				$range_begin .= ' '.$year_begin;
			}
		}
		$range_end = $day_end.' '.rpbcalendar_month_info('name', $month_end);
		if($display_year) {
			$range_end .= ' '.$year_end;
		}
		return sprintf(__('From %1$s to %2$s', 'rpbcalendar'), $range_begin, $range_end);
	}
}

// Check whether a given link targets a page in the current website
function rpbcalendar_is_internal_link($link)
{
	$home_link    = site_url().'/';
	$lg_home_link = strlen($home_link);
	if(strlen($link)<$lg_home_link) {
		return false;
	} else {
		return substr_compare($link, $home_link, 0, $lg_home_link, true)==0;
	}
}

// Weekday info
function rpbcalendar_weekday_info($info, $weekday_idx=NULL)
{
	static $retval = NULL;
	if(!isset($reval)) {
		$retval = array(
			'name' => array(
				0=>__('sunday'   , 'rpbcalendar'),
				1=>__('monday'   , 'rpbcalendar'),
				2=>__('tuesday'  , 'rpbcalendar'),
				3=>__('wednesday', 'rpbcalendar'),
				4=>__('thursday' , 'rpbcalendar'),
				5=>__('friday'   , 'rpbcalendar'),
				6=>__('saturday' , 'rpbcalendar')
			),
			'weekend' => array(
				0=>true ,
				1=>false,
				2=>false,
				3=>false,
				4=>false,
				5=>false,
				6=>true
			)
		);
	}
	return isset($weekday_idx) ? $retval[$info][$weekday_idx] : $retval[$info];
}

// Month info
function rpbcalendar_month_info($info, $month_idx=NULL)
{
	static $retval = NULL;
	if(!isset($reval)) {
		$retval = array(
			'name' => array(
				 1=>__('january'  , 'rpbcalendar'),
				 2=>__('february' , 'rpbcalendar'),
				 3=>__('march'    , 'rpbcalendar'),
				 4=>__('april'    , 'rpbcalendar'),
				 5=>__('may'      , 'rpbcalendar'),
				 6=>__('june'     , 'rpbcalendar'),
				 7=>__('july'     , 'rpbcalendar'),
				 8=>__('august'   , 'rpbcalendar'),
				 9=>__('september', 'rpbcalendar'),
				10=>__('october'  , 'rpbcalendar'),
				11=>__('november' , 'rpbcalendar'),
				12=>__('december' , 'rpbcalendar')
			)
		);
	}
	return isset($month_idx) ? $retval[$info][$month_idx] : $retval[$info];
}

?>
