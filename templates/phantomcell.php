<?php

	// Begin of line
	if($current_column==0) {
		echo '<tr>';
	}

	// Actual cell
	echo '<td class="rpbcalendar-phantom-cell">&nbsp;</td>';

	// End of line
	if($current_column==6) {
		echo '</tr>';
	}

?>
