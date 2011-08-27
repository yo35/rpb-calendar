<?php

// Hooks
add_action('admin_init'        , 'rpbcalendar_admin_init' );
add_action('admin_print_styles', 'rpbcalendar_admin_style');

// Function to report an error
function rpbcalendar_admin_error_message($message, $go_back_link)
{
	echo '<div class="error"><p>'.$message.'</p></div>';
	if(isset($go_back_link)) {
		echo '<a class="button-primary" href="'.$go_back_link.'">'.
			__('Go back', 'rpbcalendar').'</a>';
	}
}

// Function to display a notification message
function rpbcalendar_admin_notification_message($message)
{
	echo '<div class="updated"><p>'.$message.'</p></div>';
}

// Init admin interface
function rpbcalendar_admin_init()
{
	wp_register_style('rpbcalendar_admin_style', WP_PLUGIN_URL.'/calendar/css/admin.css');
}

// Register admin styles
function rpbcalendar_admin_style()
{
	wp_enqueue_style('rpbcalendar_admin_style');
}

// Validate an ID string
function rpbcalendar_validate_id($id)
{
	if(!isset($id) || empty($id) || !is_numeric($id)) {
		rpbcalendar_admin_error_message(__('Badly formatted ID string', 'rpbcalendar'));
		return false;
	}
	return true;
}

// Validate a name string
function rpbcalendar_validate_name($name)
{
	if(!isset($name) || empty($name)) {
		rpbcalendar_admin_error_message(__('Badly formatted name string', 'rpbcalendar'));
		return false;
	}
	return true;
}

// Validate a color string
function rpbcalendar_validate_color($color)
{
	if(!isset($color) || !preg_match('/#[0-9a-fA-F]{6}/', $color)) {
		rpbcalendar_admin_error_message(__('Badly formatted color string', 'rpbcalendar'));
		return false;
	}
	return true;
}

// Validate a date string
function rpbcalendar_validate_date($date)
{
	if(!isset($date) || !preg_match('/[0-9]{4}-[0-9]{2}-[0-9]{2}/', $date)) {
		rpbcalendar_admin_error_message(__('Badly formatted date string', 'rpbcalendar'));
		return false;
	}
	return true;
}

// Deal with add category requests
function rpbcalendar_process_add_category_request()
{
	global $wpdb;
	if(!(isset($_POST['mode']) && $_POST['mode']=='add')) {
		return;
	}
	$all_valid = true;
	$all_valid = rpbcalendar_validate_name ($_POST['category_name'            ]) && $all_valid;
	$all_valid = rpbcalendar_validate_color($_POST['category_text_color'      ]) && $all_valid;
	$all_valid = rpbcalendar_validate_color($_POST['category_background_color']) && $all_valid;
	if(!$all_valid) {
		return;
	}
	$retval = $wpdb->query(
		"INSERT INTO ".RPBCALENDAR_CATEGORY_TABLE." ".
		"(category_name, category_text_color, category_background_color) ".
		"VALUES (".
			"'".mysql_escape_string($_POST['category_name'            ])."', ".
			"'".mysql_escape_string($_POST['category_text_color'      ])."', ".
			"'".mysql_escape_string($_POST['category_background_color'])."'".
		");"
	);
	if($retval==1) {
		rpbcalendar_admin_notification_message(__('1 category successfully added', 'rpbcalendar'));
	} else {
		rpbcalendar_admin_error_message(__('Unable to add the new category', 'rpbcalendar'));
	}
}

// Deal with update category requests
function rpbcalendar_process_update_category_request()
{
	global $wpdb;
	if(!(isset($_POST['mode']) && $_POST['mode']=='update')) {
		return;
	}
	$all_valid = true;
	$all_valid = rpbcalendar_validate_id   ($_POST['category_id'              ]) && $all_valid;
	$all_valid = rpbcalendar_validate_name ($_POST['category_name'            ]) && $all_valid;
	$all_valid = rpbcalendar_validate_color($_POST['category_text_color'      ]) && $all_valid;
	$all_valid = rpbcalendar_validate_color($_POST['category_background_color']) && $all_valid;
	if(!$all_valid) {
		return;
	}
	$retval = $wpdb->query(
		"UPDATE ".RPBCALENDAR_CATEGORY_TABLE." SET ".
			"category_name             = '".mysql_escape_string($_POST['category_name'            ])."', ".
			"category_text_color       = '".mysql_escape_string($_POST['category_text_color'      ])."', ".
			"category_background_color = '".mysql_escape_string($_POST['category_background_color'])."' ".
		"WHERE category_id=".mysql_escape_string($_POST['category_id']).";"
	);
	if($retval==1) {
		rpbcalendar_admin_notification_message(__('1 category successfully updated', 'rpbcalendar'));
	} else {
		rpbcalendar_admin_error_message(sprintf(__('Unable to update the category with ID %s', 'rpbcalendar'),
			htmlspecialchars($_POST['category_id'])));
	}
}

