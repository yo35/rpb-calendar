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
	$sql = 'SELECT event_id, event_title, event_desc, event_begin, event_end, event_time, event_link, '.
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

?>
