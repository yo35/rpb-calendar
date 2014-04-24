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
 * Model for the table showing the list of events.
 */
class RPBCalendarModelEventList extends RPBCalendarAbstractModel
{
	private $filterByCategoryURLTemplate;


	public function __construct()
	{
		parent::__construct();
		$this->loadTrait('Event');
	}


	/**
	 * Return the URL to use to display the list of all events of the given category.
	 *
	 * @param object $category
	 * @return string
	 */
	public function getFilterByCategoryURL($category)
	{
		if(!isset($this->filterByCategoryURLTemplate)) {
			$this->filterByCategoryURLTemplate = admin_url('edit.php?post_type=rpbevent&rpbevent_category=%1$s');
		}
		return sprintf($this->filterByCategoryURLTemplate, $category->ID);
	}
}
