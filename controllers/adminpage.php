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


require_once(RPBCALENDAR_ABSPATH . 'controllers/abstractcontroller.php');


/**
 * Show the requested plugin administration page.
 */
class RPBCalendarControllerAdminPage extends RPBCalendarAbstractController
{
	/**
	 * Constructor.
	 *
	 * @param string $modelName Name of the model to use. It is supposed to refer
	 *        to a model that inherits from the class AbstractModelAdminPage.
	 */
	public function __construct($modelName)
	{
		parent::__construct($modelName);
	}


	/**
	 * Entry-point of the controller.
	 */
	public function run()
	{
		// Execute the action requested by the POST data, if any.
		switch($this->getModel()->getPostAction()) {
			case 'update-options': $this->executeAction('PostOptions', 'updateOptions'); break;
			default: break;
		}

		// Create and display the view.
		$this->getView()->display();
	}


	/**
	 * Load the trait `$traitName`, and execute the method `$methodName` supposedly defined by the trait.
	 *
	 * @param string $traitName
	 * @param string $methodName
	 * @param string $capability Required capability to execute the action. Default is `'manage_options'`.
	 */
	private function executeAction($traitName, $methodName, $capability='manage_options')
	{
		if(!current_user_can($capability)) {
			return;
		}
		$model = $this->getModel();
		$model->loadTrait($traitName);
		$model->setPostMessage($model->$methodName());
	}
}
