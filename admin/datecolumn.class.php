<?php

require_once(RPBCALENDAR_ABSPATH.'admin/column.class.php');

// Specialized version of RpbcColumn to display date field
class RpbcDateColumn extends RpbcColumn
{
	public function print_cell_content($elem)
	{
		$field = $this->key;
		$data  = $elem->$field;
		echo isset($data) ? date_i18n(get_option('date_format'), strtotime($data)) : 'N/A';
	}
}

?>
