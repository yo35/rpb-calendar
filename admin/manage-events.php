<?php

	// WP database
	global $wpdb;

	// Table ordering
	if(isset($_GET['orderby']) && (
		$_GET['orderby']=='event_title'    ||
		$_GET['orderby']=='event_desc'     ||
		$_GET['orderby']=='event_begin'    ||
		$_GET['orderby']=='event_end'      ||
		$_GET['orderby']=='event_time'     ||
		$_GET['orderby']=='event_link'     ||
		$_GET['orderby']=='event_author'   ||
		$_GET['orderby']=='event_category'
	)) {
		$orderby = $_GET['orderby'];
	} else {
		$orderby = 'event_time';
	}

	// Ordering direction
	if(isset($_GET['order']) && $_GET['order']=='desc') {
		$orderasc = false;
	} elseif(isset($_GET['order']) && $_GET['order']=='asc') {
		$orderasc = true;
	} else {
		$orderasc = false;
	}

	// Link to the current page
	$base_link = site_url().'/wp-admin/admin.php?page=rpbcalendar-events';

	// Date format
	$date_format = get_option('date_format');
	$time_format = get_option('time_format');

	// Retrieve events
	$events = $wpdb->get_results(
		"SELECT * FROM ".RPBCALENDAR_CATEGORY_TABLE." ".
		"ORDER BY ".$orderby." ".($orderasc ? "ASC" : "DESC").";"
	);

	// Setup column headers
	$column_headers = array(
		'event_title' => array(
			'label' => __('Event', 'rpbcalendar'),
			'sort'  => 'sortable desc',
			'link'  => '&order=asc'
		),
		'event_desc' => array(
			'label' => __('Description', 'rpbcalendar'),
			'sort'  => 'sortable desc',
			'link'  => '&order=asc'
		),
		'event_begin' => array(
			'label' => __('Begin', 'rpbcalendar'),
			'sort'  => 'sortable desc',
			'link'  => '&order=asc'
		),
		'event_end' => array(
			'label' => __('End', 'rpbcalendar'),
			'sort'  => 'sortable desc',
			'link'  => '&order=asc'
		),
		'event_time' => array(
			'label' => __('Time', 'rpbcalendar'),
			'sort'  => 'sortable desc',
			'link'  => '&order=asc'
		),
		'event_link' => array(
			'label' => __('Link', 'rpbcalendar'),
			'sort'  => 'sortable desc',
			'link'  => '&order=asc'
		),
		'event_author' => array(
			'label' => __('Author', 'rpbcalendar'),
			'sort'  => 'sortable desc',
			'link'  => '&order=asc'
		),
		'event_category' => array(
			'label' => __('Category', 'rpbcalendar'),
			'sort'  => 'sortable desc',
			'link'  => '&order=asc'
		)
	);
	$column_headers[$orderby]['sort'] = 'sorted '.($orderasc ? 'asc'  : 'desc');
	$column_headers[$orderby]['link'] = '&order='.($orderasc ? 'desc' : 'asc' );
?>


<!-- List of events -->
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
			if(empty($events)) {
				echo '<tr><td colspan="8">';
				echo __('No event found', 'rpbcalendar');
				echo '</td></tr>';
			} else {
				foreach($events as $event) {
					$current_id       = htmlspecialchars($event->event_id      );
					$current_title    = htmlspecialchars($event->event_title   );
					$current_desc     = htmlspecialchars($event->event_desc    );
					$current_link     = htmlspecialchars($event->event_link    );
					$current_author   = htmlspecialchars($event->event_author  );
					$current_category = htmlspecialchars($event->event_category);
					$current_begin    = htmlspecialchars(date($date_format, strtotime($holiday->event_begin)));
					$current_end      = htmlspecialchars(date($date_format, strtotime($holiday->event_end  )));
					$current_time     = htmlspecialchars(date($time_format, strtotime($holiday->event_time )));
					echo '<tr>';
					echo '<td>';
					echo '<span class="row-title">'.$current_title.'</span>';
					echo '<br /><div class=row-actions>';
					echo '<a href="'.$base_link.'&edit='.$current_id.'">'.__('Edit').'</a> | ';
					echo '<a href="'.$base_link.'&delete='.$current_id.'">'.__('Delete').'</a>';
					echo '</div>';
					echo '</td>';
					echo '<td>'.$current_desc    .'</td>';
					echo '<td>'.$current_begin   .'</td>';
					echo '<td>'.$current_end     .'</td>';
					echo '<td>'.$current_time    .'</td>';
					echo '<td>'.$current_link    .'</td>';
					echo '<td>'.$current_author  .'</td>';
					echo '<td>'.$current_category.'</td>';
					echo '</tr>';
				}
			}
		?>
	</tbody>
</table>
