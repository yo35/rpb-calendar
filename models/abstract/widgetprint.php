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
	private $widgetHidden;


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
	 * Whether the widget should be hidden or not.
	 *
	 * @return boolean
	 */
	public function getWidgetHidden()
	{
		if(!isset($this->widgetHidden)) {
			$this->widgetHidden = $this->getDefaultWidgetHidden();
		}
		return $this->widgetHidden;
	}


	/**
	 * Default "widget-hidden" attribute.
	 *
	 * @return boolean
	 */
	protected abstract function getDefaultWidgetHidden();
}
