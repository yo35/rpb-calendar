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

<script type="text/javascript">

	jQuery(document).ready(function($)
	{

		// Render the content of the tooltip.
		function renderContent(json, api)
		{
			// Event categories
			var categories = '';
			if(json.categories.length>0) {
				categories += '<div class="rpbcalendar-eventTip-categories">';
				for(var k=0; k<json.categories.length; ++k) {
					if(k>0) {
						categories += ' ';
					}
					categories += '<span class="rpbcalendar-categoryTag" style="background-color:' + json.categories[k].color + '"></span> ' +
						json.categories[k].name;
				}
				categories += '</div>';
			}

			// Begin/end dates
			var beginEndDates = '<div class="rpbcalendar-eventTip-beginEndDates"><span class="rpbcalendar-dateMark"></span>' + json.beginDate;
			if(json.endDate !== '') {
				beginEndDates += '<span class="rpbcalendar-dateSeparator"></span>' + json.endDate;
			}
			beginEndDates += '</div>';

			// Event author and release date
			var releaseInfo = '<div class="rpbcalendar-eventTip-releaseInfo">' +
				<?php echo json_encode(__('Posted on %2$s by %1$s', 'rpbcalendar')); ?> + '</div>';
			releaseInfo = releaseInfo.replace(/%1\$s/g, json.author);
			releaseInfo = releaseInfo.replace(/%2\$s/g, json.releaseDate);

			// Event description
			var text = json.content==='' ? '' : '<div class="rpbcalendar-eventTip-content">' + json.content + '</div>';

			// Event link
			var link = json.link==='' ? '' : '<div class="rpbcalendar-eventTip-link"><a href="' + json.link + '" target=_blank>' + json.link + '</a></div>';

			// Separator above the event link
			var separator = link==='' ? '' : '<hr class="rpbcalendar-eventTip-separator" />';

			// Replace the content of the tooltip.
			api.set('content.text', categories + beginEndDates + releaseInfo + text + separator + link);
		}


		// Set-up the tooltips.
		$('#' + <?php echo json_encode($model->getWidgetID()); ?> + ' .rpbcalendar-eventBlock').each(function(i,e) {
			$(e).qtip({
				content: {
					title: $(e).text(),
					button: true,
					text: function(event, api) {

						// AJAX request to fetch the event data.
						$.ajax({
							url     : <?php echo json_encode($model->getFetchEventDataURL()); ?>,
							data    : { id: $(e).data('eventId') },
							dataType: 'json',
						})

						// Render the event data if the AJAX request succeeds.
						.done(function(json) {
							if(!json.error) {
								renderContent(json, api);
							}
						});

						// Return a loading indicator.
						return $('<div></div>').spinanim();
					}
				},
				position: { my: 'top left', at: 'bottom left' },
				style: { classes: 'qtip-tipped rpbcalendar-qtip qtip-shadow' },
				show: { delay: 100, solo: true },
				hide: { delay: 100, fixed: true }
			});
		});

	});

</script>
