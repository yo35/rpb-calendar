<?php

// Plugin version
define('RPBCALENDAR_VERSION', '0.2');

// Define the tables used by the plugin
global $wpdb;
define('RPBCALENDAR_EVENT_TABLE'   , $wpdb->prefix . 'rpbcalendar_events'    );
define('RPBCALENDAR_CATEGORY_TABLE', $wpdb->prefix . 'rpbcalendar_categories');

// Display an error message
function rpbcalendar_error_message($error_message)
{
	echo '<div class="rpbcalendar-error-message">'.htmlspecialchars($error_message).'</div>';
}

// Information about the days of a week
function rpbcalendar_weekday_info($weekday_idx, $info)
{
	$retval = array(
		0 => array('name'=>__('Sunday'   , 'calendar'), 'weekend'=>true ),
		1 => array('name'=>__('Monday'   , 'calendar'), 'weekend'=>false),
		2 => array('name'=>__('Tuesday'  , 'calendar'), 'weekend'=>false),
		3 => array('name'=>__('Wednesday', 'calendar'), 'weekend'=>false),
		4 => array('name'=>__('Thursday' , 'calendar'), 'weekend'=>false),
		5 => array('name'=>__('Friday'   , 'calendar'), 'weekend'=>false),
		6 => array('name'=>__('Saturday' , 'calendar'), 'weekend'=>true )
	);
	return isset($retval[$weekday_idx]) ? $retval[$weekday_idx][$info] : NULL;
}

// Return informations about the months
function rpbcalendar_month_info($month_idx, $info)
{
	$retval = array(
		 1 => array('name'=>__('January'  , 'calendar')),
		 2 => array('name'=>__('February' , 'calendar')),
		 3 => array('name'=>__('March'    , 'calendar')),
		 4 => array('name'=>__('April'    , 'calendar')),
		 5 => array('name'=>__('May'      , 'calendar')),
		 6 => array('name'=>__('June'     , 'calendar')),
		 7 => array('name'=>__('July'     , 'calendar')),
		 8 => array('name'=>__('August'   , 'calendar')),
		 9 => array('name'=>__('September', 'calendar')),
		10 => array('name'=>__('October'  , 'calendar')),
		11 => array('name'=>__('November' , 'calendar')),
		12 => array('name'=>__('December' , 'calendar'))
	);
	return isset($retval[$month_idx]) ? $retval[$month_idx][$info] : NULL;
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

?>
