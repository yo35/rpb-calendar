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
	$sql        = "SELECT * FROM " . WP_CALENDAR_CATEGORIES_TABLE;
	$categories = $wpdb->get_results($sql);

	// Display
	echo '<style type="text/css">';
	foreach($categories as $category) {
		$output  = "";
		$output .= ".rpbcalendar-category-".htmlspecialchars($category->category_id    )." {\n";
		$output .= "    background-color: ".htmlspecialchars($category->category_colour).";\n";
		$output .= "}\n";
		echo $output;
	}
	echo '</style>';
}

?>
