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


require_once(RPBCALENDAR_ABSPATH . 'models/abstract/customposteditlist.php');


/**
 * Model for the edition form for event categories.
 */
class RPBCalendarModelCategoryEdit extends RPBCalendarAbstractModelCustomPostEditList
{
	public function __construct()
	{
		parent::__construct();
		$this->loadTrait('Category');
		$this->loadTrait('AdminPageURLs');
	}


	/**
	 * Whether the form is intended to add a new event category or to edit an existing one.
	 */
	public function isNewCategoryMode()
	{
		return $this->getCategoryID()<0;
	}
}
