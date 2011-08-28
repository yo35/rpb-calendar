<?php
/*
Plugin Name: RpbCalendar
Description: This plugin allows you to display a calendar of all your events and appointments as a page on your website.
Author: Yoann Le Montagner
Version: 0.1
*/

// Directories
define('RPBCALENDAR_PLUGIN_DIR', basename(dirname(__FILE__)));
define('RPBCALENDAR_ABSPATH'   , ABSPATH.'wp-content/plugins/'.RPBCALENDAR_PLUGIN_DIR.'/');
define('RPBCALENDAR_URL'       , site_url().'/wp-content/plugins/'.RPBCALENDAR_PLUGIN_DIR.'/');

// Enable internationalization
load_plugin_textdomain('rpbcalendar', false, RPBCALENDAR_PLUGIN_DIR);

// Plugin version
define('RPBCALENDAR_VERSION', '0.1');

// Define the tables used by the plugin
global $wpdb;
define('RPBCALENDAR_EVENT_TABLE'   , $wpdb->prefix . 'rpbcalendar_events'    );
define('RPBCALENDAR_CATEGORY_TABLE', $wpdb->prefix . 'rpbcalendar_categories');
define('RPBCALENDAR_HIGHDAY_TABLE' , $wpdb->prefix . 'rpbcalendar_highdays'  );
define('RPBCALENDAR_HOLIDAY_TABLE' , $wpdb->prefix . 'rpbcalendar_holidays'  );



////////////////////////////////////////////////////////////////////////////////
// Utilities

