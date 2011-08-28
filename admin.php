<?php

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

// Register admin styles
function rpbcalendar_admin_print_styles()
{
	wp_enqueue_style('rpbcalendar-admin');
}

// Function to handle the management of categories
function rpbcalendar_manage_categories()
{
	// Includes
	require_once(RPBCALENDAR_ABSPATH.'admin/column.class.php');
	require_once(RPBCALENDAR_ABSPATH.'admin/colorcolumn.class.php');
	require_once(RPBCALENDAR_ABSPATH.'admin/categorycolumn.class.php');
	require_once(RPBCALENDAR_ABSPATH.'admin/field.class.php');
	require_once(RPBCALENDAR_ABSPATH.'admin/colorfield.class.php');
	require_once(RPBCALENDAR_ABSPATH.'admin/form.class.php');

	// SQL
	$sql = 'SELECT category_id, category_name, category_text_color, category_background_color FROM '
		.RPBCALENDAR_CATEGORY_TABLE;

	// Columns
	$col_name            = new RpbcColumn('category_name', __('Name', 'rpbcalendar'));
	$col_name->row_title = true;
	$col_text_color       = new RpbcColorColumn('category_text_color'      , __('Text color'      , 'rpbcalendar'));
	$col_background_color = new RpbcColorColumn('category_background_color', __('Background color', 'rpbcalendar'));
	$col_preview = new RpbcCategoryColumn('category_preview', __('Preview', 'rpbcalendar'));

	// Fields
	$fld_name          = new RpbcField('category_name', __('Name', 'rpbcalendar'), 'text');
	$fld_name->options = array('maxlength'=>30);
	$fld_text_color       = new RpbcColorField('category_text_color'      , __('Text color'      , 'rpbcalendar'));
	$fld_background_color = new RpbcColorField('category_background_color', __('Background color', 'rpbcalendar'));

	// Form
	$form = new RpbcForm('categoryform', RPBCALENDAR_CATEGORY_TABLE, $sql, 'rpbcalendar-categories',
		__('category', 'rpbcalendar'), 'category_id');
	$form->fields           = array($fld_name, $fld_text_color, $fld_background_color);
	$form->columns          = array($col_name, $col_text_color, $col_background_color, $col_preview);
	$form->default_order_by = 'category_name';

	// Process requests
	$form->process_all();

	// Printing
	$form->print_all(true,
		__('Event categories'        , 'rpbcalendar'),
		__('Add a new category'      , 'rpbcalendar'),
		__('Edit the event category' , 'rpbcalendar'),
		__('Delete an event category', 'rpbcalendar')
	);
}

