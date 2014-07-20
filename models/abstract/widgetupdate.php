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
 * Base class for the models used to update the widget settings.
 */
abstract class RPBCalendarAbstractModelWidgetUpdate extends RPBCalendarAbstractModelWidget
{
	private $newInstance;
	private $validatedInstance;


	/**
	 * Constructor.
	 *
	 * @param array $oldInstance
	 * @param array $newInstance
	 */
	public function __construct($newInstance, $oldInstance)
	{
		parent::__construct($oldInstance);
		$this->newInstance = $newInstance;
	}


	/**
	 * Build and return an array containing the validated parameters of the new instance.
	 *
	 * @return array
	 */
	public function getValidatedInstance()
	{
		if(!isset($this->validatedInstance)) {
			$this->validatedInstance = array();
			foreach($this->getAllFields() as $field) {
				$value = null;
				if(isset($this->newInstance[$field])) {
					$value = $this->validateField($field, $this->newInstance[$field]);
				}
				$this->validatedInstance[$field] = isset($value) ? $value : $this->getOldFieldValue($field);
			}
		}
		return $this->validatedInstance;
	}


	/**
	 * Return the value of the given field in the old widget instance.
	 *
	 * @param string $field
	 * @return mixed
	 */
	private function getOldFieldValue($field)
	{
		$methodName = 'get' . $field;
		return $this->$methodName();
	}


	/**
	 * Validate the new value of the given field. This method must be overriden in derived classes.
	 *
	 * @param string $field
	 * @param string $value
	 */
	protected function validateField($field, $value)
	{
		return null;
	}
}
