<?php

	// WP database
	global $wpdb;

	// Link to the current page
	$base_link = site_url().'/wp-admin/admin.php?page=rpbcalendar-holidays';

	// Look for the ID of the holiday to be deleted
	if(!isset($_GET['delete'])) {
		rpbcalendar_admin_error_message(__('No holiday ID provided', 'rpbcalendar'), $base_link);
		return;
	}

	// Retrieve the holiday
	$holiday = $wpdb->get_row(
		"SELECT holiday_id, holiday_name ".
		"FROM ".RPBCALENDAR_HOLIDAY_TABLE." ".
		"WHERE holiday_id=".mysql_escape_string($_GET['delete'])." ".
		"LIMIT 1;"
	);

	// Check the holiday
	if(!isset($holiday)) {
		rpbcalendar_admin_error_message(sprintf(__('Unable to retrieve the holiday with ID %s',
			'rpbcalendar'), htmlspecialchars($_GET['delete'])), $base_link);
		return;
	}

	// Retrieve holiday data
	$holiday_id   = htmlspecialchars($holiday->holiday_id  );
	$holiday_name = htmlspecialchars($holiday->holiday_name);
?>

<!-- Ask the user to confirm the deletion -->
<form name="holidayform" method="post" action="<?php echo $base_link; ?>">
	<input type="hidden" name="mode" value="delete" />
	<input type="hidden" name="holiday_id" value="<?php echo $holiday_id; ?>" />
	<p>
		<?php echo sprintf(__('Are you sure that you want to delete the holiday %s?', 'rpbcalendar'),
			$holiday_name); ?>
	</p>
	<p class="submit">
		<input class="button-primary" type="submit" value="<?php _e('Delete', 'rpbcalendar'); ?>" />
	</p>
</form>
