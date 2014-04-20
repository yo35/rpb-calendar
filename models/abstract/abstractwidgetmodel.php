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


require_once(RPBCALENDAR_ABSPATH . 'models/abstract/abstractmodel.php');
require_once(RPBCALENDAR_ABSPATH . 'helpers/validation.php');


/**
 * Base class for the widget-associated models.
 */
abstract class RPBCalendarAbstractWidgetModel extends RPBCalendarAbstractModel
{
	private $instance;
	private $widgetName;
	private $title;


	/**
	 * Constructor.
	 *
	 * @param array $instance Array containing the information relative to the current widget instance.
	 */
	public function __construct($instance)
	{
		parent::__construct();
		$this->instance = $instance;
		$this->useTemplate($this->getWidgetName());
	}


	/**
	 * Name of the widget.
	 *
	 * @return string
	 */
	public function getWidgetName()
	{
		if(!isset($this->widgetName)) {
			$this->widgetName = preg_match('/^Widget(?:Form|Update|Print)(.*)$/', $this->getName(), $matches) ? $matches[1] : '';
		}
		return $this->widgetName;
	}


	/**
	 * Title of the widget.
	 *
	 * The default value of this attribute is obtained by calling the method `getDefaultTitle()`,
	 * that must be defined either in a sub-class or in a dynamically loaded trait.
	 *
	 * @return string
	 */
	public function getTitle()
	{
		if(!isset($this->title)) {
			$this->title = isset($this->instance['title']) ?
				RPBCalendarHelperValidation::trim($this->instance['title']) :
				$this->getDefaultTitle();
		}
		return $this->title;
	}


	/**
	 * Information about the current widget instance.
	 *
	 * @return array
	 */
	protected function getInstance()
	{
		return $this->instance;
	}
}
