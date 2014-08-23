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

<div class="rpbcalendar-hBox">
	<input type="text" name="rpbevent_link" id="rpbcalendar-eventLinkField" value="<?php
		echo htmlspecialchars($model->getEventLink());
	?>" />
	<a class="button" id="rpbcalendar-eventLinkPreview" href="#" title="<?php
		_e('Preview the link target in a new window', 'rpbcalendar');
	?>"><?php _e('Preview', 'rpbcalendar'); ?></a>
</div>


<script type="text/javascript">

	jQuery(document).ready(function($)
	{
		// Preview window for the event link.
		var previewWindow = null;

		// Open the event link target in a dedicated window when the 'Preview' button is clicked.
		$('#rpbcalendar-eventLinkPreview').click(function(e) {
			e.preventDefault();

			// Basic check on the URL
			var url = $('#rpbcalendar-eventLinkField').val();
			if(!url.match(/^https?:\/\//)) {
				alert(<?php echo json_encode(__('The link must start with http:// or https://.', 'rpbcalendar')); ?>);
				return;
			}

			// Open the link
			if(previewWindow===null || previewWindow.closed) {
				previewWindow = window.open(url);
			}
			else {
				previewWindow.location.replace(url);
			}
		});
	});

</script>
