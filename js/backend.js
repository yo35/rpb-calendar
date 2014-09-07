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
 * @requires Moment.js {@link http://momentjs.com/}
 */
(function(RPBCalendar, moment, $)
{
	'use strict';


	/**
	 * Create a date picker popup widget associated to an input text field.
	 *
	 * @param {jQuery} element Anchor for the date picker widget.
	 * @param {jQuery} inputElement Input element associated to the date picker.
	 * @param {object} options Parameter passed to the date picker widget.
	 */
	RPBCalendar.addDatePicker = function(element, inputElement, options)
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

		// Destroy the previously existing date-picker object if necessary
		element.datepicker('destroy');

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


	/**
	 * Handle of the window used to preview the event link targets.
	 */
	var previewWindow = null;


	/**
	 * Set-up the event link field of an event add/edit form.
	 *
	 * @param {jQuery} linkField
	 * @param {jQuery} previewButton
	 */
	RPBCalendar.setupEventLinkField = function(linkField, previewButton)
	{
		previewButton.click(function(e) {
			e.preventDefault();

			// Basic check off the URL
			var url = linkField.val();
			if(!url.match(/^https?:\/\//)) {
				window.alert(RPBCalendar.i18n.BAD_LINK_MESSAGE);
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
	};


	/**
	 * Set-up the date begin/end fields of an event add/edit form.
	 *
	 * @param {jQuery} beginField
	 * @param {jQuery} beginDatePicker
	 * @param {jQuery} beginWeekDay
	 * @param {jQuery} endField
	 * @param {jQuery} endDatePicker
	 * @param {jQuery} endWeekDay
	 */
	RPBCalendar.setupEventDateFields = function(beginField, beginDatePicker, beginWeekDay, endField, endDatePicker, endWeekDay)
	{
		beginField.prop('readonly', true).change(function() {
			beginWeekDay.text('(' + moment(beginField.val()).format('dddd') + ')');
		}).change();

		endField.prop('readonly', true).change(function() {
			endWeekDay.text('(' + moment(endField.val()).format('dddd') + ')');
		}).change();

		RPBCalendar.addDatePicker(beginDatePicker, beginField, {
			onSelect: function(dateBegin) {
				endDatePicker.datepicker('option', 'minDate', dateBegin);
				beginField.change();
				endField.change();
			}
		});

		RPBCalendar.addDatePicker(endDatePicker, endField, {
			minDate: beginField.val(),
			onSelect: function() { endField.change(); }
		});
	};


	/**
	 * The row index to use the next time a row will be cloned.
	 */
	var suggestedRowIndex = 0;


	/**
	 * Set-up the dynamic fields for multiple event edit forms.
	 *
	 * @param {number} rowIndex
	 */
	RPBCalendar.setupEventRow = function(rowIndex)
	{
		var entry = $('#rpbcalendar-eventEntry-' + rowIndex);

		// Update the row-index counter
		suggestedRowIndex = Math.max(suggestedRowIndex, rowIndex) + 1;

		// Setup the date picker widgets
		RPBCalendar.setupEventDateFields(
			$('.rpbcalendar-eventDateBeginField', entry), $('.rpbcalendar-eventDateBeginPicker', entry), $('.rpbcalendar-eventDateBeginWeekday', entry),
			$('.rpbcalendar-eventDateEndField'  , entry), $('.rpbcalendar-eventDateEndPicker'  , entry), $('.rpbcalendar-eventDateEndWeekday'  , entry)
		);

		// Clone-row button
		$('.rpbcalendar-cloneEntryButton', entry).click(function(e) {
			e.preventDefault();

			// Clone the current event row
			var newEntry = entry.clone();
			var newRowIndex = suggestedRowIndex;

			// The value of the textareas must be cloned manually.
			newEntry.find('textarea').each(function(index, elem) {
				$(elem).val($('textarea[name="' + $(elem).attr('name') + '"]', entry).val());
			});

			// Change the IDs and FOR/NAME-attributes in the cloned row
			newEntry.find('*[id$="-' + rowIndex + '"]').andSelf().each(function(index, elem) {
				$(elem).attr('id', $(elem).attr('id').replace(new RegExp(rowIndex + '$'), newRowIndex));
			});
			newEntry.find('*[for$="-' + rowIndex + '"]').each(function(index, elem) {
				$(elem).attr('for', $(elem).attr('for').replace(new RegExp(rowIndex + '$'), newRowIndex));
			});
			newEntry.find('*[name$="_' + rowIndex + '"]').each(function(index, elem) {
				$(elem).attr('name', $(elem).attr('name').replace(new RegExp(rowIndex + '$'), newRowIndex));
			});
			newEntry.find('*[name$="_' + rowIndex + '[]"]').each(function(index, elem) {
				$(elem).attr('name', $(elem).attr('name').replace(new RegExp(rowIndex + '\\[\\]$'), newRowIndex + '[]'));
			});

			// Append the cloned row, and initialize its widgets.
			entry.after(newEntry);
			RPBCalendar.setupEventRow(newRowIndex);

		});

		// Delete-row button
		$('.rpbcalendar-removeEntryButton', entry).click(function(e) {
			e.preventDefault();
			entry.detach();
		});
	};

})( /* global RPBCalendar */ RPBCalendar, /* global moment */ moment, /* global jQuery */ jQuery );
