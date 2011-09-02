<?php

require_once(RPBCALENDAR_ABSPATH.'admin/field.class.php');

// Specialized version of RpbcField to display a color field
class RpbcColorField extends RpbcField
{
	// Constructor
	public function __construct($key, $label)
	{
		parent::__construct($key, $label, 'text');
		$this->legend        = __('Use HTML hexa format (ex: #0000ff for blue or #ffff00 for yellow)', 'rpbcalendar');
		$this->options       = array('maxlength'=>7);
		$this->default_value = '#';
	}

	// Validation
	protected function additional_validation(&$values)
	{
		if(!preg_match('/^#[0-9a-fA-F]{6}$/', $values[$this->key])) {
			rpbcalendar_admin_error_message(sprintf(
				__('Badly formatted color field: &quot;%s&quot;', 'rpbcalendar'), $this->label));
			return false;
		}
		return true;
	}
}

?>
