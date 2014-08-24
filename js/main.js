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


/**
 * Miscellaneous functions used by the plugin.
 *
 * @requires Moment.js {@link http://momentjs.com/}
 * @requires jQuery
 * @requires jQuery UI Date Picker
 * @requires spinanim.js
 * @requires qTip2 {@link http://qtip2.com/}
 * @requires FullCalendar {@link http://arshaw.com/fullcalendar/}
 */
var RPBCalendar = (function(moment, $) /* exported RPBCalendar */
{
	'use strict';


	/**
	 * Internationalization constants.
	 */
	var i18n = {

		/**
		 * Release info field.
		 * @type {string}
		 */
		POSTED_ON_BY: 'Posted on %1$s by %2$s',

		/**
		 * Today button text.
		 * @type {string}
		 */
		TODAY: 'Today'
	};


	/**
	 * Configuration parameters.
	 */
	var config = {

		/**
		 * Target URL to use to fetch event lists.
		 * @type {string}
		 */
		FETCH_EVENTS_URL: '',

		/**
		 * Target URL to use to fetch event descriptions.
		 * @type {string}
		 */
		FETCH_EVENT_DATA_URL: '',

		/**
		 * First day of the week (0 = Sunday, 1 = Monday, ...,  6 = Saturday).
		 * @type {number}
		 */
		FIRST_DAY_OF_WEEK: 0
	};


	/**
	 * Tooltip content rendering function.
	 *
	 * @param {object} json
	 * @param {object} api
	 */
	function renderTooltipContent(json, api)
	{
		// Title
		var title = '<div class="rpbcalendar-eventTip-title">' + json.title + '</div>';

		// Begin/end dates
		var beginEndDates = '<div class="rpbcalendar-eventTip-beginEndDates">' + json.beginDate;
		if(json.endDate !== null) {
			beginEndDates += '<span class="rpbcalendar-dateSeparator">&#9654;</span>' + json.endDate;
		}
		beginEndDates += '</div>';

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

		// Event author and release date
		var releaseInfo = '<div class="rpbcalendar-eventTip-releaseInfo">' + i18n.POSTED_ON_BY + '</div>';
		releaseInfo = releaseInfo.replace(/%1\$s/g, json.releaseDate);
		releaseInfo = releaseInfo.replace(/%2\$s/g, json.author);

		// Event description
		var text = json.content==='' ? '' : '<div class="rpbcalendar-eventTip-content">' + json.content + '</div>';

		// Event link
		var link = json.link===null ? '' : '<div class="rpbcalendar-eventTip-link"><a href="' + json.link + '" target=_blank>' + json.link + '</a></div>';

		// Separator above the event link
		var separator = link==='' ? '' : '<hr class="rpbcalendar-eventTip-separator" />';

		// Replace the content of the tooltip.
		api.set('content.title', title + beginEndDates );
		api.set('content.text', categories + releaseInfo + text + separator + link);
	}


	/**
	 * Decorate the underlying DOM nodes with an event description tooltip.
	 *
	 * @param {jQuery} elements
	 */
	function addEventTooltip(elements)
	{
		elements.each(function(i,e)
		{
			// Skip the nodes that are not associated with an event ID.
			var id = $(e).data('eventId');
			if(id===undefined) {
				return;
			}

			// Tooltip factory.
			$(e).qtip({
				content: {
					title: $(e).text(),
					button: true,
					text: function(event, api) {

						// AJAX request to fetch the event data.
						$.ajax({
							url     : config.FETCH_EVENT_DATA_URL,
							data    : { id: id },
							dataType: 'json'
						})

						// Render the event data if the AJAX request succeeds.
						.done(function(json) {
							if(!json.error) {
								renderTooltipContent(json, api);
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
	}


	/**
	 * Create a calendar table to the given DOM node.
	 *
	 * @param {jQuery} element
	 */
	function addCalendar(element)
	{
		element.fullCalendar({

			// General calendar options
			header: { left: 'title', center: '', right: ' today prevYear,prev,next,nextYear' },
			firstDay: config.FIRST_DAY_OF_WEEK,

			// Event source and rendering method
			events: config.FETCH_EVENTS_URL,
			eventRender: function(event, element) {

				// Build the event block
				var content = $('<div class="rpbcalendar-eventBlock"></div>');
				content.data('eventId', event.ID);
				content.attr('style', event.style);

				// Event title
				$('<div class="rpbcalendar-eventTitle">' + event.title + '</div>').appendTo(content);
				if(event.teaser !== null) {
					$('<div class="rpbcalendar-eventTeaser">' + event.teaser + '</div>').appendTo(content);
				}

				// Set-up the tooltip
				addEventTooltip(content);

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
				today: i18n.TODAY
			},
			monthNames: moment.months(),
			monthNamesShort: moment.monthsShort(),
			dayNames: moment.weekdays(),
			dayNamesShort: moment.weekdaysShort(),

			// Loading indicator
			loading: function(isLoading) {
				if(isLoading) {
					var todayButton = $('.fc-button-today', element);
					var anchor = $('.fc-header-right .fc-header-space', element).first();
					var spinAnim = $('<div></div>').spinanim().appendTo(anchor);
					spinAnim.offset({
						left: anchor.offset().left - spinAnim.width(),
						top: todayButton.offset().top
					});
					var scale = 'scale(' + (todayButton.height() / spinAnim.height()) + ')';
					spinAnim.css('transform', scale).css('-ms-transform', scale).css('-webkit-transform', scale);
				}
				else {
					$('.uicalendar-spinanim', element).remove();
				}
			}
		});
	}


	/**
	 * Create a date picker popup widget associated to an input text field.
	 *
	 * @param {jQuery} element Anchor for the date picker widget.
	 * @param {jQuery} inputElement Input element associated to the date picker.
	 * @param {object} options Parameter passed to the date picker widget.
	 */
	function addDatePicker(element, inputElement, options)
	{
		// Default 'onSelect' callback
		var callbackOnSelect = typeof options.onSelect === 'function' ? options.onSelect : null;

		// Set the default options
		options.altField    = inputElement;
		options.dateFormat  = 'yy-mm-dd';
		options.defaultDate = inputElement.val();
		options.firstDay    = config.FIRST_DAY_OF_WEEK;
		options.onSelect    = function(value) {
			element.removeClass('rpbcalendar-popupVisible');
			if(callbackOnSelect !== null) {
				callbackOnSelect(value);
			}
		};

		// Build the date picker
		element.addClass('rpbcalendar-datePickerPopup').datepicker(options);
		var background = $('<div class="rpbcalendar-popupBackground"></div>').appendTo(element);
		background.click(function() {
			element.removeClass('rpbcalendar-popupVisible');
		});
		inputElement.focusin(function() {
			element.addClass('rpbcalendar-popupVisible');
		});
	}


	return {
		i18n: i18n,
		config: config,
		addEventTooltip: addEventTooltip,
		addCalendar: addCalendar,
		addDatePicker: addDatePicker
	};

})( /* global moment */ moment, /* global jQuery */ jQuery );
