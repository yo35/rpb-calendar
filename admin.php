<?php

// Hooks
add_action('admin_init', 'rpbcalendar_admin_init');
add_action('admin_menu', 'rpbcalendar_build_menu');

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
	wp_register_style('rpbcalendar-admin', WP_PLUGIN_URL.'/calendar/css/admin.css');
}

// Build the administration menu
function rpbcalendar_build_menu()
{
	// Set admin as the only one who can use RpbCalendar for security
	$allowed_group = 'manage_options';

	// Main menu
	$page = add_menu_page(__('Calendar', 'rpbcalendar'), __('Calendar', 'rpbcalendar'),
		$allowed_group, 'rpbcalendar', 'rpbcalendar_manage_events');
	add_action('admin_print_styles-'. $page, 'rpbcalendar_admin_print_styles');

	// Event page
	$page = add_submenu_page('rpbcalendar', __('Manage events', 'rpbcalendar'), __('Manage event', 'rpbcalendar'),
		$allowed_group, 'rpbcalendar', 'rpbcalendar_manage_events');
	add_action('admin_print_styles-'. $page, 'rpbcalendar_admin_print_styles');

	// Holiday page
	$page = add_submenu_page('rpbcalendar', __('Manage holidays', 'rpbcalendar'), __('Manage holidays', 'rpbcalendar'),
		$allowed_group, 'rpbcalendar-holidays', 'rpbcalendar_manage_holidays');
	add_action('admin_print_styles-'. $page, 'rpbcalendar_admin_print_styles');

	// Category page
	$page = add_submenu_page('rpbcalendar', __('Manage categories', 'rpbcalendar'), __('Manage categories', 'rpbcalendar'),
		'manage_options', 'rpbcalendar-categories', 'rpbcalendar_manage_categories');
	add_action('admin_print_styles-'. $page, 'rpbcalendar_admin_print_styles');
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
	require_once(RPBCALENDAR_ABSPATH.'admin/field.class.php');
	require_once(RPBCALENDAR_ABSPATH.'admin/colorfield.class.php');
	require_once(RPBCALENDAR_ABSPATH.'admin/form.class.php');

	// Specialized version of RpbcColumn to display color field
	class RpbcCategoryPreviewColumn extends RpbcColumn
	{
		public function print_cell_content($elem)
		{
			echo '<div class="rpbcalendar-category-preview" style="background-color: '
				.htmlspecialchars($elem->category_background_color).'; color: '
				.htmlspecialchars($elem->category_text_color      ).';">'
				.htmlspecialchars($elem->category_name            ).'</div>';
		}

		public function sql_sort_code($order_asc)
		{
			return 'ORDER BY category_name '.($order_asc ? 'ASC' : 'DESC');
		}
	}

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
	$form->print_all(
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
	$form->print_all(
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
	require_once(RPBCALENDAR_ABSPATH.'admin/field.class.php');
	require_once(RPBCALENDAR_ABSPATH.'admin/datefield.class.php');
	require_once(RPBCALENDAR_ABSPATH.'admin/form.class.php');

	// SQL
	$sql = 'SELECT event_id, event_title, event_desc, event_begin, event_end, event_time, '.
		'event_link, event_category, event_author FROM '.RPBCALENDAR_EVENT_TABLE;

	// Columns
	$col_title            = new RpbcColumn('event_title', __('Title', 'rpbcalendar'));
	$col_title->row_title = true;
	$col_desc = new RpbcColumn('event_desc', __('Description', 'rpbcalendar'));

	// Fields
	$fld_title          = new RpbcField('event_title', __('Title', 'rpbcalendar'), 'text');
	$fld_title->options = array('maxlength'=>30);
	$fld_desc              = new RpbcField('event_desc', __('Description', 'rpbcalendar'), 'textarea');
	$fld_desc->allow_empty = true;
	$fld_begin = new RpbcDateField('event_begin', __('Begin', 'rpbcalendar'), 'text');
	$fld_end   = new RpbcDateField('event_end'  , __('End'  , 'rpbcalendar'), 'text');

	// Form
	$form = new RpbcForm('eventform', RPBCALENDAR_EVENT_TABLE, $sql, 'rpbcalendar',
		__('event', 'rpbcalendar'), 'event_id');
	$form->fields            = array($fld_title, $fld_begin, $fld_end, $fld_desc);
	$form->columns           = array($col_title, $col_desc);
	$form->default_order_by  = 'event_begin';
	$form->default_order_asc = false;

	// Process requests
	$form->process_all();

	// Printing
	$form->print_all(
		__('Events'         , 'rpbcalendar'),
		__('Add a new event', 'rpbcalendar'),
		__('Edit the event' , 'rpbcalendar'),
		__('Delete an event', 'rpbcalendar')
	);
}

?>
