<?php

require_once(RPBCALENDAR_ABSPATH.'admin/field.class.php');

// Specialized version of RpbcField to display a time field
class RpbcTimeField extends RpbcField
{
	// Constructor
	public function __construct($key, $label)
	{
		parent::__construct($key, $label, 'text');
		$this->legend        = __('Use the following format: hh:mm', 'rpbcalendar');
		$this->options       = array('maxlength'=>5);
		$this->default_value = '';
	}

	// Format the value coming out of the database
	protected function retrieve_value($elem)
	{
		$field = $this->key;
		$data  = $elem->$field;
		return isset($data) ? date('H:i', strtotime($data)) : '';
	}

	// Validation
	protected function additional_validation(&$values)
	{
		if(!preg_match('/([0-9]{2}):([0-9]{2})/', $values[$this->key], $matches)) {
			rpbcalendar_admin_error_message(sprintf(
				__('Badly formatted time field: &quot;%s&quot;', 'rpbcalendar'), $this->label));
			return false;
		} elseif( !($matches[1]<24 && $matches[2]<60) ) {
			rpbcalendar_admin_error_message(sprintf(
				__('Wrong time value in the time field: &quot;%s&quot;', 'rpbcalendar'), $this->label));
			return false;
		}
		return true;
	}
}

?>
