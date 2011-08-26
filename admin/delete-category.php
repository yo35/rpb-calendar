<?php

	// WP database
	global $wpdb;

	// Link to the current page
	$base_link = site_url().'/wp-admin/admin.php?page=rpbcalendar-categories';

	// Look for the ID of the category to be edited
	if(!isset($_GET['delete'])) {
		rpbcalendar_admin_error_message(__('No category ID provided', 'rpbcalendar'), $base_link);
		return;
	}

	// Retrieve the category
	$category = $wpdb->get_row(
		"SELECT category_id, category_name ".
		"FROM ".RPBCALENDAR_CATEGORY_TABLE." ".
		"WHERE category_id=".mysql_escape_string($_GET['delete'])." ".
		"LIMIT 1;"
	);

	// Check the category
	if(!isset($category)) {
		rpbcalendar_admin_error_message(sprintf(__('Unable to retrieve the category with ID %s',
			'rpbcalendar'), htmlspecialchars($_GET['delete'])), $base_link);
		return;
	}

	// Retrieve category data
	$category_id   = htmlspecialchars($category->category_id  );
	$category_name = htmlspecialchars($category->category_name);
?>

<!-- Ask the user to confirm the deletion -->
<form name="catform" id="catform" method="post" action="<?php echo $base_link; ?>">
	<input type="hidden" name="mode" value="delete" />
	<input type="hidden" name="category_id" value="<?php echo $category_id; ?>" />
	<p>
		<?php echo sprintf(__('Are you sure that you want to delete the category %s?', 'rpbcalendar'),
			$category_name); ?>
	</p>
	<p class="submit">
		<input class="button-primary" type="submit" value="<?php _e('Delete', 'rpbcalendar'); ?>" />
	</p>
</form>
