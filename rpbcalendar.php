<?php
/*
Plugin Name: RpbCalendar
Description: This plugin allows you to display a calendar of all your events and appointments as a page on your website.
Author: Yoann Le Montagner
Version: 0.11
*/

// Don't forget to update the version field at the top of config.php

// Configuration file
require_once(dirname(__FILE__).'/config.php');



////////////////////////////////////////////////////////////////////////////////
// Utilities

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

	// Highday page
	$page = add_submenu_page('rpbcalendar', __('Manage highdays', 'rpbcalendar'), __('Manage highdays', 'rpbcalendar'),
		'manage_options', 'rpbcalendar-highdays', 'rpbcalendar_manage_highdays');
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

// Event explorer
add_shortcode('rpbexplorer', 'rpbcalendar_shortcode_rpbexplorer');
function rpbcalendar_shortcode_rpbexplorer($atts)
{
	ob_start();
	include(RPBCALENDAR_ABSPATH.'templates/explorer.php');
	return ob_get_clean();
}

// Printer
add_shortcode('rpbprinter', 'rpbcalendar_shortcode_printer');
function rpbcalendar_shortcode_printer($atts)
{
	ob_start();
	include(RPBCALENDAR_ABSPATH.'templates/printer.php');
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
