<?php

// Plugin version
// Don't forget to update the version field at the top of rpbcalendar.php
define('RPBCALENDAR_VERSION', '0.9');

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

// Display an error message
function rpbcalendar_error_message($error_message)
{
	echo '<div class="rpbcalendar-error-message">'.htmlspecialchars($error_message).'</div>';
}

// Plugin options
function rpbcalendar_permissions     () { return get_option('rpbcalendar_permissions', 'manage_options'); }
function rpbcalendar_display_author  () { return get_option('rpbcalendar_display_author'  , 'true')=='true'; }
function rpbcalendar_display_category() { return get_option('rpbcalendar_display_category', 'true')=='true'; }

// Navigate form (begin)
function rpbcalendar_begin_navigate_form($form_name, $fields_to_skip)
{
	$current_url   = get_permalink();
	$question_mark = strpos($current_url, '?');
	$base_url      = ($question_mark===false) ? $current_url : substr($current_url, 0, $question_mark);
	$form_id       = 'rpbcalendar-'.$form_name.'-form';
	echo '<div id="'.$form_id.'"><form name="'.$form_name.'" method="get" action="'.$base_url.'">';
	foreach($_GET as $key => $value) {
		if(array_search($key, $fields_to_skip)===false) {
			echo '<input type="hidden" name="'.htmlspecialchars($key).'" value="'.htmlspecialchars($value).'" />';
		}
	}
}

// Navigate form (end)
function rpbcalendar_end_navigate_form($submit_label=NULL, $submit_title=NULL)
{
	if(isset($submit_label)) {
		$title = isset($submit_title) ? ' title="'.$submit_title.'"' : '';
		echo '<input type="submit" value="'.$submit_label.'"'.$title.' />';
	}
	echo '</form></div>';
}

// Navigate form (simple version)
function rpbcalendar_navigate_form($form_name, $params, $submit_label, $submit_title=NULL)
{
	rpbcalendar_begin_navigate_form($form_name, array_keys($params));
	foreach($params as $key => $value) {
		echo '<input type="hidden" name="'.htmlspecialchars($key).'" value="'.htmlspecialchars($value).'" />';
	}
	rpbcalendar_end_navigate_form($submit_label, $submit_title);
}

// SELECT ... FROM ... part of the query to use to retrieve events from the database
function rpbcalendar_select_events_base_sql()
{
	global $wpdb;
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
	return $select_part.$from_part;
}

// Remove the comments from a CSS string
function rpbcalendar_clean_up_css($css)
{
	return preg_replace('/\/\*.*\*\//','', $css);
}

?>
