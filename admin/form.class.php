<?php

// Form object for editing or displaying a category / an event / a holiday ...
class RpbcForm
{
	public $name                    ; // Name of the form
	public $table                   ; // SQL table used for INSERT/UPDATE/DELETE requests
	public $sql                     ; // SQL SELECT statement (without the ordering part)
	public $base_link               ; // Link to the current admin page
	public $elem_name               ; // Type name of the editted element
	public $id_field_key            ; // SQL key of the ID field
	public $fields  = array()       ; // List of fields
	public $columns = array()       ; // List of columns
	public $default_order_by  = NULL; // Default column used to sort the table
	public $default_order_asc = true; // Default sort direction


	// Constructor
	public function __construct($name, $table, $sql, $admin_page_key, $elem_name, $id_field_key)
	{
		$this->name         = $name;
		$this->table        = $table;
		$this->sql          = $sql;
		$this->elem_name    = $elem_name;
		$this->base_link    = site_url().'/wp-admin/admin.php?page='.$admin_page_key;
		$this->id_field_key = $id_field_key;
	}


	// Rendering all
	public function print_all($general_title, $add_title, $edit_title, $delete_title)
	{
		echo '<div class="wrap">';
		if(isset($_GET['edit'])) {
			echo '<h2>'.$edit_title.'</h2>';
			$this->print_edit(true);
		} elseif(isset($_GET['delete'])) {
			echo '<h2>'.$delete_title.'</h2>';
			$this->print_delete();
		} else {
			echo '<h2>'.$general_title.'</h2>';
			echo '<div id="col-container"><div id="col-right"><div class="col-wrap">';
			$this->print_view();
			echo '</div></div><div id="col-left"><div class="col-wrap">';
			echo '<h3>'.$add_title.'</h3>';
			$this->print_edit(false);
			echo '</div></div></div>';
		}
		echo '</div>';
	}


	// Rendering edit/add form
	public function print_edit($in_table)
	{
		// Retrieve the editted object
		$elem = NULL;
		if(isset($_GET['edit'])) {
			$elem = $this->retrieve_element($_GET['edit']);
			if(!isset($elem)) {
				return;
			}
		}

		// Prefix
		$form_class = $in_table ? '' : 'class="form-wrap" ';
		echo '<form '.$form_class.'name="'.$this->name.'" method="post" action="'.$this->base_link.'">';
		if(isset($elem)) {
			$id_field = $this->id_field_key;
			echo '<input type="hidden" name="mode" value="update" />';
			echo '<input type="hidden" name="'.$id_field.'" value="'.htmlspecialchars($elem->$id_field).'" />';
		} else {
			echo '<input type="hidden" name="mode" value="add" />';
		}
		if($in_table) {
			echo '<table class="form-table"><tbody>';
		}

		// Fields
		foreach($this->fields as $field) {
			$field->print_field($in_table, $elem);
		}

		// Suffix
		if($in_table) {
			echo '</tbody></table>';
		}
		$submit_class = $in_table ? 'button-primary' : 'button';
		$submit_label = isset($elem) ? __('Update', 'rpbcalendar') : __('Add', 'rpbcalendar');
		echo '<p class="submit"><input class="'.$submit_class.'" type="submit" value="'
			.$submit_label.'" /></p>';
		echo '</form>';
	}

	// Rendering delete
	public function print_delete()
	{
		// Retrieve the deleted object
		if(!isset($_GET['delete'])) {
			rpbcalendar_admin_error_message(sprintf(__('No %1$s ID provided', 'rpbcalendar'),
				$this->elem_name), $this->base_link);
			return;
		}
		$elem = $this->retrieve_element($_GET['delete']);
		if(!isset($elem)) {
			return;
		}

		// Print the confirmation form
		$id_field = $this->id_field_key;
		echo '<form name="'.$this->name.'" method="post" action="'.$this->base_link.'">';
		echo '<input type="hidden" name="mode" value="delete" />';
		echo '<input type="hidden" name="'.$id_field.'" value="'.htmlspecialchars($elem->$id_field).'" />';
		echo '<p>'.sprintf(__('Are you sure that you want to delete the %1$s with ID %2$s?', 'rpbcalendar'),
			$this->elem_name, htmlspecialchars($_GET['delete'])).'</p>';
		echo '<p class="submit"><input class="button-primary" type="submit" value="'
			.__('Delete', 'rpbcalendar').'" /></p>';
		echo '</form>';
	}

