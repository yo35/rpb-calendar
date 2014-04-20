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
 * Base class for the models used to render widget setting forms.
 */
abstract class RPBCalendarAbstractWidgetFormModel extends RPBCalendarAbstractModel
{
	private $wpWidget;
	private $instance;
	private $widgetName;
	private $title;
	private $titleFieldID;
	private $titleFieldName;


	/**
	 * Constructor.
	 *
	 * @param object $wpWidget Instance of the WP_Widget class corresponding to the current widget.
	 * @param array $instance Array containing the information relative to the current widget instance.
	 */
	public function __construct($wpWidget, $instance)
	{
		parent::__construct();
		$this->wpWidget = $wpWidget;
		$this->instance = $instance;
		$this->useTemplate($this->getWidgetName());
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
	 * Name of the widget.
	 *
	 * @return string
	 */
	public function getWidgetName()
	{
		if(!isset($this->widgetName)) {
			$this->widgetName = preg_match('/^WidgetForm(.*)$/', $this->getName(), $matches) ? $matches[1] : '';
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
	 * Information about the current widget instance.
	 *
	 * @return array
	 */
	protected function getInstance()
	{
		return $this->instance;
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
