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

				if(event.link !== '') {
					var content = element.contents();
					var clazz   = element.attr('class');
					element = $('<a target="_blank"></a>').attr('href', event.link).attr('class', clazz).append(content);
				}

				// Class and attributes
				element.addClass('rpbcalendar-eventBlock');
				element.data('eventId', event.ID);
				element.attr('style', event.style);

				// Set-up the tooltip
				RPBCalendar.addEventTooltip(element);

				// Return the element
				return element;
			},

			// Localization
			buttonText: {
				today: RPBCalendar.i18n.TODAY
			},
			monthNames: RPBCalendar.i18n.MONTH_NAMES,
			monthNamesShort: RPBCalendar.i18n.MONTH_SHORT_NAMES,
			dayNames: RPBCalendar.i18n.WEEKDAY_NAMES,
			dayNamesShort: RPBCalendar.i18n.WEEKDAY_SHORT_NAMES,

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