	// Rendering function
	public function print_view()
	{
		// Compose the SQL query
		$sort_column = $this->search_sort_column();
		$sort_sql    = '';
		$sort_key    = NULL;
		$sort_asc    = $this->order_asc();
		if(isset($sort_column)) {
			$sort_key = $sort_column->key;
			$sort_sql = ' '.$sort_column->sql_sort_code($sort_asc);
		}
		$full_sql = $this->sql . $sort_sql . ';';

		// Retrieve the data
		global $wpdb;
		$elems = $wpdb->get_results($full_sql);

		// Printing
		echo '<table class="wp-list-table widefat fixed"><thead>';
		$this->print_headers($sort_key, $sort_asc);
		echo '</thead><tfoot>';
		$this->print_headers($sort_key, $sort_asc);
		echo '</tfoot><tbody>';
		$this->print_records($elems);
		echo '</tbody></table>';
	}

	// Rendering headers
	private function print_headers($sort_key, $sort_asc)
	{
		echo '<tr>';
		foreach($this->columns as $column) {
			$sorting_class = ($column->sortable ? 'sortable desc' : '');
			$link_suffix   = '&order=asc';
			if($column->key==$sort_key) {
				$sorting_class = 'sorted '.($sort_asc ? 'asc' : 'desc');
				$link_suffix   = '&order='.($sort_asc ? 'desc' : 'asc');
			}
			echo '<th class="'.$sorting_class.'" scope="col">';
			if($column->sortable) {
				echo '<a href="'.$this->base_link.'&orderby='.$column->key.$link_suffix.'">';
				echo '<span>'.$column->label.'</span><span class="sorting-indicator"></span>';
				echo '</a>';
			} else {
				echo $column->label;
			}
			echo '</th>';
		}
		echo '</tr>';
	}

	// Rendering records
	private function print_records($elems)
	{
		// Special case for empty lists
		if(empty($elems)) {
			echo '<tr><td colspan="'.count($this->columns).'">';
			echo sprintf(__('No %s found.', 'rpbcalendar'), $this->elem_name);
			echo '</td></tr>';
			return;
		}

		// General case
		foreach($elems as $elem) {
			echo '<tr>';
			foreach($this->columns as $column) {
				echo '<td>';
				if($column->row_title) {
					$id_field = $this->id_field_key;
					$id_value = htmlspecialchars($elem->$id_field);
					echo '<span class="row-title">';
					$column->print_cell_content($elem);
					echo '</span><br /><div class=row-actions>';
					echo '<a href="'.$this->base_link.'&edit='.$id_value.'">'.__('Edit').'</a> | ';
					echo '<a href="'.$this->base_link.'&delete='.$id_value.'">'.__('Delete').'</a>';
					echo '</div>';
				} else {
					$column->print_cell_content($elem);
				}
				echo '</td>';
			}
			echo '</tr>';
		}
	}

	// Retrieve the editted element
	private function retrieve_element($element_id)
	{
		global $wpdb;
		$full_sql = $this->sql.' WHERE '.$this->id_field_key.'='.mysql_escape_string($element_id).' LIMIT 1;';
		$elem     = $wpdb->get_row($full_sql);
		if(!isset($elem)) {
			rpbcalendar_admin_error_message(sprintf(
				__('Unable to retrieve the %1$s with ID &quot;%2$s&quot;', 'rpbcalendar'),
				$this->elem_name, htmlspecialchars($element_id)), $this->base_link);
		}
		return $elem;
	}

