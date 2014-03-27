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


require_once(RPBCALENDAR_ABSPATH . 'helpers/loader.php');


/**
 * Base class for the controllers.
 */
abstract class RPBCalendarAbstractController
{
	private $modelName;
	private $model = null;
	private $view  = null;


	/**
	 * Constructor
	 *
	 * @param string $modelName Name of the model to use.
	 */
	protected function __construct($modelName)
	{
		$this->modelName = $modelName;
	}


	/**
	 * Load (if necessary) and return the model.
	 */
	public function getModel()
	{
		if(is_null($this->model)) {
			$this->model = RPBCalendarHelperLoader::loadModel($this->modelName);
		}
		return $this->model;
	}


	/**
	 * Load (if necessary) and return the view.
	 */
	public function getView()
	{
		if(is_null($this->view)) {
			$this->view = RPBCalendarHelperLoader::loadView($this->getModel());
		}
		return $this->view;
	}


	/**
	 * Entry-point of the controller.
	 */
	public abstract function run();
}