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


require_once(RPBCALENDAR_ABSPATH . 'models/traits/abstracttrait.php');
require_once(RPBCALENDAR_ABSPATH . 'helpers/validation.php');


/**
 * Common parameters for "today-events" and "upcoming events" widgets.
 */
abstract class RPBCalendarTraitWidgetBase extends RPBCalendarAbstractTrait
{
	protected $instance;
	private $title;
	private $inclusiveMode;
	private $filteredCategories;


	/**
	 * Constructor.
	 *
	 * @param array $instance
	 */
	public function __construct($instance)
	{
		$this->instance = $instance;
	}


	/**
	 * Return the common fields of the widget.
	 *
	 * @param string ... Widget specific fields.
	 * @return array
	 */
	protected function getWidgetFields()
	{
		$args = func_get_args();
		return array_merge(array('Title', 'InclusiveMode', 'FilteredCategories'), $args);
	}


	/**
	 * Default widget title.
	 *
	 * @return string
	 */
	protected abstract function buildDefaultTitle();


	/**
	 * Title of the widget.
	 *
	 * @return string
	 */
	public function getTitle()
	{
		if(!isset($this->title)) {
			$value = isset($this->instance['title']) ? RPBCalendarHelperValidation::validateString($this->instance['title']) : null;
			$this->title = isset($value) ? $value : $this->buildDefaultTitle();
		}
		return $this->title;
	}


	/**
	 * When true, only display events that belong to the categories returned by `getFilteredCategories()`.
	 * When false, do not display events that belong to those categories.
	 *
	 * @return boolean
	 */
	public function getInclusiveMode()
	{
		if(!isset($this->inclusiveMode)) {
			$value = isset($this->instance['inclusive-mode']) ? RPBCalendarHelperValidation::validateBoolean($this->instance['inclusive-mode']) : null;
			$this->inclusiveMode = isset($value) ? $value : false;
		}
		return $this->inclusiveMode;
	}


	/**
	 * List of event categories to include/exclude (depending on the value returned by `getInclusiveMode()`.
	 *
	 * @param boolean $asCommaSeparatedString True to return the result as a comma separated string (optional, default: false).
	 * @return mixed List of event category IDs, either as a string or as an array.
	 */
	public function getFilteredCategories($asCommaSeparatedString = false)
	{
		if(!isset($this->filteredCategories)) {
			$value = isset($this->instance['filtered-categories']) ? RPBCalendarHelperValidation::validateIntegerArray($this->instance['filtered-categories']) : null;
			$this->filteredCategories = isset($value) ? $value : array();
		}
		return $asCommaSeparatedString ? implode(',', $this->filteredCategories) : $this->filteredCategories;
	}
}
