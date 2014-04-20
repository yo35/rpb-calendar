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


require_once(RPBCALENDAR_ABSPATH . 'models/abstract/abstractwidgetmodel.php');


/**
 * Base class for the models used to render widget setting forms.
 */
abstract class RPBCalendarAbstractWidgetFormModel extends RPBCalendarAbstractWidgetModel
{
	private $wpWidget;
	private $titleFieldID;
	private $titleFieldName;


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
	 * Use the "WidgetForm" view by default.
	 *
	 * @return string
	 */
	public function getViewName()
	{
		return 'WidgetForm';
	}


	/**
	 * ID for the "title" field.
	 *
	 * @return string
	 */
	public function getTitleFieldID()
	{
		if(!isset($this->titleFieldID)) {
			$this->titleFieldID = $this->getFieldID('title');
		}
		return $this->titleFieldID;
	}


	/**
	 * Name for the "title" field.
	 *
	 * @return string
	 */
	public function getTitleFieldName()
	{
		if(!isset($this->titleFieldName)) {
			$this->titleFieldName = $this->getFieldName('title');
		}
		return $this->titleFieldName;
	}


	/**
	 * ID to use for a field named as `$fieldName`.
	 *
	 * @param string $fieldName
	 * @return string
	 */
	protected function getFieldID($fieldName)
	{
		return $this->wpWidget->get_field_id($fieldName);
	}


	/**
	 * Name attribute to use for a field named as `$fieldName`.
	 *
	 * @param string $fieldName
	 * @return string
	 */
	protected function getFieldName($fieldName)
	{
		return $this->wpWidget->get_field_name($fieldName);
	}
}
