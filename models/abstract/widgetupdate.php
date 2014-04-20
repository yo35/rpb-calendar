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
require_once(RPBCALENDAR_ABSPATH . 'helpers/validation.php');


/**
 * Base class for the models used to update the widget settings.
 */
abstract class RPBCalendarAbstractWidgetUpdateModel extends RPBCalendarAbstractWidgetModel
{
	private $validatedInstance;
	private $newInstance;
	private $newTitle;


	/**
	 * Constructor.
	 *
	 * @param array $instance Array containing the information relative to the old widget instance.
	 * @param array $newInstance New widget parameters.
	 */
	public function __construct($instance, $newInstance)
	{
		parent::__construct($instance);
		$this->newInstance = $newInstance;

		// Initialize the new widget parameters.
		$this->newTitle = isset($newInstance['title']) ? RPBCalendarHelperValidation::trim($newInstance['title']) : null;
	}


	/**
	 * Build and return an array containing the validated parameters of the new instance.
	 *
	 * @return array
	 */
	public function getValidatedInstance()
	{
		if(!isset($this->validatedInstance)) {
			$this->validatedInstance = $this->makeValidatedInstance();
		}
		return $this->validatedInstance;
	}


	/**
	 * Initialize the set of validated parameters. This method may be overloaded in derived classes.
	 */
	protected function makeValidatedInstance()
	{
		return array('title' => isset($this->newTitle) ? $this->newTitle : $this->getTitle());
	}


	/**
	 * Return the new value of the title parameter.
	 *
	 * @return string May be null if the new title is invalid.
	 */
	public function getNewTitle()
	{
		return $this->newTitle;
	}


	/**
	 * Return the new set of widget parameters.
	 *
	 * @return array
	 */
	protected function getNewInstance()
	{
		return $this->newInstance;
	}
}
