<?php

	// WP database
	global $wpdb;

	// Table ordering
	if(isset($_GET['orderby']) && ($_GET['orderby']=='holiday_name' ||
		$_GET['orderby']=='holiday_begin' || $_GET['orderby']=='holiday_end'))
	{
		$orderby = $_GET['orderby'];
	} else {
		$orderby = 'holiday_begin';
	}

	// Ordering direction
	if(isset($_GET['order']) && $_GET['order']=='desc') {
		$orderasc = false;
	} else {
		$orderasc = true;
	}

	// Link to the current page
	$base_link = site_url().'/wp-admin/admin.php?page=rpbcalendar-holidays';

	// Date format
	$date_format = get_option('date_format');

	// Retrieve categories
	$holidays = $wpdb->get_results(
		"SELECT holiday_id, holiday_name, holiday_begin, holiday_end ".
		"FROM ".RPBCALENDAR_HOLIDAY_TABLE." ".
		"ORDER BY ".$orderby." ".($orderasc ? "ASC" : "DESC").";"
	);

	// Setup column headers
	$column_headers = array(
		'holiday_name' => array(
			'label' => __('Name', 'rpbcalendar'),
			'sort'  => 'sortable desc',
			'link'  => '&order=asc'
		),
		'holiday_begin' => array(
			'label' => __('First day', 'rpbcalendar'),
			'sort'  => 'sortable desc',
			'link'  => '&order=asc'
		),
		'holiday_end' => array(
			'label' => __('Last day', 'rpbcalendar'),
			'sort'  => 'sortable desc',
			'link'  => '&order=asc'
		)
	);
	$column_headers[$orderby]['sort'] = 'sorted '.($orderasc ? 'asc'  : 'desc');
	$column_headers[$orderby]['link'] = '&order='.($orderasc ? 'desc' : 'asc' );
?>

<!-- Container -->
<div id="col-container">

<!-- List of holidays -->
<div id="col-right"><div class="col-wrap">
	<table cellspacing="0" class="wp-list-table widefat fixed">
		<thead>
			<tr>
				<?php
					foreach($column_headers as $key => $value) {
						echo '<th class="'.$value['sort'].'" scope="col">';
						echo '<a href="'.$base_link.'&orderby='.$key.$value['link'].'">';
						echo '<span>'.$value['label'].'</span>';
						echo '<span class="sorting-indicator"></span>';
						echo '</a>';
						echo '</th>';
					}
				?>
			</tr>
		</thead>
		<tfoot>
			<tr>
				<?php
					foreach($column_headers as $key => $value) {
						echo '<th class="'.$value['sort'].'" scope="col">';
						echo '<a href="'.$base_link.'&orderby='.$key.$value['link'].'">';
						echo '<span>'.$value['label'].'</span>';
						echo '<span class="sorting-indicator"></span>';
						echo '</a>';
						echo '</th>';
					}
				?>
			</tr>
		</tfoot>
		<tbody>
			<?php
				if(empty($holidays)) {
					echo '<tr><td colspan="3">';
					echo __('No holiday found', 'rpbcalendar');
					echo '</td></tr>';
				} else {
					foreach($holidays as $holiday) {
						$current_id    = htmlspecialchars($holiday->holiday_id  );
						$current_name  = htmlspecialchars($holiday->holiday_name);
						$current_begin = htmlspecialchars(date($date_format, strtotime($holiday->holiday_begin)));
						$current_end   = htmlspecialchars(date($date_format, strtotime($holiday->holiday_end  )));
						echo '<tr>';
						echo '<td>';
						echo '<span class="row-title">'.$current_name.'</span>';
						echo '<br /><div class=row-actions>';
						echo '<a href="'.$base_link.'&edit='.$current_id.'">'.__('Edit').'</a> | ';
						echo '<a href="'.$base_link.'&delete='.$current_id.'">'.__('Delete').'</a>';
						echo '</div>';
						echo '</td>';
						echo '<td>'.$current_begin.'</td>';
						echo '<td>'.$current_end.'</td>';
						echo '</tr>';
					}
				}
			?>
		</tbody>
	</table>
</div></div>

<!-- Add holiday -->
<div id="col-left"><div class="col-wrap"><div class="form-wrap">
	<h3><?php _e('Add a new holiday', 'rpbcalendar'); ?></h3>
	<form name="holidayform" method="post" action="<?php echo $base_link; ?>">
		<input type="hidden" name="mode" value="add" />
		<div class="form-field">
			<label for="holiday_name"><?php _e('Name', 'rpbcalendar'); ?></label>
			<input type="text" name="holiday_name" maxlength="30" value="" />
		</div>
		<div class="form-field">
			<label for="holiday_begin"><?php _e('First day', 'rpbcalendar'); ?></label>
			<input type="text" name="holiday_begin" maxlength="10" value="" />
			<p>
				<?php _e('Use the following format: yyyy-mm-dd'); ?>
			</p>
		</div>
		<div class="form-field">
			<label for="holiday_end"><?php _e('Last day', 'rpbcalendar'); ?></label>
			<input type="text" name="holiday_end" maxlength="10" value="" />
			<p>
				<?php _e('Use the following format: yyyy-mm-dd'); ?>
			</p>
		</div>
		<input class="button" type="submit" value="<?php _e('Add', 'rpbcalendar'); ?>" />
	</form>
</div></div></div>

<!-- Closing the container -->
</div>
