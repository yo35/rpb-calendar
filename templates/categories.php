<?php

	// Checks
	if(!isset($categories)) {
		rpbcalendar_error_message(
			__('Unable to print the list of categories', 'rpbcalendar')
		);
		return;
	}

	// Displaying categories
	foreach($categories as $category) {
		$category_class = 'rpbcalendar-category-'.htmlspecialchars($category->category_id);
		echo '<div class="rpbcalendar-category '.$category_class.'">';
		echo htmlspecialchars($category->category_name);
		echo '</div>';
	}
?>