// Deal with delete category requests
function rpbcalendar_process_delete_category_request()
{
	global $wpdb;
	if(!(isset($_POST['mode']) && $_POST['mode']=='delete')) {
		return;
	}
	if(!rpbcalendar_validate_id($_POST['category_id'])) {
		return;
	}
	$retval = $wpdb->query(
		"DELETE FROM ".RPBCALENDAR_CATEGORY_TABLE." ".
		"WHERE category_id=".mysql_escape_string($_POST['category_id']).";"
	);
	if($retval==1) {
		rpbcalendar_admin_notification_message(__('1 category successfully deleted', 'rpbcalendar'));
	} else {
		rpbcalendar_admin_error_message(sprintf(__('Unable to delete the category with ID %s', 'rpbcalendar'),
			htmlspecialchars($_GET['delete'])));
	}
}

require_once(RPBCALENDAR_ABSPATH.'admin/column.class.php');
require_once(RPBCALENDAR_ABSPATH.'admin/colorcolumn.class.php');
require_once(RPBCALENDAR_ABSPATH.'admin/categorypreviewcolumn.class.php');
require_once(RPBCALENDAR_ABSPATH.'admin/field.class.php');
require_once(RPBCALENDAR_ABSPATH.'admin/form.class.php');

// Function to handle the management of categories
function rpbcalendar_manage_categories()
{
	echo '<div class="wrap">';
	//if(isset($_GET['edit'])) {
	//	echo '<h2>'.__('Edit the event category', 'rpbcalendar').'</h2>';
	//	include(RPBCALENDAR_ABSPATH.'admin/edit-category.php');
	//} elseif(isset($_GET['delete'])) {
	//	echo '<h2>'.__('Delete an event category', 'rpbcalendar').'</h2>';
	//	include(RPBCALENDAR_ABSPATH.'admin/delete-category.php');
	//} else {
		echo '<h2>'.__('Event categories', 'rpbcalendar').'</h2>';
		rpbcalendar_process_add_category_request();
		rpbcalendar_process_update_category_request();
		rpbcalendar_process_delete_category_request();
		//include(RPBCALENDAR_ABSPATH.'admin/manage-categories.php');

		// SQL
		$sql = 'SELECT category_id, category_name, category_text_color, category_background_color FROM '
			.RPBCALENDAR_CATEGORY_TABLE;

		// Columns
		$col_name            = new RpbcColumn('category_name', __('Name', 'rpbcalendar'));
		$col_name->row_title = true;
		$col_text_color       = new RpbcColorColumn('category_text_color'      , __('Text color'      , 'rpbcalendar'));
		$col_background_color = new RpbcColorColumn('category_background_color', __('Background color', 'rpbcalendar'));
		$col_preview = new RpbcCategoryPreviewColumn('category_preview', __('Preview', 'rpbcalendar'));

		// Fields
		$fld_name          = new RpbcField('category_name', __('Name', 'rpbcalendar'), 'text');
		$fld_name->options = array('maxlength'=>30);
		$fld_text_color          = new RpbcField('category_text_color'      , __('Text color'      , 'rpbcalendar'), 'text');
		$fld_text_color->options = array('maxlength'=>7);
		$fld_text_color->legend  = __('Use HTML hexa format (ex: #0000ff for blue or #ffff00 for yellow)', 'rpbcalendar');

		// Form
		$form = new RpbcForm('categoryform', $sql, 'rpbcalendar-categories', __('category', 'rpbcalendar'), 'category_id');
		$form->fields           = array($fld_name, $fld_text_color);
		$form->columns          = array($col_name, $col_text_color, $col_background_color, $col_preview);
		$form->default_order_by = 'category_name';
		$form->print_view();
		$form->print_edit(false);
		$form->print_delete();

	//}
	echo '</div>';
}

