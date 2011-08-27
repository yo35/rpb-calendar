<?php

require_once(RPBCALENDAR_ABSPATH.'admin/column.class.php');

// Specialized version of RpbcColumn to display color field
class RpbcCategoryPreviewColumn extends RpbcColumn
{
	public function print_cell_content($elem)
	{
		echo '<div class="rpbcalendar-category-preview" style="background-color: '
			.htmlspecialchars($elem->category_background_color).'; color: '
			.htmlspecialchars($elem->category_text_color      ).';">'
			.htmlspecialchars($elem->category_name            ).'</div>';
	}

	public function sql_sort_code($order_asc)
	{
		return 'ORDER BY category_name '.($order_asc ? 'ASC' : 'DESC');
	}
}

?>