// Function to handle the management of holydays
function rpbcalendar_manage_holidays()
{
	// Includes
	require_once(RPBCALENDAR_ABSPATH.'admin/column.class.php');
	require_once(RPBCALENDAR_ABSPATH.'admin/datecolumn.class.php');
	require_once(RPBCALENDAR_ABSPATH.'admin/field.class.php');
	require_once(RPBCALENDAR_ABSPATH.'admin/datefield.class.php');
	require_once(RPBCALENDAR_ABSPATH.'admin/form.class.php');

	// SQL
	$sql = 'SELECT holiday_id, holiday_name, holiday_begin, holiday_end FROM '.RPBCALENDAR_HOLIDAY_TABLE;

	// Columns
	$col_name            = new RpbcColumn('holiday_name', __('Name', 'rpbcalendar'));
	$col_name->row_title = true;
	$col_begin = new RpbcDateColumn('holiday_begin', __('First day', 'rpbcalendar'));
	$col_end   = new RpbcDateColumn('holiday_end'  , __('Last day' , 'rpbcalendar'));

	// Fields
	$fld_name          = new RpbcField('holiday_name', __('Name', 'rpbcalendar'), 'text');
	$fld_name->options = array('maxlength'=>30);
	$fld_begin = new RpbcDateField('holiday_begin', __('First day', 'rpbcalendar'), 'text');
	$fld_end   = new RpbcDateField('holiday_end'  , __('Last day' , 'rpbcalendar'), 'text');

	// Form
	$form = new RpbcForm('holidayform', RPBCALENDAR_HOLIDAY_TABLE, $sql, 'rpbcalendar-holidays',
		__('holiday', 'rpbcalendar'), 'holiday_id');
	$form->fields            = array($fld_name, $fld_begin, $fld_end);
	$form->columns           = array($col_name, $col_begin, $col_end);
	$form->default_order_by  = 'holiday_begin';
	$form->default_order_asc = false;

	// Process requests
	$form->process_all();

	// Printing
	$form->print_all(true,
		__('Holidays'         , 'rpbcalendar'),
		__('Add a new holiday', 'rpbcalendar'),
		__('Edit the holiday' , 'rpbcalendar'),
		__('Delete a holiday' , 'rpbcalendar')
	);
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
	// Includes
	require_once(RPBCALENDAR_ABSPATH.'admin/column.class.php');
	require_once(RPBCALENDAR_ABSPATH.'admin/categorycolumn.class.php');
	require_once(RPBCALENDAR_ABSPATH.'admin/linkcolumn.class.php');
	require_once(RPBCALENDAR_ABSPATH.'admin/field.class.php');
	require_once(RPBCALENDAR_ABSPATH.'admin/datefield.class.php');
	require_once(RPBCALENDAR_ABSPATH.'admin/form.class.php');

	// Specialized version of RpbcColumn to display the description field
	class RpbcEventDescColumn extends RpbcColumn
	{
		public function print_cell_content($elem)
		{
			echo rpbcalendar_format_event_desc($elem->event_desc);
		}
	}

	// Specialized version of RpbcColumn to display the date and time fields
	class RpbcEventDateColumn extends RpbcColumn
	{
		public function print_cell_content($elem)
		{
			$begin_date = date_i18n(get_option('date_format'), strtotime($elem->event_begin));
			$end_date   = date_i18n(get_option('date_format'), strtotime($elem->event_end  ));
			if($begin_date==$end_date) {
				echo $begin_date;
			} else {
				echo sprintf(__('%1$s<br />to %2$s', 'rpbcalendar'), $begin_date, $end_date);
			}
			if(isset($elem->event_time)) {
				$event_time = date_i18n(get_option('time_format'), strtotime($elem->event_time));
				echo '<br />'.sprintf(__('At %s', 'rpbcalendar'), $event_time);
			}
		}
	}

	// SQL
	global $wpdb;
	$sql = 'SELECT event_id, event_title, event_desc, event_begin, event_end, event_time, event_link, event_category, '.
		'wpu.display_name AS author_name, '.
		'rpbc.category_name AS category_name, rpbc.category_text_color AS category_text_color, '.
		'rpbc.category_background_color AS category_background_color '.
		'FROM '.RPBCALENDAR_EVENT_TABLE.' '.
		'LEFT OUTER JOIN '.$wpdb->users.' wpu ON event_author=wpu.ID '.
		'LEFT OUTER JOIN '.RPBCALENDAR_CATEGORY_TABLE.' rpbc ON event_category=rpbc.category_id';

	// Category list
	$categories = $wpdb->get_results(
		'SELECT category_id, category_name FROM '.RPBCALENDAR_CATEGORY_TABLE.' ORDER BY category_name;'
	);
	$choices = array(array('key'=>'', 'value'=>__('No category', 'rpbcalendar')));
	foreach($categories as $category) {
		array_push($choices, array('key'=>$category->category_id, 'value'=>$category->category_name));
	}

	// Columns
	$col_title            = new RpbcColumn('event_title', __('Title', 'rpbcalendar'));
	$col_title->row_title = true;
	$col_desc = new RpbcEventDescColumn('event_desc' , __('Description', 'rpbcalendar'));
	$col_date = new RpbcEventDateColumn('event_begin', __('Date'       , 'rpbcalendar'));
	$col_author = new RpbcColumn('author_name', __('Author', 'rpbcalendar'));
	$col_category = new RpbcCategoryColumn('category_name', __('Category', 'rpbcalendar'));
	$col_link = new RpbcLinkColumn('event_link', __('Link', 'rpbcalendar'));

	// Fields
	global $current_user;
	$fld_author                = new RpbcField('event_author', __('Author', 'rpbcalendar'), 'hidden');
	$fld_author->default_value = $current_user->ID;
	$fld_title          = new RpbcField('event_title', __('Title', 'rpbcalendar'), 'text');
	$fld_title->options = array('maxlength'=>30);
	$fld_desc              = new RpbcField('event_desc', __('Description', 'rpbcalendar'), 'textarea');
	$fld_desc->allow_empty = true;
	$fld_begin = new RpbcDateField('event_begin', __('Begin', 'rpbcalendar'), 'text');
	$fld_end   = new RpbcDateField('event_end'  , __('End'  , 'rpbcalendar'), 'text');
	$fld_category              = new RpbcField('event_category', __('Category', 'rpbcalendar'), 'select');
	$fld_category->options     = array('choices'=>$choices);
	$fld_category->allow_empty = array('choices'=>$choices);
	$fld_link              = new RpbcField('event_link', __('Link', 'rpbcalendar'), 'text');
	$fld_link->allow_empty = true;

	// Form
	$form = new RpbcForm('eventform', RPBCALENDAR_EVENT_TABLE, $sql, 'rpbcalendar',
		__('event', 'rpbcalendar'), 'event_id');
	$form->fields            = array($fld_author, $fld_title, $fld_begin, $fld_end, $fld_category, $fld_link, $fld_desc);
	$form->columns           = array($col_title, $col_desc, $col_date, $col_author, $col_category, $col_link);
	$form->default_order_by  = 'event_begin';
	$form->default_order_asc = false;

	// Process requests
	$form->process_all();

	// Printing
	$form->print_all(false,
		__('Events'         , 'rpbcalendar'),
		__('Add a new event', 'rpbcalendar'),
		__('Edit the event' , 'rpbcalendar'),
		__('Delete an event', 'rpbcalendar')
	);
}

