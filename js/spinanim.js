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
 * Spinning animation widget (typically used as a loading indicator).
 *
 * @author Yoann Le Montagner
 *
 * @requires jQuery
 * @requires jQuery UI Widget
 */
(function($)
{
	'use strict';


	/**
	 * Default number of sticks.
	 *
	 * @constant
	 */
	var DEFAULT_NUMBER_OF_STICKS = 11;


	/**
	 * Default stick width.
	 *
	 * @constant
	 */
	var DEFAULT_STICK_WIDTH = 12;


	/**
	 * Default stick height.
	 *
	 * @constant
	 */
	var DEFAULT_STICK_HEIGHT = 4;


	/**
	 * Default inner diameter of the stick circle.
	 *
	 * @constant
	 */
	var DEFAULT_DIAMETER = 16;


	/**
	 * Generate the CSS attributes to apply to an element to translate and a rotate it.
	 *
	 * @param {number} tx Translation distance in the X direction (in pixels).
	 * @param {number} ty Translation distance in the Y direction (in pixels).
	 * @param {number} angle Angle of the rotation (in radian).
	 * @return string
	 */
	function cssTranslateRotate(tx, ty, angle)
	{
		var translate = 'translate(' + Math.round(tx) + 'px,' + Math.round(ty) + 'px)';
		var rotate    = 'rotate(' + Math.round(180 * angle / Math.PI) + 'deg)';
		var transform = 'transform: ' + translate + ' ' + rotate + ';';
		return transform + ' -webkit-' + transform + ' -ms-' + transform;
	}


	/**
	 * Generate the CSS attributes to set the dimensions (width and height) of an element.
	 *
	 * @param {number} width
	 * @param {number} height
	 * @return string
	 */
	function cssSize(width, height)
	{
		return 'width:' + Math.round(width) + 'px; height:' + Math.round(height) + 'px;';
	}


	/**
	 * Generate the CSS attributes to make an element have rounded corners.
	 *
	 * @param {number} radius
	 * @string
	 */
	function cssBorderRadius(radius)
	{
		return 'border-radius:' + Math.round(radius) + 'px;';
	}


	/**
	 * Register a 'spinanim' widget in the jQuery widget framework.
	 */
	$.widget('uicalendar.spinanim',
	{

		/**
		 * Default options.
		 */
		options: {},


		/**
		 * Constructor.
		 */
		_create: function()
		{
			this.element.addClass('uicalendar-spinanim');
			this._refresh();
		},


		/**
		 * Destructor.
		 */
		_destroy: function()
		{
			this._destroyTimer();
			this.element.empty().removeClass('uicalendar-spinanim');
		},


		/**
		 * Return the size of the container (in pixels).
		 *
		 * @return number
		 */
		containerSize: function()
		{
			return DEFAULT_DIAMETER + DEFAULT_STICK_WIDTH*2 + 3;
		},


		/**
		 * Generate the CSS attributes to apply to the container.
		 *
		 * @return string
		 */
		_containerStyle: function()
		{
			var sz = this.containerSize();
			return 'margin:auto; position:relative; ' + cssSize(sz,sz);
		},


		/**
		 * Generate the CSS attributes to apply to the sticks.
		 *
		 * @param {number} angle Expected angle (in radian).
		 * @return string
		 */
		_stickStyle: function(angle)
		{
			var w  = DEFAULT_STICK_WIDTH;
			var h  = DEFAULT_STICK_HEIGHT;
			var r  = (DEFAULT_DIAMETER + DEFAULT_STICK_WIDTH)/2;
			var sz = this.containerSize();
			var tx = r*Math.cos(angle) - w/2 + sz/2;
			var ty = r*Math.sin(angle) - h/2 + sz/2;
			return 'position:absolute; background-color:black; ' + cssSize(w,h) + ' ' + cssTranslateRotate(tx,ty,angle) +
				' ' + cssBorderRadius(h/2);
		},


		/**
		 * Refresh the widget.
		 */
		_refresh: function()
		{
			// Create the container.
			var content = '<div class="uicalendar-spinanim-container" style="' + this._containerStyle() + '">';

			// Create the sticks.
			var angle = 2 * Math.PI / DEFAULT_NUMBER_OF_STICKS;
			for(var k=0; k<DEFAULT_NUMBER_OF_STICKS; ++k) {
				content += '<div class="uicalendar-spinanim-stick" style="' + this._stickStyle(k*angle) + '"></div>';
			}

			// Close the container and render its content.
			content += '</div>';
			$(content).appendTo(this.element.empty());

			// Build the stick update callback.
			var indexOffset = 0;
			var sticks = $('.uicalendar-spinanim-stick', this.element);
			var callback = function() {
				sticks.each(function(i,e) {
					var val = (indexOffset - i) / DEFAULT_NUMBER_OF_STICKS;
					$(e).css('opacity', 1 - (val - Math.floor(val)));
				});
				++indexOffset;
			};

			// Animate the content.
			this._destroyTimer();
			this._timerID = window.setInterval(callback, 1000 / DEFAULT_NUMBER_OF_STICKS);
			callback();
		},


		/**
		 * Stop the timer used to animate the widget if not done yet.
		 */
		_destroyTimer: function()
		{
			if(this._timerID > 0) {
				clearInterval(this._timerID);
				this._timerID = -1;
			}
		},


		/**
		 * ID of the timer used to animate the widget.
		 */
		_timerID: -1

	});

})( /* global jQuery */ jQuery );
