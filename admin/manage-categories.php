<?php

	// WP database
	global $wpdb;

	// Table ordering
	if(isset($_GET['orderby']) && ($_GET['orderby']=='category_name' ||
		$_GET['orderby']=='category_text_color' || $_GET['orderby']=='category_background_color'))
	{
		$orderby = $_GET['orderby'];
	} else {
		$orderby = 'category_name';
	}

	// Ordering direction
	if(isset($_GET['order']) && $_GET['order']=='desc') {
		$orderasc = false;
	} else {
		$orderasc = true;
	}

	// Link to the current page
	$base_link = site_url().'/wp-admin/admin.php?page=rpbcalendar-categories';

	// Retrieve categories
	$categories = $wpdb->get_results(
		"SELECT category_id, category_name, category_text_color, category_background_color ".
		"FROM ".RPBCALENDAR_CATEGORY_TABLE." ".
		"ORDER BY ".$orderby." ".($orderasc ? "ASC" : "DESC").";"
	);

	// Setup column headers
	$column_headers = array(
		'category_name' => array(
			'label' => __('Name', 'rpbcalendar'),
			'sort'  => 'sortable desc',
			'link'  => '&order=asc'
		),
		'category_text_color' => array(
			'label' => __('Text color', 'rpbcalendar'),
			'sort'  => 'sortable desc',
			'link'  => '&order=asc'
		),
		'category_background_color' => array(
			'label' => __('Background color', 'rpbcalendar'),
			'sort'  => 'sortable desc',
			'link'  => '&order=asc'
		)
	);
	$column_headers[$orderby]['sort'] = 'sorted '.($orderasc ? 'asc'  : 'desc');
	$column_headers[$orderby]['link'] = '&order='.($orderasc ? 'desc' : 'asc' );
?>

<!-- Container -->
<div id="col-container">

<!-- List of categories -->
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
				<th scope="col">
					<?php _e('Preview', 'rpbcalendar'); ?>
				</th>
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
				<th scope="col">
					<?php _e('Preview', 'rpbcalendar'); ?>
				</th>
			</tr>
		</tfoot>
		<tbody>
			<?php
				if(empty($categories)) {
					echo '<tr><td colspan="4">';
					echo __('No category found', 'rpbcalendar');
					echo '</td></tr>';
				} else {
					foreach($categories as $category) {
						$current_id               = htmlspecialchars($category->category_id              );
						$current_name             = htmlspecialchars($category->category_name            );
						$current_text_color       = htmlspecialchars($category->category_text_color      );
						$current_background_color = htmlspecialchars($category->category_background_color);
						echo '<tr>';
						echo '<td>';
						echo '<span class="row-title">'.$current_name.'</span>';
						echo '<br /><div class=row-actions>';
						echo '<a href="'.$base_link.'&edit='.$current_id.'">'.__('Edit').'</a> | ';
						echo '<a href="'.$base_link.'&delete='.$current_id.'">'.__('Delete').'</a>';
						echo '</div>';
						echo '</td>';
						echo '<td>';
						echo '<div class="rpbcalendar-color-caption" style="background-color: '
							.$current_text_color.';"></div>';
						echo $current_text_color;
						echo '</td>';
						echo '<td>';
						echo '<div class="rpbcalendar-color-caption" style="background-color: '
							.$current_background_color.';"></div>';
						echo $current_background_color;
						echo '</td>';
						echo '<td>';
						echo '<div class="rpbcalendar-category-preview" style="background-color: '
							.$current_background_color.'; color: '.$current_text_color.';">';
						echo $current_name;
						echo '</div>';
						echo '</td>';
						echo '</tr>';
					}
				}
			?>
		</tbody>
	</table>
</div></div>

<!-- Add category -->
<div id="col-left"><div class="col-wrap"><div class="form-wrap">
	<h3><?php _e('Add a new category', 'rpbcalendar'); ?></h3>
	<form name="catform" id="catform" method="post" action="<?php echo $base_link; ?>">
		<input type="hidden" name="mode" value="add" />
		<div class="form-field">
			<label for="category_name"><?php _e('Name', 'rpbcalendar'); ?></label>
			<input type="text" name="category_name" maxlength="30" value="" />
		</div>
		<div class="form-field">
			<label for="category_text_color"><?php _e('Text color', 'rpbcalendar'); ?></label>
			<input type="text" name="category_text_color" maxlength="7" value="" />
			<p>
				<?php _e('Use HTML hexa format (ex: #0000ff for blue or #ffff00 for yellow)', 'rpbcalendar'); ?>
			</p>
		</div>
		<div class="form-field">
			<label for="category_background_color"><?php _e('Background color', 'rpbcalendar'); ?></label>
			<input type="text" name="category_background_color" maxlength="7" value="" />
			<p>
				<?php _e('Use HTML hexa format (ex: #0000ff for blue or #ffff00 for yellow)', 'rpbcalendar'); ?>
			</p>
		</div>
		<input class="button" type="submit" value="<?php _e('Add', 'rpbcalendar'); ?>" />
	</form>
</div></div></div>

<!-- Closing the container -->
</div>
