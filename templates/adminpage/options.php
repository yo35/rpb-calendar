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

<form action="<?php echo htmlspecialchars($model->getFormActionURL()); ?>" method="post">

	<input type="hidden" name="rpbcalendar_defaultColor" id="rpbcalendar-admin-defaultColorField" value="<?php
		echo htmlspecialchars($model->getDefaultCategoryColor());
	?>" />

	<table class="form-table">
		<tbody>

			<tr valign="top">

				<th scope="row">
					<label for="rpbcalendar-admin-defaultColorField"><?php
						_e('Default category color', 'rpbcalendar');
					?></label>
				</th>

				<td>
					<div class="rpbcalendar-admin-hBox">
						<div class="rpbcalendar-admin-vBox">
							<div id="rpbcalendar-admin-defaultColorPreview" class="rpbcalendar-admin-colorPatch"></div>
							<a class="button" id="rpbcalendar-admin-randomColorButton" href="#" title="<?php
								_e('Select a color at random', 'rpbcalendar');
							?>"><?php _e('Random', 'rpbcalendar'); ?></a>
						</div>
						<div>
							<div id="rpbcalendar-admin-colorPicker"></div>
						</div>
					</div>
				</td>

			</tr>

		</tbody>
	</table>

</form>



<script type="text/javascript">

	jQuery(document).ready(function($)
	{
		// Initialize the color picker widget.
		$('#rpbcalendar-admin-defaultColorField').prop('readonly', true).iris({
			hide: false,
			palettes: true,
			target: $('#rpbcalendar-admin-colorPicker'),
			change: function(event, ui) {
				$('#rpbcalendar-admin-defaultColorPreview').css('background-color', ui.color.toString());
			}
		});

		// Callback to set a color at random.
		$('#rpbcalendar-admin-randomColorButton').click(function(e) {
			e.preventDefault();
			var color = Math.floor(Math.random()*256*256*256);
			$('#rpbcalendar-admin-defaultColorField').iris('color', '#' + color.toString(16));
		});

		// Initial aspect of the color sample.
		$('#rpbcalendar-admin-defaultColorPreview').css('background-color', $('#rpbcalendar-admin-defaultColorField').val());
	});

</script>
