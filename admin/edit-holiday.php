<?php

	// WP database
	global $wpdb;

	// Link to the current page
	$base_link = site_url().'/wp-admin/admin.php?page=rpbcalendar-holidays';

	// Look for the ID of the category to be edited
	if(!isset($_GET['edit'])) {
		rpbcalendar_admin_error_message(__('No holiday ID provided', 'rpbcalendar'), $base_link);
		return;
	}

	// Retrieve the holiday
	$holiday = $wpdb->get_row(
		"SELECT holiday_id, holiday_name, holiday_begin, holiday_end ".
		"FROM ".RPBCALENDAR_HOLIDAY_TABLE." ".
		"WHERE holiday_id=".mysql_escape_string($_GET['edit'])." ".
		"LIMIT 1;"
	);

	// Check the holiday
	if(!isset($holiday)) {
		rpbcalendar_admin_error_message(sprintf(__('Unable to retrieve the holiday with ID %s',
			'rpbcalendar'), htmlspecialchars($_GET['edit'])), $base_link);
		return;
	}

	// Retrieve holiday data
	$holiday_id    = htmlspecialchars($holiday->holiday_id   );
	$holiday_name  = htmlspecialchars($holiday->holiday_name );
	$holiday_begin = htmlspecialchars($holiday->holiday_begin);
	$holiday_end   = htmlspecialchars($holiday->holiday_end  );
?>

<!-- Edit holiday form -->
<form name="holidayform" method="post" action="<?php echo $base_link; ?>">
	<input type="hidden" name="mode" value="update" />
	<input type="hidden" name="holiday_id" value="<?php echo $holiday_id; ?>" />
	<table class="form-table"><tbody>
		<tr class="form-field">
			<th scope="row"><label for="holiday_name"><?php _e('Name', 'rpbcalendar'); ?></label></th>
			<td><input type="text" name="holiday_name" maxlength="30" value="<?php echo $holiday_name; ?>" /></td>
		</tr>
		<tr class="form-field">
			<th scope="row"><label for="holiday_begin"><?php _e('First day', 'rpbcalendar'); ?></label></th>
			<td>
				<input type="text" name="holiday_begin" maxlength="10" value="<?php echo $holiday_begin; ?>" />
				<p class="description">
					<?php _e('Use the following format: yyyy-mm-dd', 'rpbcalendar'); ?>
				</p>
			</td>
		</tr>
		<tr class="form-field">
			<th scope="row"><label for="holiday_end"><?php _e('Last day', 'rpbcalendar'); ?></label></th>
			<td>
				<input type="text" name="holiday_end" maxlength="10" value="<?php echo $holiday_end; ?>" />
				<p class="description">
					<?php _e('Use the following format: yyyy-mm-dd', 'rpbcalendar'); ?>
				</p>
			</td>
		</tr>
	</tbody></table>
	<p class="submit">
		<input class="button-primary" type="submit" value="<?php _e('Update', 'rpbcalendar'); ?>" />
	</p>
</form>