// Deal with add holiday requests
function rpbcalendar_process_add_holiday_request()
{
	global $wpdb;
	if(!(isset($_POST['mode']) && $_POST['mode']=='add')) {
		return;
	}
	$all_valid = true;
	$all_valid = rpbcalendar_validate_name($_POST['holiday_name' ]) && $all_valid;
	$all_valid = rpbcalendar_validate_date($_POST['holiday_begin']) && $all_valid;
	$all_valid = rpbcalendar_validate_date($_POST['holiday_end'  ]) && $all_valid;
	if(!$all_valid) {
		return;
	}
	$retval = $wpdb->query(
		"INSERT INTO ".RPBCALENDAR_HOLIDAY_TABLE." ".
		"(holiday_name, holiday_begin, holiday_end) ".
		"VALUES (".
			"'".mysql_escape_string($_POST['holiday_name' ])."', ".
			"'".mysql_escape_string($_POST['holiday_begin'])."', ".
			"'".mysql_escape_string($_POST['holiday_end'  ])."'".
		");"
	);
	if($retval==1) {
		rpbcalendar_admin_notification_message(__('1 holiday successfully added', 'rpbcalendar'));
	} else {
		rpbcalendar_admin_error_message(__('Unable to add the new holiday', 'rpbcalendar'));
	}
}

// Deal with update holiday requests
function rpbcalendar_process_update_holiday_request()
{
	global $wpdb;
	if(!(isset($_POST['mode']) && $_POST['mode']=='update')) {
		return;
	}
	$all_valid = true;
	$all_valid = rpbcalendar_validate_id  ($_POST['holiday_id'   ]) && $all_valid;
	$all_valid = rpbcalendar_validate_name($_POST['holiday_name' ]) && $all_valid;
	$all_valid = rpbcalendar_validate_date($_POST['holiday_begin']) && $all_valid;
	$all_valid = rpbcalendar_validate_date($_POST['holiday_end'  ]) && $all_valid;
	if(!$all_valid) {
		return;
	}
	$retval = $wpdb->query(
		"UPDATE ".RPBCALENDAR_HOLIDAY_TABLE." SET ".
			"holiday_name  = '".mysql_escape_string($_POST['holiday_name' ])."', ".
			"holiday_begin = '".mysql_escape_string($_POST['holiday_begin'])."', ".
			"holiday_end   = '".mysql_escape_string($_POST['holiday_end'  ])."' ".
		"WHERE holiday_id=".mysql_escape_string($_POST['holiday_id']).";"
	);
	if($retval==1) {
		rpbcalendar_admin_notification_message(__('1 holiday successfully updated', 'rpbcalendar'));
	} else {
		rpbcalendar_admin_error_message(sprintf(__('Unable to update the holiday with ID %s', 'rpbcalendar'),
			htmlspecialchars($_POST['holiday_id'])));
	}
}

// Deal with delete holiday requests
function rpbcalendar_process_delete_holiday_request()
{
	global $wpdb;
	if(!(isset($_POST['mode']) && $_POST['mode']=='delete')) {
		return;
	}
	if(!rpbcalendar_validate_id($_POST['holiday_id'])) {
		return;
	}
	$retval = $wpdb->query(
		"DELETE FROM ".RPBCALENDAR_HOLIDAY_TABLE." ".
		"WHERE holiday_id=".mysql_escape_string($_POST['holiday_id']).";"
	);
	if($retval==1) {
		rpbcalendar_admin_notification_message(__('1 holiday successfully deleted', 'rpbcalendar'));
	} else {
		rpbcalendar_admin_error_message(sprintf(__('Unable to delete the holiday with ID %s', 'rpbcalendar'),
			htmlspecialchars($_GET['delete'])));
	}
}

// Function to handle the management of holydays
function rpbcalendar_manage_holidays()
{
	echo '<div class="wrap">';
	if(isset($_GET['edit'])) {
		echo '<h2>'.__('Edit the holiday', 'rpbcalendar').'</h2>';
		include(RPBCALENDAR_ABSPATH.'admin/edit-holiday.php');
	} elseif(isset($_GET['delete'])) {
		echo '<h2>'.__('Delete a holiday', 'rpbcalendar').'</h2>';
		include(RPBCALENDAR_ABSPATH.'admin/delete-holiday.php');
	} else {
		echo '<h2>'.__('Holidays', 'rpbcalendar').'</h2>';
		rpbcalendar_process_add_holiday_request();
		rpbcalendar_process_update_holiday_request();
		rpbcalendar_process_delete_holiday_request();
		include(RPBCALENDAR_ABSPATH.'admin/manage-holidays.php');
	}
	echo '</div>';
}

// Function to handle the management of highdays
function rpbcalendar_manage_highdays()
{
	echo '<div class="wrap">';
	echo 'TODO';
	echo '</div>';
}

// Function to handle the management of events
function rpbcalendar_manage_events()
{
	echo '<div class="wrap">';
	echo '<h2>'.__('Events', 'rpbcalendar').'</h2>';
	include(RPBCALENDAR_ABSPATH.'admin/manage-events.php');
	echo '</div>';
}

?>
