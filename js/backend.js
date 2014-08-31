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
 * Miscellaneous functions used by the plugin in the backend.
 *
 * @requires main.js
 * @requires jQuery
 * @requires jQuery UI Date Picker
 */
(function(RPBCalendar, $)
{
	'use strict';


	/**
	 * Create a date picker popup widget associated to an input text field.
	 *
	 * @param {jQuery} element Anchor for the date picker widget.
	 * @param {jQuery} inputElement Input element associated to the date picker.
	 * @param {object} options Parameter passed to the date picker widget.
	 */
	RPBCalendar.addDatePicker =  function(element, inputElement, options)
	{
		// Default 'onSelect' callback
		var callbackOnSelect = typeof options.onSelect === 'function' ? options.onSelect : null;

		// Set the default options
		options.altField    = inputElement;
		options.dateFormat  = 'yy-mm-dd';
		options.defaultDate = inputElement.val();
		options.firstDay    = RPBCalendar.config.FIRST_DAY_OF_WEEK;
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
	};

})( /* global RPBCalendar */ RPBCalendar, /* global jQuery */ jQuery );
