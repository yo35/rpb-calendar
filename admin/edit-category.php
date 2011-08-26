<?php

	// WP database
	global $wpdb;

	// Link to the current page
	$base_link = site_url().'/wp-admin/admin.php?page=rpbcalendar-categories';

	// Look for the ID of the category to be edited
	if(!isset($_GET['edit'])) {
		rpbcalendar_admin_error_message(__('No category ID provided', 'rpbcalendar'), $base_link);
		return;
	}

	// Retrieve the category
	$category = $wpdb->get_row(
		"SELECT category_id, category_name, category_text_color, category_background_color ".
		"FROM ".RPBCALENDAR_CATEGORY_TABLE." ".
		"WHERE category_id=".mysql_escape_string($_GET['edit'])." ".
		"LIMIT 1;"
	);

	// Check the category
	if(!isset($category)) {
		rpbcalendar_admin_error_message(sprintf(__('Unable to retrieve the category with ID %s',
			'rpbcalendar'), htmlspecialchars($_GET['edit'])), $base_link);
		return;
	}

	// Retrieve category data
	$category_id               = htmlspecialchars($category->category_id              );
	$category_name             = htmlspecialchars($category->category_name            );
	$category_text_color       = htmlspecialchars($category->category_text_color      );
	$category_background_color = htmlspecialchars($category->category_background_color);
?>

<!-- Edit category form -->
<form name="catform" id="catform" method="post" action="<?php echo $base_link; ?>">
	<input type="hidden" name="mode" value="update" />
	<input type="hidden" name="category_id" value="<?php echo $category_id; ?>" />
	<table class="form-table"><tbody>
		<tr class="form-field">
			<th scope="row"><label for="category_name"><?php _e('Name', 'rpbcalendar'); ?></label></th>
			<td><input type="text" name="category_name" maxlength="30" value="<?php echo $category_name; ?>" /></td>
		</tr>
		<tr class="form-field">
			<th scope="row"><label for="category_text_color"><?php _e('Text color', 'rpbcalendar'); ?></label></th>
			<td>
				<input type="text" name="category_text_color" maxlength="7" value="<?php echo $category_text_color; ?>" />
				<p class="description">
					<?php _e('Use HTML hexa format (ex: #0000ff for blue or #ffff00 for yellow)', 'rpbcalendar'); ?>
				</p>
			</td>
		</tr>
		<tr class="form-field">
			<th scope="row"><label for="category_background_color"><?php _e('Background color', 'rpbcalendar'); ?></label></th>
			<td>
				<input type="text" name="category_background_color" maxlength="7" value="<?php echo $category_background_color; ?>" />
				<p class="description">
					<?php _e('Use HTML hexa format (ex: #0000ff for blue or #ffff00 for yellow)', 'rpbcalendar'); ?>
				</p>
			</td>
		</tr>
	</tbody></table>
	<p class="submit">
		<input class="button-primary" type="submit" value="<?php _e('Update', 'rpbcalendar'); ?>" />
	</p>
</form>
