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

// Return the local time
function rpbcalendar_time()
{
  return time() + 3600*get_option('gmt_offset');
}

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

// Navigate form (begin)
function rpbcalendar_begin_navigate_form($form_name, $fields_to_skip)
{
	$current_url   = get_permalink();
	$question_mark = strpos($current_url, '?');
	$base_url      = ($question_mark===false) ? $current_url : substr($current_url, 0, $question_mark);
	$form_id       = 'rpbcalendar-'.$form_name.'-form';
	echo '<form id="'.$form_id.'" name="'.$form_name.'" method="get" action="'.$base_url.'">';
	foreach($_GET as $key => $value) {
		if(array_search($key, $fields_to_skip)===false) {
			echo '<input type="hidden" name="'.htmlspecialchars($key).'" value="'.htmlspecialchars($value).'" />';
		}
	}
}

// Navigate form (end)
function rpbcalendar_end_navigate_form($submit_label, $submit_title)
{
	if(isset($submit_label)) {
		$title = isset($submit_title) ? ' title="'.$submit_title.'"' : '';
		echo '<input type="submit" value="'.$submit_label.'"'.$title.' />';
	}
	echo '</form>';
}

// Navigate form (simple version)
function rpbcalendar_navigate_form($form_name, $params, $submit_label, $submit_title)
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
	wp_register_style('rpbcalendar-general', RPBCALENDAR_URL.'/css/rpbcalendar.css');
	wp_enqueue_style ('rpbcalendar-general');
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
	include(RPBCALENDAR_ABSPATH.'templates/calendar.php');
	return ob_get_clean();
}



////////////////////////////////////////////////////////////////////////////////
// Widgets

// Today's events
class RpbcTodaysEvents extends WP_Widget
{
	// Constructor
	function __construct()
	{
		$widget_ops = array(
			'description' => __('Display the list of today\'s events', 'rpbcalendar')
		);
		parent::__construct('rpbcalendar_todays_events', __('Today\'s events', 'rpbcalendar'), $widget_ops);
	}

	// Display
	function widget($args, $instance)
	{
		include(RPBCALENDAR_ABSPATH.'templates/todaywidget.php');
	}

	// Update
	function update($new_instance, $old_instance)
	{
		$instance          = $old_instance;
		$instance['title'] = $new_instance['title'];
		return $instance;
	}

	// Configuration
	function form($instance)
	{
		$title = __('Today\'s events', 'rpbcalendar');
		if(isset($instance['title'])) {
			$title = htmlspecialchars($instance['title']);
		}
		echo '<p>';
		echo '<label for="'.$this->get_field_id('title').'">'.__('Title:', 'rpbcalendar').'</label>';
		echo '<input type="text" class="widefat" id="'.$this->get_field_id('title').'" name="'.
			$this->get_field_name('title').'" value="'.$title.'" />';
		echo '</p>';
	}
}

// Upcoming events
class RpbcUpcomingEvents extends WP_Widget
{
	// Constructor
	function __construct()
	{
		$widget_ops = array(
			'description' => __('Display a list of upcoming events', 'rpbcalendar')
		);
		parent::__construct('rpbcalendar_upcoming_events', __('Upcoming events', 'rpbcalendar'), $widget_ops);
	}

	// Display
	function widget($args, $instance)
	{
		include(RPBCALENDAR_ABSPATH.'templates/upcomingwidget.php');
	}

	// Update
	function update($new_instance, $old_instance)
	{
		$instance          = $old_instance;
		$instance['title'] = $new_instance['title'];
		return $instance;
	}

	// Configuration
	function form($instance)
	{
		$title = __('Upcoming events', 'rpbcalendar');
		if(isset($instance['title'])) {
			$title = htmlspecialchars($instance['title']);
		}
		echo '<p>';
		echo '<label for="'.$this->get_field_id('title').'">'.__('Title:', 'rpbcalendar').'</label>';
		echo '<input type="text" class="widefat" id="'.$this->get_field_id('title').'" name="'.
			$this->get_field_name('title').'" value="'.$title.'" />';
		echo '</p>';
	}
}

// Register widgets
add_action('widgets_init', 'rpbcalendar_register_widgets');
function rpbcalendar_register_widgets()
{
	register_widget('RpbcTodaysEvents'  );
	register_widget('RpbcUpcomingEvents');
}

?>
