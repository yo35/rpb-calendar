<?php

require_once(RPBCALENDAR_ABSPATH.'admin/column.class.php');

// Specialized version of RpbcColumn to display color field
class RpbcColorColumn extends RpbcColumn
{
	public function print_cell_content($elem)
	{
		$field = $this->key;
		$data  = htmlspecialchars($elem->$field);
		echo '<div class="rpbcalendar-color-caption" style="background-color: '.$data.';"></div>'.$data;
	}
}

?>