// Function to adjust the plugin options
function rpbcalendar_manage_options()
{
	// Save user rights
	$permissions     = get_option('rpbcalendar_permissions', 'manage_options');
	$new_permissions = $_POST['permissions'];
	if(isset($new_permissions) && $permissions!=$new_permissions && (
		$new_permissions=='manage_options' ||
		$new_permissions=='edit_pages'     ||
		$new_permissions=='publish_posts'  ||
		$new_permissions=='edit_posts'     ||
		$new_permissions=='read'
	)) {
		$permissions = $new_permissions;
		update_option('rpbcalendar-permissions', $permissions);
		rpbcalendar_admin_notification_message(__('Option &quot;Permissions&quot; saved', 'rpbcalendar'));
	}

	// Save display author
	$display_author     = get_option('rpbcalendar-display-author', 'true');
	$new_display_author = isset($_POST['display-author']) ? 'true' : 'false';
	if(isset($_POST['rpbcalendar-options']) && $display_author!=$new_display_author) {
		$display_author = $new_display_author;
		update_option('rpbcalendar-display-author', $display_author);
		rpbcalendar_admin_notification_message(__('Option &quot;Display author&quot; saved', 'rpbcalendar'));
	}

	// Save display category
	$display_category     = get_option('rpbcalendar-display-category', 'true');
	$new_display_category = isset($_POST['display-category']) ? 'true' : 'false';
	if(isset($_POST['rpbcalendar-options']) && $display_category!=$new_display_category) {
		$display_category = $new_display_category;
		update_option('rpbcalendar-display-category', $display_category);
		rpbcalendar_admin_notification_message(__('Option &quot;Display category&quot; saved', 'rpbcalendar'));
	}

	// Begin of form
	$target_link = site_url().'/wp-admin/admin.php?page=rpbcalendar-options';
	echo '<div class="wrap">';
	echo '<h2>'.__('Calendar options', 'rpbcalendar').'</h2>';
	echo '<form class="form-wrap" name="rpbcalendaroptions" method="post" action="'.$target_link.'">';
	echo '<table class="form-table"><tbody>';
	echo '<input type="hidden" name="rpbcalendar-options" value="1" />';

	// User rights
	echo '<tr class="form-field"><th scope="row">';
	echo '<label for="permissions">'.__('Permissions', 'rpbcalendar').'</label>';
	echo '</th><td>';
	echo '<select name="permissions">';
	echo '<option value="manage_options"'.($permissions=='manage_options'?' selected="1"':'').'>'.__('Administrator', 'rpbcalendar').'</option>';
	echo '<option value="edit_pages"'    .($permissions=='edit_pages'    ?' selected="1"':'').'>'.__('Editor'       , 'rpbcalendar').'</option>';
	echo '<option value="publish_posts"' .($permissions=='publish_posts' ?' selected="1"':'').'>'.__('Author'       , 'rpbcalendar').'</option>';
	echo '<option value="edit_posts"'    .($permissions=='edit_posts'    ?' selected="1"':'').'>'.__('Contributor'  , 'rpbcalendar').'</option>';
	echo '<option value="read"'          .($permissions=='read'          ?' selected="1"':'').'>'.__('Subscriber'   , 'rpbcalendar').'</option>';
	echo '</select>';
	echo '<p>'.__('Choose the lowest user group that is allowed to manage events and holidays', 'rpbcalendar').'</p>';
	echo '</td></tr>';

	// Display author name
	echo '<tr class="form-field"><th scope="row">';
	echo '<label for="display-author">'.__('Author name', 'rpbcalendar').'</label>';
	echo '</th><td>';
	echo '<input type="checkbox" name="display-author" '.($display_author=='true'?'checked="1"':'').' />';
	echo '<p>'.__('Display the name of the author name on events', 'rpbcalendar').'</p>';
	echo '</td></tr>';

	// Use categories
	echo '<tr class="form-field"><th scope="row">';
	echo '<label for="display-category">'.__('Categories', 'rpbcalendar').'</label>';
	echo '</th><td>';
	echo '<input type="checkbox" name="display-category" '.($display_category=='true'?'checked="1"':'').' />';
	echo '<p>'.__('Use event categories', 'rpbcalendar').'</p>';
	echo '</td></tr>';

	// End of form
	echo '</tbody></table>';
	echo '<p class="submit"><input class="button-primary" type="submit" value="'
		.__('Save changes', 'rpbcalendar').'" /></p>';
	echo '</form>';
	echo '</div>';
}

?>
