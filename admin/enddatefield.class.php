<?php

require_once(RPBCALENDAR_ABSPATH.'admin/datefield.class.php');

// Specialized version of date field for specifying the end date of a day range
// in a form
class RpbcEndDateField extends RpbcDateField
{
	public $before_field_key;

	// Constructor
	public function __construct($key, $label, $before_field_key)
	{
		parent::__construct($key, $label);
		$this->before_field_key = $before_field_key;
	}

	// Validation
	protected function additional_validation(&$values)
	{
		if(!parent::additional_validation($values)) {
			return false;
		} elseif($values[$this->key] < $values[$this->before_field_key]) {
			rpbcalendar_admin_error_message(sprintf(
				__('End date unconsistent with respect to the corresponding day range: &quot;%s&quot;', 'rpbcalendar'),
				$this->label));
			return false;
		}
		return true;
	}

	// Prepare validation
	protected function prepare_validation(&$values)
	{
		if(!isset($values[$this->key]) || empty($values[$this->key])) {
			$values[$this->key] = $values[$this->before_field_key];
		}
	}
}

?>
