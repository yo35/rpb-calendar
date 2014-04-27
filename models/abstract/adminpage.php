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
 * Base class for the models used to render the plugin administration pages.
 */
abstract class RPBCalendarAbstractAdminPageModel extends RPBCalendarAbstractModel
{
	private $adminPageName;
	private $postAction;
	private $title;


	/**
	 * Constructor.
	 */
	public function __construct($title)
	{
		parent::__construct();
		$this->title = $title;
		$this->setTemplateName($this->getAdminPageName());
	}


	/**
	 * Use the "AdminPage" view by default.
	 *
	 * @return string
	 */
	public function getViewName()
	{
		return 'AdminPage';
	}


	/**
	 * Name of the administration page.
	 *
	 * @return string
	 */
	public function getAdminPageName()
	{
		if(!isset($this->adminPageName)) {
			$this->adminPageName = preg_match('/^AdminPage(.*)$/', $this->getName(), $matches) ? $matches[1] : '';
		}
		return $this->adminPageName;
	}


	/**
	 * Return the name of the action that should be performed by the server.
	 * The action is initiated by the user when clicking on a "submit" button in
	 * an HTML form with its method attribute set to POST.
	 *
	 * This function may return an empty string if no action is required.
	 *
	 * @return string
	 */
	public function getPostAction()
	{
		if(!isset($this->postAction)) {
			$this->postAction = isset($_POST['rpbcalendar_action']) ? $_POST['rpbcalendar_action'] : '';
		}
		return $this->postAction;
	}


	/**
	 * Human-readable title of the page.
	 *
	 * @return string
	 */
	public function getTitle()
	{
		return $this->title;
	}
}
