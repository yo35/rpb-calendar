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


require_once(RPBCALENDAR_ABSPATH . 'models/abstract/widget.php');


/**
 * Base class for the models used to print the widgets in the front-end.
 */
abstract class RPBCalendarAbstractModelWidgetPrint extends RPBCalendarAbstractModelWidget
{
	private $theme;
	private $isWidgetHidden;
	private $hasTitle;


	/**
	 * Constructor.
	 *
	 * @param array $instance Array containing the information relative to the current widget instance.
	 * @param array $theme Array containing some data provided by the theme.
	 */
	public function __construct($instance, $theme)
	{
		parent::__construct($instance);
		$this->theme = $theme;
	}


	/**
	 * Use the "WidgetPrint" view by default.
	 *
	 * @return string
	 */
	public function getViewName()
	{
		return 'WidgetPrint';
	}


	/**
	 * Theme-related data.
	 *
	 * @return array
	 */
	public function getTheme()
	{
		return $this->theme;
	}


	/**
	 * DOM ID of the widget.
	 */
	public function getWidgetID()
	{
		return $this->theme['widget_id'];
	}


	/**
	 * Whether the widget should be hidden or not.
	 *
	 * @return boolean
	 */
	public function isWidgetHidden()
	{
		if(!isset($this->isWidgetHidden)) {
			$this->isWidgetHidden = $this->computeIsWidgetHidden();
		}
		return $this->isWidgetHidden;
	}


	/**
	 * Override this method to hide the widget in particular situations. By default, the widget is never hidden.
	 *
	 * @return boolean
	 */
	protected function computeIsWidgetHidden()
	{
		return false;
	}


	/**
	 * Whether the widget has a title or not.
	 *
	 * @return boolean
	 */
	public function hasTitle()
	{
		if(!isset($this->hasTitle)) {
			$this->hasTitle = $this->isFieldRegistered('Title') && $this->getTitle() !== '';
		}
		return $this->hasTitle;
	}
}
