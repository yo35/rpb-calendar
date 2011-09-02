<?php

require_once(RPBCALENDAR_ABSPATH.'admin/field.class.php');

// Specialized version of RpbcField to display an int field
class RpbcIntField extends RpbcField
{
	public $maximum_value    = NULL; // Upper bound
	public $minium_value     = NULL; // Lower bound
	public $negative_allowed = true; // Whether negative values are accepted

	// Constructor
	public function __construct($key, $label)
	{
		parent::__construct($key, $label, 'text');
	}

	// Validation
	protected function additional_validation(&$values)
	{
		$matching_pattern = $this->negative_allowed ? '/^(-?[0-9]+)$/' : '/^([0-9]+)$/';
		if(!preg_match($matching_pattern, $values[$this->key], $matches)) {
			rpbcalendar_admin_error_message(sprintf(
				__('Badly formatted integer field: &quot;%s&quot;', 'rpbcalendar'), $this->label));
			return false;
		} elseif( (isset($this->maximum_value) && $matches[1]>$this->maximum_value) ||
			(isset($this->minimum_value) && $matches[1]<$this->minimum_value))
		{
			rpbcalendar_admin_error_message(sprintf(
				__('Value out of the valid range in the following field: &quot;%s&quot;', 'rpbcalendar'),
				$this->label));
			return false;
		}
		return true;
	}
}
