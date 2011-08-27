<?php

// Column object for table of events / categories / holydays...
class RpbcColumn
{
	public $key  ; // Key to identify the column (often the name of the corresponding field)
	public $label; // Label of the column in the header
	public $row_title = false; // true if the column display the name or title field
	public $sortable = true;   // true if sorting is allowed


	// Constructor
	public function __construct($key, $label)
	{
		$this->key   = $key  ;
		$this->label = $label;
	}

	// Rendering function
	public function print_cell_content($elem)
	{
		$field = $this->key;
		echo htmlspecialchars($elem->$field);
	}

	// SQL sorting code generator
	public function sql_sort_code($order_asc)
	{
		return 'ORDER BY '.$this->key.' '.($order_asc ? 'ASC' : 'DESC');
	}
}

?>
