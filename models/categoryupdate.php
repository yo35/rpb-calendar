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


require_once(RPBCALENDAR_ABSPATH.'models/abstract/abstractmodel.php');


/**
 * Process an update event category request.
 */
class RPBCalendarModelCategoryUpdate extends RPBCalendarAbstractModel
{
	private $traitLoaded = false;
	private $categoryID;


	public function __construct($categoryID)
	{
		parent::__construct();
		$this->categoryID = $categoryID;
	}


	/**
	 * Process the request.
	 */
	public function processRequest()
	{
		// Nothing to do if it is not the expected taxonomy type.
		if($_POST['taxonomy']!='rpbevent_category') {
			return;
		}

		// Load the required trait.
		if(!$this->traitLoaded) {
			$this->loadTrait('PostCategory');
			$this->traitLoaded = true;
		}

		// Call the update method.
		$this->updateCategory($this->categoryID);
	}
}
