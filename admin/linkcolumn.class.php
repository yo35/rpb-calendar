<?php

require_once(RPBCALENDAR_ABSPATH.'admin/column.class.php');

// Specialized version of RpbcColumn to display a link field
class RpbcLinkColumn extends RpbcColumn
{
	public function print_cell_content($elem)
	{
		$field = $this->key;
		$data  = $elem->$field;
		if(isset($data)) {
			$data = htmlspecialchars($data);
			echo '<a href="'.$data.'" target="_blank">'.$data.'</a>';
		} else {
			echo 'N/A';
		}
	}
}

?>
