<?php

// Table object for displaying lists of events / categories / holydays...
class RpbcTable
{
	public $sql;                      // SQL SELECT statement (without the ordering part)
	public $base_link;                // Link to the current admin page
	public $columns = array();        // List of columns
	public $default_order_by = NULL;  // Default column used to sort the table
	public $default_order_asc = true; // Default sort direction


	// Constructor
	public function __construct($sql, $admin_page_key)
	{
		$this->sql = $sql;
		$this->base_link = site_url().'/wp-admin/admin.php?page='.$admin_page_key;
	}

	// Rendering function
	public function print_table()
	{
		// Compose the SQL query
		$sort_column = $this->search_sort_column();
		$sort_sql    = '';
		$sort_key    = NULL;
		$sort_asc    = $this->order_asc();
		if(isset($sort_column)) {
			$sort_key = $sort_column->key;
			$sort_sql = ' '.$sort_column->sql_sort_code($sort_asc);
		}
		$full_sql = $this->sql . $sort_sql . ';';

		// Retrieve the data
		global $wpdb;
		$elems = $wpdb->get_results($full_sql);

		// Printing
		echo '<table class="wp-list-table widefat fixed"><thead>';
		$this->print_headers($sort_key, $sort_asc);
		echo '</thead><tfoot>';
		$this->print_headers($sort_key, $sort_asc);
		echo '</tfoot><tbody>';
		$this->print_records($elems);
		echo '</tbody></table>';
	}

	// Rendering headers
	private function print_headers($sort_key, $sort_asc)
	{
		echo '<tr>';
		foreach($this->columns as $column) {
			$sorting_class = ($column->sortable ? 'sortable desc' : '');
			$link_suffix   = '&order=asc';
			if($column->key==$sort_key) {
				$sorting_class = 'sorted '.($sort_asc ? 'asc' : 'desc');
				$link_suffix   = '&order='.($sort_asc ? 'desc' : 'asc');
			}
			echo '<th class="'.$sorting_class.'" scope="col">';
			if($column->sortable) {
				echo '<a href="'.$this->base_link.'&orderby='.$column->key.$link_suffix.'">';
				echo '<span>'.$column->label.'</span><span class="sorting-indicator"></span>';
				echo '</a>';
			} else {
				echo $column->label;
			}
			echo '</th>';
		}
		echo '</tr>';
	}

	// Rendering records
	private function print_records($elems)
	{
		// Special case for empty lists
		if(empty($elems)) {
			echo '<tr><td colspan="'.count($this->columns).'">';
			echo __('No element found.', 'rpbcalendar');
			echo '</td></tr>';
			return;
		}

		// General case
		foreach($elems as $elem) {
			echo '<tr>';
			foreach($this->columns as $column) {
				echo '<td>';
				if($column->row_title) {
					echo '<span class="row-title">';
					$column->print_cell_content($elem);
					echo '</span>';
				} else {
					$column->print_cell_content($elem);
				}
				echo '</td>';
			}
			echo '</tr>';
		}
	}

	// Search the column to use to sort the table
	private function search_sort_column()
	{
		$order_by = $_GET['orderby'];
		$default_column = NULL;
		foreach($this->columns as $column) {
			if($column->key==$order_by) {
				return $column;
			} elseif($column->key==$this->default_order_by) {
				$default_column = $column;
			}
		}
		return $default_column;
	}

	// Retrieve the order asc argument
	private function order_asc()
	{
		if(isset($_GET['order']) && $_GET['order']=='desc') {
			return false;
		} elseif(isset($_GET['order']) && $_GET['order']=='asc') {
			return true;
		} else {
			return $this->default_order_asc;
		}
	}
}

?>
