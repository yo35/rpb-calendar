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


/**
 * Base class for the widget-associated models.
 */
abstract class RPBCalendarAbstractModelWidget extends RPBCalendarAbstractModel
{
	protected $instance;
	private $widgetName;
	private $fields = array();


	/**
	 * Constructor.
	 *
	 * @param array $instance Array containing the information relative to the current widget instance.
	 */
	public function __construct($instance)
	{
		parent::__construct();
		$this->instance = $instance;
	}


	/**
	 * Name of the widget.
	 *
	 * @return string
	 */
	public function getWidgetName()
	{
		if(!isset($this->widgetName)) {
			$this->widgetName = preg_match('/^Widget(?:Edit|Update|Print)(.*)$/', $this->getName(), $m) ? $m[1] : '';
		}
		return $this->widgetName;
	}


	/**
	 * Use the widget name for the template name by default.
	 *
	 * @return string
	 */
	public function getTemplateName()
	{
		return $this->getWidgetName();
	}


	/**
	 * Check whether a field is registered or not for the current widget.
	 *
	 * @param string $field
	 * @return boolean
	 */
	protected function isFieldRegistered($field)
	{
		return in_array($field, $this->fields);
	}


	/**
	 * Register a new field for the current widget.
	 *
	 * For each registered field, the model must provide a method `getField()`,
	 * that returns the value of the field for the current widget instance.
	 *
	 * @param string $field
	 */
	protected function registerField($field)
	{
		if(!in_array($field, $this->fields)) {
			$this->fields[] = $field;
		}
	}


	/**
	 * Register a new list of fields.
	 *
	 * @param array $fields
	 */
	protected function registerFields($fields)
	{
		$this->fields = array_unique(array_merge($this->fields, $fields));
	}


	/**
	 * Unregister a field for the current widget.
	 *
	 * @param string $field
	 */
	protected function unregisterField($field)
	{
		$this->fields = array_diff($this->fields, array($field));
	}


	/**
	 * Unregister a list of fields.
	 *
	 * @param array $fields
	 */
	protected function unregisterFields($fields)
	{
		$this->fields = array_diff($this->fields, $fields);
	}


	/**
	 * Return all the registered fields.
	 *
	 * @return array
	 */
	protected function getAllFields()
	{
		return $this->fields;
	}


	/**
	 * Convert a camel-case field name into lower case style, with hyphens as word separators.
	 *
	 * @param string $field
	 * @return string
	 */
	protected static function toLowerCase($field)
	{
		return strtolower(preg_replace('/([^A-Z])([A-Z])/', '$1-$2', $field));
	}
}
