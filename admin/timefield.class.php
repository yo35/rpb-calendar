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

	// Validation
	public function additional_validation($values)
	{
		if(!( preg_match('/[0-9]{2}:[0-9]{2}/', $values[$this->key]) || ($this->allow_empty && empty($values[$this->key])) )) {
			rpbcalendar_admin_error_message(sprintf(
				__('Badly formatted time field: &quot;%s&quot;', 'rpbcalendar'), $this->label));
			return false;
		}
		return true;
	}
}

?>
