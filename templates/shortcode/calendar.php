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

<div id="<?php echo htmlspecialchars($model->getUniqueID()); ?>" class="rpbcalendar-calendar"></div>

<script type="text/javascript">

	jQuery(document).ready(function($) {

		$('#' + <?php echo json_encode($model->getUniqueID()); ?>).fullCalendar({

			// General calendar options
			header: { left: 'title', center: '', right: ' today prevYear,prev,next,nextYear' },
			firstDay: <?php echo json_encode($model->getStartOfWeek()); ?>,

			// Event source and rendering method
			events: <?php echo json_encode($model->getFetchEventsURL()); ?>,
			eventRender: function(event, element) {

				// Build the event block
				var content = $('<div class="rpbcalendar-eventBlock"></div>');
				content.data('eventId', event.ID);
				content.attr('style', event.style);

				// Event title
				$('<div class="rpbcalendar-eventTitle"></div>').text(event.title).appendTo(content);
				if(event.teaser !== null) {
					$('<div class="rpbcalendar-eventTeaser">' + event.teaser + '</div>').appendTo(content);
				}

				// Set-up the tooltip
				RPBCalendar.addEventTooltip(content);

				// Event link
				if(event.link !== null) {
					content = $('<a target="_blank"></a>').attr('href', event.link).append(content);
				}

				// Return the element
				element.empty().append(content);
				return element;
			},

			// Localization
			buttonText: {
				today: RPBCalendar.i18n.TODAY
			},
			monthNames: moment.months(),
			monthNamesShort: moment.monthsShort(),
			dayNames: moment.weekdays(),
			dayNamesShort: moment.weekdaysShort(),

			// Set-up the loading indicator
			loading: function(isLoading, view) {
				if(isLoading) {
					var todayButton = $('#' + <?php echo json_encode($model->getUniqueID()); ?> + ' .fc-button-today');
					var anchor = $('#' + <?php echo json_encode($model->getUniqueID()); ?> + ' .fc-header-right .fc-header-space').first();
					var spinAnim = $('<div></div>').spinanim().appendTo(anchor);
					spinAnim.offset({
						left: anchor.offset().left - spinAnim.width(),
						top: todayButton.offset().top
					});
					var scale = 'scale(' + (todayButton.height() / spinAnim.height()) + ')';
					spinAnim.css('transform', scale).css('-ms-transform', scale).css('-webkit-transform', scale);
				}
				else {
					$('#' + <?php echo json_encode($model->getUniqueID()); ?> + ' .uicalendar-spinanim').remove();
				}
			}
		});

	});

</script>
