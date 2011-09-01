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
	}

	// Validation
	public function additional_validation(&$values)
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
