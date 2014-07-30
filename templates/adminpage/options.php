<?php
/******************************************************************************
 *                                                                            *
 *    This file is part of RPB Calendar, a Wordpress plugin.                  *
 *    Copyright (C) 2014  Yoann Le Montagner <yo35 -at- melix.net>            *
 *                                                                            *
 *    This program is free software: you can redistribute it and/or modify    *
 *    it under the terms of the GNU General Public License as published by    *
 *    the Free Software Foundation, either version 3 of the License, or       *
 *    (at your option) any later version.                                     *
 *                                                                            *
 *    This program is distributed in the hope that it will be useful,         *
 *    but WITHOUT ANY WARRANTY; without even the implied warranty of          *
 *    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the           *
 *    GNU General Public License for more details.                            *
 *                                                                            *
 *    You should have received a copy of the GNU General Public License       *
 *    along with this program.  If not, see <http://www.gnu.org/licenses/>.   *
 *                                                                            *
 ******************************************************************************/
?>

<form action="<?php echo htmlspecialchars($model->getPageOptionsURL()); ?>" method="post">

	<input type="hidden" name="rpbcalendar_action" value="update-options" />

	<input type="hidden" name="defaultCategoryColor" id="rpbcalendar-defaultCategoryColorField" value="<?php
		echo htmlspecialchars($model->getDefaultCategoryColor());
	?>" />

	<input type="hidden" name="defaultEventColor" id="rpbcalendar-defaultEventColorField" value="<?php
		echo htmlspecialchars($model->getDefaultEventColor());
	?>" />

	<table class="form-table">
		<tbody>

			<tr valign="top">
				<th scope="row"><?php _e('Default category color', 'rpbcalendar'); ?></th>
				<td>
					<div id="rpbcalendar-defaultCategoryColorIris"></div>
					<p class="description">
						<?php
							_e('The default category color is used to display the events that belong to categories with no color.',
								'rpbcalendar');
						?>
					</p>
				</td>
			</tr>

			<tr valign="top">
				<th scope="row"><?php _e('Default event color', 'rpbcalendar'); ?></th>
				<td>
					<div id="rpbcalendar-defaultEventColorIris"></div>
					<p class="description">
						<?php
							_e('The default event color is used to display the events that do not belong to any category.',
								'rpbcalendar');
						?>
					</p>
				</td>
			</tr>

			<tr valign="top">
				<th scope="row"><?php _e('Date format in events', 'rpbcalendar'); ?></th>
				<td>
					<p>
						<input type="hidden" name="defaultShowWeekDay" value="0" />
						<input id="rpbcalendar-defaultShowWeekDayField" type="checkbox" name="defaultShowWeekDay" value="1"
							<?php if($model->getDefaultShowWeekDay()): ?>checked="yes"<?php endif; ?>
						/>
						<label for="rpbcalendar-defaultShowWeekDayField"><?php _e('Show weekday', 'rpbcalendar'); ?></label>
					</p>
					<p>
						<input type="hidden" name="defaultShowYear" value="0" />
						<input id="rpbcalendar-defaultShowYearField" type="checkbox" name="defaultShowYear" value="1"
							<?php if($model->getDefaultShowYear()): ?>checked="yes"<?php endif; ?>
						/>
						<label for="rpbcalendar-defaultShowYearField"><?php _e('Show year', 'rpbcalendar'); ?></label>
					</p>
				</td>
			</tr>

		</tbody>
	</table>


	<p class="submit">
		<input class="button button-primary" type="submit" value="<?php _e('Save changes', 'rpbcalendar'); ?>" />
	</p>

</form>



<script type="text/javascript">

	jQuery(document).ready(function($)
	{
		// Initialize the color picker widget (default category color).
		$('#rpbcalendar-defaultCategoryColorIris').iris2({
			buttonClass: 'button',
			clearButton: false,
			color: $('#rpbcalendar-defaultCategoryColorField').val(),
			change: function(event, ui) {
				$('#rpbcalendar-defaultCategoryColorField').val(ui.color);
			}
		});

		// Initialize the color picker widget (default event color).
		$('#rpbcalendar-defaultEventColorIris').iris2({
			buttonClass: 'button',
			clearButton: false,
			color: $('#rpbcalendar-defaultEventColorField').val(),
			change: function(event, ui) {
				$('#rpbcalendar-defaultEventColorField').val(ui.color);
			}
		});

	});

</script>
