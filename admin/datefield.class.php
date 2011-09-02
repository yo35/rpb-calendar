<?php

require_once(RPBCALENDAR_ABSPATH.'admin/field.class.php');

// Specialized version of RpbcField to display a date field
class RpbcDateField extends RpbcField
{
	// Constructor
	public function __construct($key, $label)
	{
		parent::__construct($key, $label, 'text');
		$this->legend        = __('Use the following format: yyyy-mm-dd', 'rpbcalendar');
		$this->options       = array('maxlength'=>10);
		$this->default_value = '';
		$this->legend .=
			'<br />';
	}

	// Format the value coming out of the database
	protected function retrieve_value($elem)
	{
		$field = $this->key;
		$data  = $elem->$field;
		return isset($data) ? date('Y-m-d', strtotime($data)) : '';
	}

	// Rendering the actual field
	protected function print_actual_field($value)
	{
		// Print the input field
		echo '<input type="text" ';
		$this->print_option_attributes();
		echo ' value="'.$value.'" />';

		// Button to select the date
		if(isset($this->form)) {
			$link_id        = $this->key.'-link';
			$java_statement = "return rpbcCalendarPopup(document.forms['".$this->form."'].".$this->key.", '".$link_id."');";
			$select_label   = __('Select', 'rpbcalendar');
			echo '<br /><a href="#" id="'.$link_id.'" onClick="'.$java_statement.'">'.$select_label.'</a>';
		}
	}

	// Validation
	protected function additional_validation(&$values)
	{
		if(!preg_match('/([0-9]{4})-([0-9]{2})-([0-9]{2})/', $values[$this->key], $matches)) {
			rpbcalendar_admin_error_message(sprintf(
				__('Badly formatted date field: &quot;%s&quot;', 'rpbcalendar'), $this->label));
			return false;
		} elseif( !($matches[2]>=1 && $matches[2]<=12 && $matches[3]>=1 &&
			$matches[3]<=date('t', mktime(0, 0, 0, $matches[2], 1, $matches[1]))) )
		{
			rpbcalendar_admin_error_message(sprintf(
				__('Wrong date value in the date field: &quot;%s&quot;', 'rpbcalendar'), $this->label));
			return false;
		}
		return true;
	}
}

?>