	// Search the column to use to sort the table
	private function search_sort_column()
	{
		$order_by = $_GET['orderby'];
		$default_column = NULL;
		foreach($this->columns as $column) {
			if($column->key==$order_by) {
				return $column;
			} elseif($column->key==$this->default_order_by) {
				$default_column = $column;
			}
		}
		return $default_column;
	}

	// Retrieve the order asc argument
	private function order_asc()
	{
		if(isset($_GET['order']) && $_GET['order']=='desc') {
			return false;
		} elseif(isset($_GET['order']) && $_GET['order']=='asc') {
			return true;
		} else {
			return $this->default_order_asc;
		}
	}

	// Validate an id string
	private function validate_id($element_id)
	{
		if(!isset($element_id) || empty($element_id) || !is_numeric($element_id)) {
			rpbcalendar_admin_error_message(
				__('Unspecified or badly formatted ID string', 'rpbcalendar'));
			return false;
		}
		return true;
	}

	// Process all requests
	public function process_all()
	{
		$this->process_insert();
		$this->process_update();
		$this->process_delete();
	}

	// Process an insert request
	public function process_insert()
	{
		// Checks
		if(!(isset($_POST['mode']) && $_POST['mode']=='add')) {
			return;
		}
		$field_part = '';
		$value_part = '';
		foreach($this->fields as $field) {
			if(!$field->validation($_POST)) {
				return;
			}
			$field_part .= (empty($field_part) ? '' : ', ') . $field->key;
			$value_part .= (empty($value_part) ? '' : ', ') . "'".mysql_escape_string($_POST[$field->key])."'";
		}

		// Execute the request
		global $wpdb;
		$retval = $wpdb->query(
			'INSERT INTO '.$this->table.' ('.$field_part.') VALUES ('.$value_part.');'
		);
		if($retval==1) {
			rpbcalendar_admin_notification_message(sprintf(__('1 %s successfully added', 'rpbcalendar'),
				$this->elem_name));
		}
	}

	// Process an update request
	public function process_update()
	{
		// Checks
		if(!(isset($_POST['mode']) && $_POST['mode']=='update')) {
			return;
		}
		if(!$this->validate_id($_POST[$this->id_field_key])) {
			return;
		}
		$where_part = $this->id_field_key.'='.mysql_escape_string($_POST[$this->id_field_key]);
		$set_part   = '';
		foreach($this->fields as $field) {
			if(!$field->validation($_POST)) {
				return;
			}
			$set_part .= (empty($set_part) ? '' : ', ') . $field->key . '=' .
				"'".mysql_escape_string($_POST[$field->key])."'";
		}

		// Execute the request
		global $wpdb;
		$retval = $wpdb->query(
			'UPDATE '.$this->table.' SET '.$set_part.' WHERE '.$where_part.';'
		);
		if($retval==1) {
			rpbcalendar_admin_notification_message(sprintf(__('1 %s successfully updated', 'rpbcalendar'),
				$this->elem_name));
		}
	}

	// Process a delete request
	public function process_delete()
	{
		// Checks
		if(!(isset($_POST['mode']) && $_POST['mode']=='delete')) {
			return;
		}
		if(!$this->validate_id($_POST[$this->id_field_key])) {
			return;
		}
		$where_part = $this->id_field_key.'='.mysql_escape_string($_POST[$this->id_field_key]);

		// Execute the request
		global $wpdb;
		$retval = $wpdb->query(
			'DELETE FROM '.$this->table.' WHERE '.$where_part.';'
		);
		if($retval==1) {
			rpbcalendar_admin_notification_message(sprintf(__('1 %s successfully deleted', 'rpbcalendar'),
				$this->elem_name));
		}
	}
}

?>