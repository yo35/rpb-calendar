<?php
/*
Plugin Name: RpbCalendar
Description: This plugin allows you to display a calendar of all your events and appointments as a page on your website.
Author: Yoann Le Montagner
Version: 0.3
*/

// Directories
define('RPBCALENDAR_PLUGIN_DIR', basename(dirname(__FILE__)));
define('RPBCALENDAR_ABSPATH'   , ABSPATH.'wp-content/plugins/'.RPBCALENDAR_PLUGIN_DIR.'/');
define('RPBCALENDAR_URL'       , site_url().'/wp-content/plugins/'.RPBCALENDAR_PLUGIN_DIR.'/');

// Enable internationalization
load_plugin_textdomain('rpbcalendar', false, RPBCALENDAR_PLUGIN_DIR.'/languages/');

// Plugin version
// Don't forget to update the version field at the top of this file
define('RPBCALENDAR_VERSION', '0.3');

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
function rpbcalendar_end_navigate_form($submit_label, $submit_title=NULL)
{
	if(isset($submit_label)) {
		$title = isset($submit_title) ? ' title="'.$submit_title.'"' : '';
		echo '<input type="submit" value="'.$submit_label.'"'.$title.' />';
	}
	echo '</form>';
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
	add_action('admin_print_styles-' . $page, 'rpbcalendar_admin_print_css'    );
	add_action('admin_print_scripts-'. $page, 'rpbcalendar_admin_print_scripts');

	// Event page
	$page = add_submenu_page('rpbcalendar', __('Manage events', 'rpbcalendar'), __('Manage events', 'rpbcalendar'),
		$allowed_group, 'rpbcalendar', 'rpbcalendar_manage_events');
	add_action('admin_print_styles-' . $page, 'rpbcalendar_admin_print_css'    );
	add_action('admin_print_scripts-'. $page, 'rpbcalendar_admin_print_scripts');

	// Holiday page
	$page = add_submenu_page('rpbcalendar', __('Manage holidays', 'rpbcalendar'), __('Manage holidays', 'rpbcalendar'),
		$allowed_group, 'rpbcalendar-holidays', 'rpbcalendar_manage_holidays');
	add_action('admin_print_styles-' . $page, 'rpbcalendar_admin_print_css'    );
	add_action('admin_print_scripts-'. $page, 'rpbcalendar_admin_print_scripts');

	// Category page
	$page = add_submenu_page('rpbcalendar', __('Manage categories', 'rpbcalendar'), __('Manage categories', 'rpbcalendar'),
		'manage_options', 'rpbcalendar-categories', 'rpbcalendar_manage_categories');
	add_action('admin_print_styles-' . $page, 'rpbcalendar_admin_print_css'    );
	add_action('admin_print_scripts-'. $page, 'rpbcalendar_admin_print_scripts');

	// Options page
	$page = add_submenu_page('rpbcalendar', __('Calendar options', 'rpbcalendar'), __('Calendar options', 'rpbcalendar'),
		'manage_options', 'rpbcalendar-options', 'rpbcalendar_manage_options');
	add_action('admin_print_styles-' . $page, 'rpbcalendar_admin_print_css'    );
	add_action('admin_print_scripts-'. $page, 'rpbcalendar_admin_print_scripts');
}

// Register admin CSS
add_action('admin_init', 'rpbcalendar_register_admin_css');
function rpbcalendar_register_admin_css()
{
	wp_register_style('rpbcalendar-admin'         , RPBCALENDAR_URL.'/css/admin.css'         );
	wp_register_style('rpbcalendar-calendar-popup', RPBCALENDAR_URL.'/css/calendar-popup.css');
}

// Register admin scripts
add_action('admin_init', 'rpbcalendar_register_admin_scripts');
function rpbcalendar_register_admin_scripts()
{
	wp_register_script('rpbcalendar-calendar-popup', RPBCALENDAR_URL.'/javascript/CalendarPopup.js');
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
	?>
		<style type="text/css">
		<?php foreach($categories as $category) { ?>
			.rpbcalendar-category-<?php echo htmlspecialchars($category->category_id); ?> {
				color           : <?php echo htmlspecialchars($category->category_text_color      ); ?>;
				background-color: <?php echo htmlspecialchars($category->category_background_color); ?>;
			}
		<?php } ?>
		</style>
	<?php
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

// Source files
require_once(RPBCALENDAR_ABSPATH.'todaysevents.class.php'  );
require_once(RPBCALENDAR_ABSPATH.'upcomingevents.class.php');

// Register widgets
add_action('widgets_init', 'rpbcalendar_register_widgets');
function rpbcalendar_register_widgets()
{
	register_widget('RpbcTodaysEvents'  );
	register_widget('RpbcUpcomingEvents');
}

?>
