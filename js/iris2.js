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
 * Extended Iris widget (color picker).
 *
 * @author Yoann Le Montagner
 *
 * @requires jQuery
 * @requires jQuery UI Widget
 * @requires Iris {@link http://automattic.github.io/Iris/}
 */
(function($)
{

	/**
	 * Internationalization constants.
	 */
	$.iris2 =
	{
		/**
		 * Label of the "random" button.
		 * @type {string}
		 */
		RANDOM_BUTTON_LABEL: 'Random',

		/**
		 * Label of the "clear" button.
		 * @type {string}
		 */
		CLEAR_BUTTON_LABEL: 'Clear',

		/**
		 * Description of the "random" button.
		 * @type {string}
		 */
		RANDOM_BUTTON_POPUP: 'Select a color at random',

		/**
		 * Description of the "clear" button.
		 * @type {string}
		 */
		CLEAR_BUTTON_POPUP: 'Unselect the current color'
	};


	/**
	 * Default color displayed by the widget.
	 * @type {string}
	 * @constant
	 */
	var DEFAULT_COLOR = '#0000ff';


	/**
	 * Ensure that the given string is a valid color value. If this is not the case,
	 * the default color is returned.
	 *
	 * @param {string} color
	 * @returns {string}
	 */
	function filterColor(color)
	{
		return (color=='' || /^#[0-9a-fA-F]{6}$/.test(color)) ? color : DEFAULT_COLOR;
	}


	/**
	 * Register a 'iris2' widget in the jQuery widget framework.
	 */
	$.widget('uicalendar.iris2',
	{

		/**
		 * Default options.
		 */
		options:
		{
			/**
			 * Whether a "random" button is displayed or not.
			 * @type {boolean}
			 */
			randomButton: true,

			/**
			 * Whether a "clear" button is displayed or not.
			 * @type {boolean}
			 */
			clearButton: true,

			/**
			 * CSS class that will be used to theme the buttons.
			 * @type {string}
			 */
			buttonClass: '',

			/**
			 * Color value.
			 * @type {string}
			 */
			color: DEFAULT_COLOR
		},


		/**
		 * Constructor.
		 */
		_create: function()
		{
			this.element.addClass('uicalendar-iris2');
			this.options.color = filterColor(this.options.color);
			this._refresh();
		},


		/**
		 * Destructor.
		 */
		_destroy: function()
		{
			this.element.empty().removeClass('uicalendar-iris2');
		},


		/**
		 * Option setter.
		 */
		_setOption: function(key, value)
		{
			if(key=='color') {
				var newColor = filterColor(value);
				if(newColor=='') {
					this.selectNone();
				}
				else {
					$('.uicalendar-iris2-colorPicker', this.element).iris('color', newColor);
				}
				return;
			}

			// For all options (except color), rebuild the widget.
			this.options[key] = value;
			this._refresh();
		},


		/**
		 * Select a color at random.
		 */
		selectRandom: function()
		{
			var newColor = Math.floor(Math.random()*256*256*256);
			$('.uicalendar-iris2-colorPicker', this.element).iris('color', '#' + newColor.toString(16));
		},


		/**
		 * Unselect the current color.
		 *
		 * This is equivalent to set the `color` property to `''`.
		 */
		selectNone: function()
		{
			$('.uicalendar-iris2-preview', this.element).addClass('uicalendar-iris2-transparent').css('background-color', 'transparent');
			this._updateColor('');
		},


		/**
		 * Update the color property.
		 */
		_updateColor: function(newColor)
		{
			if(this.options.color==newColor) {
				return;
			}
			this.options.color = newColor;
			this._trigger('change', null, { color: this.options.color });
		},


		/**
		 * Refresh the widget.
		 */
		_refresh: function()
		{
			// CSS class to use for the buttons.
			var buttonStyle = this.options.buttonClass=='' ? '' : (' ' + this.options.buttonClass);

			// Build the content skeleton.
			var content = '<div class="uicalendar-iris2-hBox"><div class="uicalendar-iris2-vBox">';
			content += '<div class="uicalendar-iris2-preview iris-border"></div>';
			if(this.options.randomButton) {
				content += '<button class="uicalendar-iris2-randomButton' + buttonStyle + '" title="' + $.iris2.RANDOM_BUTTON_POPUP + '">'
					+ $.iris2.RANDOM_BUTTON_LABEL + '</button>';
			}
			if(this.options.clearButton) {
				content += '<button class="uicalendar-iris2-clearButton' + buttonStyle + '" title="' + $.iris2.CLEAR_BUTTON_POPUP + '">'
					+ $.iris2.CLEAR_BUTTON_LABEL + '</button>';
			}
			content += '</div><div class="uicalendar-iris2-colorPicker"></div></div>';

			// Parse the content skeleton.
			$(content).appendTo(this.element.empty());

			// The DOM nodes inside the widget.
			var myself = this;
			var preview = $('.uicalendar-iris2-preview', this.element);

			// Set-up the color-picker sub-widget.
			$('.uicalendar-iris2-colorPicker', this.element).iris({
				color: this.options.color=='' ? DEFAULT_COLOR : this.options.color,
				hide: false,
				palettes: true,
				change: function(event, ui) {
					preview.removeClass('uicalendar-iris2-transparent').css('background-color', ui.color.toString());
					myself._updateColor(ui.color.toString());
				}
			});

			// Set-up the random-button sub-widget.
			if(this.options.randomButton) {
				$('.uicalendar-iris2-randomButton', this.element).click(function(e) { e.preventDefault(); myself.selectRandom(); });
			}

			// Set-up the clear-button sub-widget.
			if(this.options.clearButton) {
				$('.uicalendar-iris2-clearButton', this.element).click(function(e) { e.preventDefault(); myself.selectNone(); });
			}

			// Initialize the state of the preview node.
			if(this.options.color=='') {
				preview.addClass('uicalendar-iris2-transparent').css('background-color', 'transparent');
			}
			else {
				preview.css('background-color', this.options.color);
			}
		}

	});

})(jQuery);
