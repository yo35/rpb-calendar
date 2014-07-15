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


/**
 * Helper functions for dynamic class loading.
 */
abstract class RPBCalendarHelperLoader
{
	/**
	 * Load the model corresponding to the given model name.
	 *
	 * @param string $modelName Name of the model.
	 * @param mixed ... Arguments to pass to the model (optional).
	 * @return object New instance of the model.
	 */
	public static function loadModel($modelName)
	{
		$fileName  = strtolower($modelName);
		$className = 'RPBCalendarModel' . $modelName;
		require_once(RPBCALENDAR_ABSPATH . 'models/' . $fileName . '.php');
		if(func_num_args() === 1) {
			return new $className;
		}
		else {
			$args  = func_get_args();
			$clazz = new ReflectionClass($className);
			return $clazz->newInstanceArgs(array_slice($args, 1));
		}
	}


	/**
	 * Load the trait corresponding to the given trait name.
	 *
	 * @param string $traitName Name of the trait.
	 * @param mixed ... Arguments to pass to the trait (optional).
	 * @return object New instance of the trait.
	 */
	public static function loadTrait($traitName)
	{
		$fileName  = strtolower($traitName);
		$className = 'RPBCalendarTrait' . $traitName;
		require_once(RPBCALENDAR_ABSPATH . 'models/traits/' . $fileName . '.php');
		if(func_num_args() === 1) {
			return new $className;
		}
		else {
			$args  = func_get_args();
			$clazz = new ReflectionClass($className);
			return $clazz->newInstanceArgs(array_slice($args, 1));
		}
	}


	/**
	 * Load the view whose name is returned by `$model->getViewName()`.
	 *
	 * @param object $model
	 * @return object New instance of the view.
	 */
	public static function loadView($model)
	{
		$viewName  = $model->getViewName();
		$fileName  = strtolower($viewName);
		$className = 'RPBCalendarView' . $viewName;
		require_once(RPBCALENDAR_ABSPATH . 'views/' . $fileName . '.php');
		return new $className($model);
	}
}
