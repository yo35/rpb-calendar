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
 * Base class for the models used to render widget setting forms.
 */
abstract class RPBCalendarAbstractModelWidgetEdit extends RPBCalendarAbstractModelWidget
{
	private $wpWidget;
	private $fieldIDs   = array();
	private $fieldNames = array();


	/**
	 * Constructor.
	 *
	 * @param array $instance Array containing the information relative to the current widget instance.
	 * @param object $wpWidget Instance of the WP_Widget class corresponding to the current widget.
	 */
	public function __construct($instance, $wpWidget)
	{
		parent::__construct($instance);
		$this->wpWidget = $wpWidget;
	}


	/**
	 * Use the "WidgetEdit" view by default.
	 *
	 * @return string
	 */
	public function getViewName()
	{
		return 'WidgetEdit';
	}


	/**
	 * Intercept the calls to methods `getFieldName()` and `getFieldID()`,
	 * and return the corresponding name or ID.
	 */
	public function __call($method, $args)
	{
		$pattern = '/^get(' . implode('|', $this->getAllFields()) . ')Field(Name|ID)$/';
		if(preg_match($pattern, $method, $m)) {
			return $m[2] === 'Name' ? $this->getFieldName($m[1]) : $this->getFieldID($m[1]);
		}
		else {
			return parent::__call($method, $args);
		}
	}


	/**
	 * ID to use for the given field.
	 *
	 * @param string $field
	 * @return string
	 */
	private function getFieldID($field)
	{
		if(!isset($this->fieldIDs[$field])) {
			$this->fieldIDs[$field] = $this->wpWidget->get_field_id($field);
		}
		return $this->fieldIDs[$field];
	}


	/**
	 * Name to use for the given field.
	 *
	 * @param string $field
	 * @return string
	 */
	private function getFieldName($field)
	{
		if(!isset($this->fieldNames[$field])) {
			$this->fieldNames[$field] = $this->wpWidget->get_field_name($field);
		}
		return $this->fieldNames[$field];
	}
}
