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


require_once(RPBCALENDAR_ABSPATH.'models/traits/abstracttrait.php');
require_once(RPBCALENDAR_ABSPATH.'helpers/validation.php');


/**
 * Meta information associated to an event category.
 */
class RPBCalendarTraitCategory extends RPBCalendarAbstractTrait
{
	private $categoryID = -1;
	private $color;


	/**
	 * ID of the currently selected event category.
	 *
	 * @return int
	 */
	public function getCategoryID()
	{
		return $this->categoryID;
	}


	/**
	 * Change the currently selected event category.
	 *
	 * @param int $categoryID ID of the newly selected event category.
	 */
	public function setCategoryID($categoryID)
	{
		if($this->categoryID==$categoryID) {
			return;
		}
		$this->categoryID = $categoryID;
		$this->color = null;
	}


	/**
	 * Return the color associated to the currently selected event category.
	 *
	 * @return string
	 */
	public function getCategoryColor()
	{
		if(is_null($this->color)) {
			$value = RPBCalendarHelperValidation::validateColor(get_option('rpbevent_category_'.$this->categoryID.'_color'), true);
			$this->color = is_null($value) ? '' : $value;
		}
		return $this->color;
	}
}
