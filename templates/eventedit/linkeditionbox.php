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

<div class="rpbcalendar-admin-hBox">
	<input type="text" name="rpbevent_link" id="rpbcalendar-admin-eventLinkField" value="<?php
		echo htmlspecialchars($model->getEventLink());
	?>" />
	<a class="button" id="rpbcalendar-admin-eventLinkPreview" href="#" title="<?php
		_e('Preview the target link in a new window', 'rpbcalendar');
	?>"><?php _e('Preview', 'rpbcalendar'); ?></a>
</div>


<script type="text/javascript">

	// Mark the field as valid
	function rpbcalendarUpdateEventLinkState($, isValid)
	{
		if(isValid) {
			$('#rpbcalendar-admin-eventLinkField').removeClass('rpbcalendar-admin-invalidField');
			$('#rpbcalendar-admin-eventLinkPreview').removeClass('rpbcalendar-admin-disabled');
		}
		else {
			$('#rpbcalendar-admin-eventLinkField').addClass('rpbcalendar-admin-invalidField');
			$('#rpbcalendar-admin-eventLinkPreview').addClass('rpbcalendar-admin-disabled');
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
				success: function(answer) { rpbcalendarUpdateEventLinkState(jQuery, answer.result); },
				error: function() { rpbcalendarUpdateEventLinkState(jQuery, false); }
			}
		);
	}).change();


	// Preview window for the event link.
	var rpbcalendarEventLinkPreviewWindow = null;


	// Open the event link target in a dedicated window when the 'Preview' button is clicked.
	jQuery('#rpbcalendar-admin-eventLinkPreview').click(function(e)
	{
		e.preventDefault();
		if(jQuery('#rpbcalendar-admin-eventLinkPreview').hasClass('rpbcalendar-admin-disabled')) {
			return;
		}
		var url = jQuery('#rpbcalendar-admin-eventLinkField').val();
		if(rpbcalendarEventLinkPreviewWindow==null || rpbcalendarEventLinkPreviewWindow.closed) {
			rpbcalendarEventLinkPreviewWindow = window.open(url);
		}
		else {
			rpbcalendarEventLinkPreviewWindow.location.replace(url);
		}
	});

</script>
