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

<div class="rpbcalendar-admin-gridLayout">
	<div>
		<div>
			<label for="rpbcalendar-admin-eventDateBeginField"><?php _e('From:', 'rpbcalendar'); ?></label>
		</div>
		<div>
			<input type="text" name="rpbevent_date_begin" id="rpbcalendar-admin-eventDateBeginField" value="<?php
				echo htmlspecialchars($model->getEventDateBeginAsString());
			?>" size="10" />
		</div>
	</div>
	<div>
		<div>
			<label for="rpbcalendar-admin-eventDateBeginField"><?php _e('To:', 'rpbcalendar'); ?></label>
		</div>
		<div>
			<input type="text" name="rpbevent_date_end" id="rpbcalendar-admin-eventDateEndField" value="<?php
				echo htmlspecialchars($model->getEventDateEndAsString());
			?>" size="10" />
		</div>
	</div>
</div>

<div id="rpbcalendar-eventDateBeginPicker" class="rpbcalendar-datePickerPopup rpbcalendar-jQuery-enableSmoothness">
	<div class="rpbcalendar-datePickerPopup-background"></div>
	<div class="rpbcalendar-datePickerPopup-widget"></div>
</div>

<div id="rpbcalendar-eventDateEndPicker" class="rpbcalendar-datePickerPopup rpbcalendar-jQuery-enableSmoothness">
	<div class="rpbcalendar-datePickerPopup-background"></div>
	<div class="rpbcalendar-datePickerPopup-widget"></div>
</div>


<script type="text/javascript">

	jQuery(document).ready(function($)
	{
		$('#rpbcalendar-admin-eventDateBeginField').prop('readonly', true).focusin(function() {
			$('#rpbcalendar-eventDateBeginPicker').addClass('rpbcalendar-popupVisible');
		});

		$('#rpbcalendar-admin-eventDateEndField').prop('readonly', true).focusin(function() {
			$('#rpbcalendar-eventDateEndPicker').addClass('rpbcalendar-popupVisible');
		});

		$('#rpbcalendar-eventDateBeginPicker .rpbcalendar-datePickerPopup-widget').datepicker({
			altField: '#rpbcalendar-admin-eventDateBeginField',
			dateFormat: 'yy-mm-dd',
			defaultDate: $('#rpbcalendar-admin-eventDateBeginField').val(),
			onSelect: function(dateBegin) {
				$('#rpbcalendar-eventDateEndPicker .rpbcalendar-datePickerPopup-widget').datepicker('option', 'minDate', dateBegin);
				$('#rpbcalendar-eventDateBeginPicker').removeClass('rpbcalendar-popupVisible');
			}
		});

		$('#rpbcalendar-eventDateEndPicker .rpbcalendar-datePickerPopup-widget').datepicker({
			altField: '#rpbcalendar-admin-eventDateEndField',
			dateFormat: 'yy-mm-dd',
			defaultDate: $('#rpbcalendar-admin-eventDateEndField').val(),
			minDate: $('#rpbcalendar-admin-eventDateBeginField').val(),
			onSelect: function() {
				$('#rpbcalendar-eventDateEndPicker').removeClass('rpbcalendar-popupVisible');
			}
		});

		$('.rpbcalendar-datePickerPopup-background').click(function() {
			$('.rpbcalendar-datePickerPopup').removeClass('rpbcalendar-popupVisible');
		});

	});

</script>
