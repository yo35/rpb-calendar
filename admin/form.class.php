<?php

// Form object for editing category / event / holiday ...
class RpbcForm
{
	public $name            ; // Name of the form
	public $sql             ; // SQL SELECT statement (without the ordering part)
	public $base_link       ; // Link to the current admin page
	public $elem_name       ; // Type name of the editted element
	public $id_field_key    ; // SQL key of the ID field
	public $fields = array(); // List of fields


	// Constructor
	public function __construct($name, $sql, $admin_page_key, $elem_name, $id_field_key)
	{
		$this->name         = $name;
		$this->sql          = $sql ;
		$this->elem_name    = $elem_name;
		$this->base_link    = site_url().'/wp-admin/admin.php?page='.$admin_page_key;
		$this->id_field_key = $id_field_key;
	}

	// Rendering
	public function print_form($in_table)
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

	// Retrieve the editted element
	private function retrieve_element($element_id)
	{
		global $wpdb;
		$full_sql = $this->sql.' WHERE '.$this->id_field_key.'='.mysql_escape_string($element_id).' LIMIT 1;';
		$elem     = $wpdb->get_row($full_sql);
		if(!isset($elem)) {
			rpbcalendar_admin_error_message(sprintf(__('Unable to retrieve the %1$s with ID %2$s', 'rpbcalendar'),
				$this->elem_name, htmlspecialchars($element_id)), $this->base_link);
		}
		return $elem;
	}
}

?>