// Display an error message
function rpbcalendar_error_message($error_message)
{
	echo '<div class="rpbcalendar-error-message">'.htmlspecialchars($error_message).'</div>';
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

// Check whether a given link targets a page in the current website
function rpbcalendar_is_internal_link($link)
{
	$home_link    = site_url();
	$lg_home_link = strlen($home_link);
	if(strlen($link)<$lg_home_link) {
		return false;
	} else {
		return substr_compare($link, $home_link, 0, $lg_home_link, true)==0;
	}
}

// Weekday info
function rpbcalendar_weekday_info($weekday_idx, $info)
{
	static $retval = NULL;
	if(!isset($reval)) {
		$retval = array(
			0 => array('name'=>__('Sunday'   , 'rpbcalendar'), 'weekend'=>true ),
			1 => array('name'=>__('Monday'   , 'rpbcalendar'), 'weekend'=>false),
			2 => array('name'=>__('Tuesday'  , 'rpbcalendar'), 'weekend'=>false),
			3 => array('name'=>__('Wednesday', 'rpbcalendar'), 'weekend'=>false),
			4 => array('name'=>__('Thursday' , 'rpbcalendar'), 'weekend'=>false),
			5 => array('name'=>__('Friday'   , 'rpbcalendar'), 'weekend'=>false),
			6 => array('name'=>__('Saturday' , 'rpbcalendar'), 'weekend'=>true )
		);
	}
	return isset($retval[$weekday_idx]) ? $retval[$weekday_idx][$info] : NULL;
}

// Plugin options
function rpbcalendar_permissions     () { return get_option('rpbcalendar_permissions', 'manage_options'); }
function rpbcalendar_display_author  () { return get_option('rpbcalendar_display_author'  , 'true')=='true'; }
function rpbcalendar_display_category() { return get_option('rpbcalendar_display_category', 'true')=='true'; }



////////////////////////////////////////////////////////////////////////////////
// Setup tables

// Install procedure
register_activation_hook(__FILE__, 'rpbcalendar_install');
function rpbcalendar_install()
{
	require_once(RPBCALENDAR_ABSPATH.'init.php');
	rpbcalendar_create_tables();
	add_option('rpbcalendar_version', RPBCALENDAR_VERSION);
}

// Check install and update
add_action('plugins_loaded', 'rpbcalendar_check_install');
function rpbcalendar_check_install()
{
	if(get_option('rpbcalendar_version')==RPBCALENDAR_VERSION) {
		return;
	}
	require_once(RPBCALENDAR_ABSPATH.'init.php');
	rpbcalendar_create_tables();
	update_option('rpbcalendar_version', RPBCALENDAR_VERSION);
}



////////////////////////////////////////////////////////////////////////////////
// Setup admin features

// Build the administration menu
add_action('admin_menu', 'rpbcalendar_build_admin_menu');
function rpbcalendar_build_admin_menu()
{
	// Init
	require_once(RPBCALENDAR_ABSPATH.'admin.php');
	$allowed_group = rpbcalendar_permissions();

	// Main menu
	$page = add_menu_page(__('Calendar', 'rpbcalendar'), __('Calendar', 'rpbcalendar'),
		$allowed_group, 'rpbcalendar', 'rpbcalendar_manage_events');
	add_action('admin_print_styles-'. $page, 'rpbcalendar_admin_print_styles');

	// Event page
	$page = add_submenu_page('rpbcalendar', __('Manage events', 'rpbcalendar'), __('Manage event', 'rpbcalendar'),
		$allowed_group, 'rpbcalendar', 'rpbcalendar_manage_events');
	add_action('admin_print_styles-'. $page, 'rpbcalendar_admin_print_styles');

	// Holiday page
	$page = add_submenu_page('rpbcalendar', __('Manage holidays', 'rpbcalendar'), __('Manage holidays', 'rpbcalendar'),
		$allowed_group, 'rpbcalendar-holidays', 'rpbcalendar_manage_holidays');
	add_action('admin_print_styles-'. $page, 'rpbcalendar_admin_print_styles');

	// Category page
	$page = add_submenu_page('rpbcalendar', __('Manage categories', 'rpbcalendar'), __('Manage categories', 'rpbcalendar'),
		'manage_options', 'rpbcalendar-categories', 'rpbcalendar_manage_categories');
	add_action('admin_print_styles-'. $page, 'rpbcalendar_admin_print_styles');

	// Options page
	$page = add_submenu_page('rpbcalendar', __('Calendar options', 'rpbcalendar'), __('Calendar options', 'rpbcalendar'),
		'manage_options', 'rpbcalendar-options', 'rpbcalendar_manage_options');
	add_action('admin_print_styles-'. $page, 'rpbcalendar_admin_print_styles');
}

// Register admin CSS
add_action('admin_init', 'rpbcalendar_register_admin_css');
function rpbcalendar_register_admin_css()
{
	wp_register_style('rpbcalendar-admin', RPBCALENDAR_URL.'/css/admin.css');
}



////////////////////////////////////////////////////////////////////////////////
// Setup CSS

// Inline category style
add_action('wp_print_styles', 'rpbcalendar_setup_category_colors');
function rpbcalendar_setup_category_colors()
{
	// Retrieve category info
	global $wpdb;
	$categories = $wpdb->get_results(
		'SELECT category_id, category_text_color, category_background_color FROM '.RPBCALENDAR_CATEGORY_TABLE.';'
	);

	// Display
	echo '<style type="text/css">';
	foreach($categories as $category) {
		$output  = "";
		$output .= ".rpbcalendar-category-".htmlspecialchars($category->category_id)." {\n";
		$output .= "    color: ".htmlspecialchars($category->category_text_color).";\n";
		$output .= "    background-color: ".htmlspecialchars($category->category_background_color).";\n";
		$output .= "}\n";
		echo $output;
	}
	echo '</style>';
}

// Enqueue general styles
add_action('wp_print_styles', 'rpbcalendar_enqueue_general_css');
function rpbcalendar_enqueue_general_css()
{
	wp_register_style('rpbcalendar_event_style'   , RPBCALENDAR_URL.'/css/event.css');
	wp_register_style('rpbcalendar_calendar_style', RPBCALENDAR_URL.'/css/calendar.css');
	wp_register_style('rpbcalendar_category_style', RPBCALENDAR_URL.'/css/category.css');
	wp_register_style('rpbcalendar_error_style'   , RPBCALENDAR_URL.'/css/error.css');
	wp_enqueue_style ('rpbcalendar_event_style'   );
	wp_enqueue_style ('rpbcalendar_calendar_style');
	wp_enqueue_style ('rpbcalendar_category_style');
	wp_enqueue_style ('rpbcalendar_error_style'   );
}



////////////////////////////////////////////////////////////////////////////////
// Shortcodes

// List of categories
add_shortcode('rpbcategories', 'rpbcalendar_shortcode_rpbcategories');
function rpbcalendar_shortcode_rpbcategories($atts)
{
	global $wpdb;
	$categories = $wpdb->get_results(
		'SELECT category_id, category_name FROM '.RPBCALENDAR_CATEGORY_TABLE.' ORDER BY category_name;'
	);
	ob_start();
	include(RPBCALENDAR_ABSPATH.'templates/categories.php');
	return ob_get_clean();
}

// Calendar
add_shortcode('rpbcalendar', 'rpbcalendar_shortcode_rpbcalendar');
function rpbcalendar_shortcode_rpbcalendar($atts)
{
	ob_start();
	//echo 'blah: ';
	//$myarray = array_fill(1, 10, false);
	//foreach(range(2,4) as $k) {
	//	$myarray[$k] = true;
	//}
	//$myarray[2] = true;
	//var_dump($myarray);
	//var_dump(date('Y-m-d', mktime(0, 0, 0, 8, 1, 2011)));
	$current_year = 2011;
	$current_month = 8;

	include(RPBCALENDAR_ABSPATH.'templates/calendar.php');

	return ob_get_clean();
}

?>
