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

<input type="text" name="event_link" id="rpbcalendar-admin-eventLinkField" value="<?php
	echo htmlspecialchars($model->getEventLink());
?>" />


<script type="text/javascript">

	// Mark the field as valid
	function rpbCalendarUpdateEventLinkState($, isValid)
	{
		if(isValid) {
			$('#rpbcalendar-admin-eventLinkField').removeClass('rpbcalendar-admin-invalidField');
		}
		else {
			$('#rpbcalendar-admin-eventLinkField').addClass('rpbcalendar-admin-invalidField');
		}
	}


	// Call the AJAX validation request when the value of the field changes.
	jQuery('#rpbcalendar-admin-eventLinkField').change(function()
	{
		jQuery.ajax(
			'<?php echo RPBCALENDAR_URL . '/validate.php'; ?>',
			{
				data: { method: 'validateURL', arg1: true, value: jQuery('#rpbcalendar-admin-eventLinkField').val() },
				dataType: 'json',
				success: function(answer) { rpbCalendarUpdateEventLinkState(jQuery, answer.result); },
				error: function() { rpbCalendarUpdateEventLinkState(jQuery, false); }
			}
		);
	}).change();

</script>
