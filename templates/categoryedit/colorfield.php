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

<tr class="form-field">

	<th valign="top" scope="row">
		<label for="rpbcalendar-admin-categoryColorField"><?php _e('Color', 'rpbcalendar'); ?></label>
	</th>

	<td>
		<input type="hidden" name="rpbevent_category_color" id="rpbcalendar-admin-categoryColorField" value="<?php
			echo htmlspecialchars($model->getCategoryColor());
		?>" />
		<div class="rpbcalendar-admin-hBox">
			<div class="rpbcalendar-admin-vBox">
				<div id="rpbcalendar-admin-categoryColorPreview" class="rpbcalendar-admin-colorPatch"></div>
				<a class="button" id="rpbcalendar-admin-randomColorButton" href="#" title="<?php
					_e('Select a color at random', 'rpbcalendar');
				?>"><?php _e('Random', 'rpbcalendar'); ?></a>
				<a class="button" id="rpbcalendar-admin-clearColorButton" href="#" title="<?php
					_e('Do not associate a color to the current category', 'rpbcalendar');
				?>"><?php _e('Clear', 'rpbcalendar'); ?></a>
			</div>
			<div>
				<div id="rpbcalendar-admin-colorPicker"></div>
			</div>
		</div>
	</td>

</tr>


<script type="text/javascript">

	jQuery(document).ready(function($)
	{
		// Initialize the color picker widget.
		$('#rpbcalendar-admin-categoryColorField').prop('readonly', true).iris({
			hide: false,
			palettes: true,
			target: $('#rpbcalendar-admin-colorPicker'),
			change: function(event, ui) {
				$('#rpbcalendar-admin-categoryColorPreview').css('background-color', ui.color.toString());
				$('#rpbcalendar-admin-categoryColorPreview').removeClass('rpbcalendar-admin-colorPatchTransparent');
			}
		});


		// Callback to set a color at random.
		$('#rpbcalendar-admin-randomColorButton').click(function(e) {
			e.preventDefault();
			var color = Math.floor(Math.random()*256*256*256);
			$('#rpbcalendar-admin-categoryColorField').iris('color', '#' + color.toString(16));
		});


		// Callback to unset the color.
		$('#rpbcalendar-admin-clearColorButton').click(function(e) {
			e.preventDefault();
			$('#rpbcalendar-admin-categoryColorField').val('');
			$('#rpbcalendar-admin-categoryColorPreview').css('background-color', 'transparent');
			$('#rpbcalendar-admin-categoryColorPreview').addClass('rpbcalendar-admin-colorPatchTransparent');
		});


		// Initial aspect of the color sample.
		var initialColor = $('#rpbcalendar-admin-categoryColorField').val();
		$('#rpbcalendar-admin-categoryColorPreview').css('background-color', initialColor=='' ? 'transparent' : initialColor);
		if(initialColor=='') {
			$('#rpbcalendar-admin-categoryColorPreview').addClass('rpbcalendar-admin-colorPatchTransparent');
		}
	});

</script>
