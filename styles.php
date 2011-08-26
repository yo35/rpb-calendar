<?php

// Utilities
require_once(RPBCALENDAR_ABSPATH.'tools.php');

// Hooks
add_action('wp_print_styles', 'rpbcalendar_register_category_styles');

// Inline category styles when the page is displayed
function rpbcalendar_register_category_styles()
{
	// Retrieve category info
	global $wpdb;
	$sql        = "SELECT * FROM " . RPBCALENDAR_CATEGORY_TABLE;
	$categories = $wpdb->get_results($sql);

	// Display
	echo '<style type="text/css">';
	foreach($categories as $category) {
		$output  = "";
		$output .= ".rpbcalendar-category-".htmlspecialchars($category->category_id   )." {\n";
		$output .= "    color: ".htmlspecialchars($category->category_color).";\n";
		$output .= "    background-color: ".htmlspecialchars($category->category_background_color).";\n";
		$output .= "}\n";
		echo $output;
	}
	echo '</style>';

	wp_register_style('rpbcalendar_event_style', WP_PLUGIN_URL.'/calendar/css/event.css');
	wp_register_style('rpbcalendar_calendar_style', WP_PLUGIN_URL.'/calendar/css/calendar.css');
	wp_register_style('rpbcalendar_category_style', WP_PLUGIN_URL.'/calendar/css/category.css');
	wp_register_style('rpbcalendar_error_style', WP_PLUGIN_URL.'/calendar/css/error.css');
	wp_enqueue_style ('rpbcalendar_event_style');
	wp_enqueue_style ('rpbcalendar_calendar_style');
	wp_enqueue_style ('rpbcalendar_category_style');
	wp_enqueue_style ('rpbcalendar_error_style');
}

?>
